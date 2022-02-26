<?php

namespace Tests\Feature\Jobs;

use App\Jobs\SendNotifyNewsChiefEditor;
use App\Mail\ChiefEditorNotifyMailer;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mail;
use Tests\TestCase;

class SendNotifyNewsChiefEditorTest extends TestCase
{
    use DatabaseTransactions;

    public function testEditorList()
    {
        Mail::fake();

        $editors = User::select('id', 'email')->withChiefEditorRole()->get();

        $testObj = new SendNotifyNewsChiefEditor((object)[
            'id' => [1]
        ]);


        $testObj->handle();
        Mail::assertSent(ChiefEditorNotifyMailer::class, $editors->count());

        $resultEditors = $this->getProtectedProperty($testObj, 'editors');

        $this->assertEquals($editors, $resultEditors);
    }

    public function testNewsList()
    {
        $news = Post::select('id', 'title')->inRandomOrder()->first();

        $testObj = new SendNotifyNewsChiefEditor((object)[
            'id' => [$news->id]
        ]);

        $resultNews = $this->getProtectedProperty($testObj, 'data');

        $this->assertEquals($resultNews->links,
            collect([
                (object)[
                    'href' => route('admin.posts.show', $news),
                    'title' => $news->title,
                ]
            ]));
    }
}
