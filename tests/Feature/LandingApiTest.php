<?php

namespace Tests\Feature;

use App\Mail\ContactMessageReceived;
use App\Mail\LaunchSubscriptionReceived;
use App\Models\ContactMessage;
use App\Models\LaunchSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class LandingApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_launch_subscription_is_persisted_and_notification_is_sent(): void
    {
        Mail::fake();
        config(['mail.internal_to' => 'hello@trackerslens.com']);

        $this->postJson('/api/launch-subscriptions', [
            'email' => 'early@example.com',
            'source' => 'launch_modal',
            'locale' => 'it',
        ])
            ->assertCreated()
            ->assertJsonPath('subscription.email', 'early@example.com');

        $this->assertDatabaseHas(LaunchSubscription::class, [
            'email' => 'early@example.com',
            'source' => 'launch_modal',
            'locale' => 'it',
        ]);

        Mail::assertSent(LaunchSubscriptionReceived::class);
    }

    public function test_launch_subscription_is_updated_when_email_already_exists(): void
    {
        LaunchSubscription::create([
            'email' => 'early@example.com',
            'source' => 'footer',
        ]);

        $this->postJson('/api/launch-subscriptions', [
            'email' => 'early@example.com',
            'source' => 'launch_modal',
        ])->assertOk();

        $this->assertSame(1, LaunchSubscription::query()->count());
        $this->assertDatabaseHas(LaunchSubscription::class, [
            'email' => 'early@example.com',
            'source' => 'launch_modal',
        ]);
    }

    public function test_contact_message_is_persisted_and_notification_is_sent(): void
    {
        Mail::fake();
        config(['mail.internal_to' => 'hello@trackerslens.com']);

        $this->postJson('/api/contact-messages', [
            'name' => 'Carlos',
            'email' => 'carlos@example.com',
            'message' => 'I want early access for Trackers Lens.',
            'source' => 'contact_modal',
            'locale' => 'en',
        ])
            ->assertCreated()
            ->assertJsonPath('message', 'Contact message saved.');

        $this->assertDatabaseHas(ContactMessage::class, [
            'name' => 'Carlos',
            'email' => 'carlos@example.com',
            'source' => 'contact_modal',
        ]);

        Mail::assertSent(ContactMessageReceived::class);
    }
}
