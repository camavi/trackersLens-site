<?php

namespace App\Mail;

use App\Models\LaunchSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LaunchSubscriptionReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(private readonly LaunchSubscription $subscription) {}

    public function build(): self
    {
        return $this
            ->subject('Trackers Lens launch subscription')
            ->text('emails.launch-subscription-received', [
                'subscription' => $this->subscription,
            ]);
    }
}
