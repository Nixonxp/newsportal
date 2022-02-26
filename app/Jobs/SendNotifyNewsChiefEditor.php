<?php

namespace App\Jobs;

use App\Exceptions\ServiceException;
use App\Mail\ChiefEditorNotifyMailer;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNotifyNewsChiefEditor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private object $data;
    private $editors;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(object $data)
    {
        $this->data = $data;
        try {
            $this->getNewsLinks();
            $this->editors = User::select('id', 'email')
                ->withChiefEditorRole()
                ->get();
        } catch (ServiceException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @throws ServiceException
     */
    protected function getNewsLinks()
    {
        if (!empty($this->data->id)) {
            $this->data->links = Post::select('id', 'title')
                ->whereIn('id', $this->data->id)
                ->get()
                ->map(fn(Post $post) => (object)[
                    'href' => route('admin.posts.show', $post),
                    'title' => $post->title,
                ]);
        } else {
            throw new ServiceException('Not set param id' . PHP_EOL);
        }

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->editors->isNotEmpty()) {
            Mail::to($this->editors)->send(new ChiefEditorNotifyMailer($this->data));
        }
    }
}
