<?php

namespace App\Mail;

use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PerformanceAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Website $website,
        public array $failingMetrics, // bijv. ['LCP' => ['value' => 3.1, 'threshold' => 2.5, 'unit' => 's']]
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Performance alert: {$this->website->name} heeft slechte metrics",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.performance-alert',
        );
    }
}