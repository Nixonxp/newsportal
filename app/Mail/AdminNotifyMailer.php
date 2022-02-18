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
        return $this->subject(__('admin.sub_author_posted_new_post'))
            ->view('email.admin', ['data' => $this->data]);
    }
}
