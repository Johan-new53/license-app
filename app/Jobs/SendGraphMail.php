<?php

namespace App\Jobs;

use App\Services\GraphMailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendGraphMail implements ShouldQueue
{
    use Queueable;

    protected $to;
    protected $subject;
    protected $body;

    public function __construct($to, $subject, $body)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
    }

    public function handle(GraphMailService $mail): void
    {
        $mail->send($this->to, $this->subject, $this->body);
    }
}