<?php

namespace App\Jobs;

use App\Mail\SubscribePostMailer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProccessSendingSubscribeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private object $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(object $data)
    {
        $this->data =$data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->data->user_email)->send(new SubscribePostMailer($this->data));
    }
}
