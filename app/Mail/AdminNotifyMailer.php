<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNotifyMailer extends Mailable
{
    use Queueable, SerializesModels;

    private object $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(object $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('admin.site_new_notify', ['site' => env('APP_NAME')]))
            ->view('emails.admin', ['data' => $this->data]);
    }
}
