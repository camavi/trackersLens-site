<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageReceived;
use App\Mail\LaunchSubscriptionReceived;
use App\Models\ContactMessage;
use App\Models\LaunchSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class LandingController extends Controller
{
    public function subscribe(Request $request): JsonResponse
    {

        $data = $request->validate([
            'email' => ['required', 'email:rfc', 'max:255'],
            'source' => ['sometimes', 'string', 'max:80'],
            'locale' => ['sometimes', 'nullable', 'string', 'max:8'],
        ]);

        $subscription = LaunchSubscription::updateOrCreate(
            ['email' => mb_strtolower($data['email'])],
            [
                'source' => $data['source'] ?? 'landing',
                'locale' => $data['locale'] ?? null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        );

        $this->sendInternalMail(new LaunchSubscriptionReceived($subscription));

        return response()->json([
            'message' => 'Subscription saved.',
            'subscription' => [
                'email' => $subscription->email,
                'source' => $subscription->source,
            ],
        ], $subscription->wasRecentlyCreated ? Response::HTTP_CREATED : Response::HTTP_OK);
    }

    public function contact(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'source' => ['sometimes', 'string', 'max:80'],
            'locale' => ['sometimes', 'nullable', 'string', 'max:8'],
        ]);

        $contactMessage = ContactMessage::create([
            'name' => $data['name'],
            'email' => mb_strtolower($data['email']),
            'message' => $data['message'],
            'source' => $data['source'] ?? 'landing',
            'locale' => $data['locale'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $this->sendInternalMail(new ContactMessageReceived($contactMessage));

        return response()->json([
            'message' => 'Contact message saved.',
        ], Response::HTTP_CREATED);
    }

    private function sendInternalMail(object $mail): void
    {
        $debugId = (string) Str::uuid();
        $startedAt = microtime(true);
        $to = config('mail.internal_to');
        $debugContext = [
            'debug_id' => $debugId,
            'mail' => $mail::class,
            'to' => $to,
            'env' => app()->environment(),
            'mailer' => config('mail.default'),
            'smtp_host' => config('mail.mailers.smtp.host'),
            'smtp_port' => config('mail.mailers.smtp.port'),
            'smtp_scheme' => config('mail.mailers.smtp.scheme'),
            'smtp_username' => config('mail.mailers.smtp.username'),
            'from' => config('mail.from.address'),
            'log_path' => config('logging.channels.single.path'),
        ];

        $this->logMailDebug('Landing internal mail debug: started.', $debugContext);

        if (! $to) {
            $this->logMailDebug('Landing internal mail debug: skipped because MAIL_INTERNAL_TO is empty.', $debugContext + [
                'duration_ms' => $this->elapsedMilliseconds($startedAt),
            ]);

            return;
        }

        try {
            $this->logMailDebug('Landing internal mail debug: sending.', $debugContext);

            Mail::to($to)->send($mail);

            $this->logMailDebug('Landing internal mail debug: sent.', $debugContext + [
                'duration_ms' => $this->elapsedMilliseconds($startedAt),
            ]);
        } catch (Throwable $exception) {
            $this->logMailDebug('Landing internal mail debug: failed.', $debugContext + [
                'duration_ms' => $this->elapsedMilliseconds($startedAt),
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);
        }
    }

    private function logMailDebug(string $message, array $context): void
    {
        try {
            Log::warning($message, $context);
        } catch (Throwable $exception) {
            error_log($message.' '.json_encode($context + [
                'log_exception' => $exception->getMessage(),
            ]));
            // Logging must never break a public landing form response.
        }
    }

    private function elapsedMilliseconds(float $startedAt): int
    {
        return (int) round((microtime(true) - $startedAt) * 1000);
    }
}
