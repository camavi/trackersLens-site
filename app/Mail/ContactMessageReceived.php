<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(private readonly ContactMessage $contactMessage) {}

    public function build(): self
    {
        return $this
            ->replyTo($this->contactMessage->email, $this->contactMessage->name)
            ->subject('Trackers Lens contact request')
            ->text('emails.contact-message-received', [
                'contactMessage' => $this->contactMessage,
            ]);
    }
}
