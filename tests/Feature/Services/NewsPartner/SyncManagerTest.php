<?php

namespace Tests\Feature\Services\NewsPartner;

use App\Jobs\ProccessSendAdminEmail;
use App\Jobs\SendNotifyNewsChiefEditor;
use App\Services\NewsPartner\SyncManager;
use Exception;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SyncManagerTest extends TestCase
{
    use DatabaseTransactions;

    public function testHandleSuccess()
    {
        Queue::fake();

        $testObject = $this->getTestObjectWithData([
            'created' => [
                'id' => [1]
            ]
        ]);

        $testObject->handle();

        Queue::assertPushed(SendNotifyNewsChiefEditor::class);
    }

    public function testHandleSuccessNotNewRecords()
    {
        Queue::fake();

        $testObject = $this->getTestObjectWithData([
            'found' => [
                'id' => [1]
            ]
        ]);

        $testObject->handle();

        Queue::assertNotPushed(SendNotifyNewsChiefEditor::class);
    }

    public function testHandleSuccessFromException()
    {
        Queue::fake();

        $testObject = $this->getTestObjectWithException();
        $testObject->handle();

        Queue::assertPushed(ProccessSendAdminEmail::class);
    }

    private function getTestObjectWithData($data)
    {
        $testClass =  new class extends SyncManager {
            public $data;
            protected function syncHeadlinesNewsCategories(): array
            {
                return $this->data;
            }
        };

        $testClass->data = $data;

        return $testClass;
    }

    private function getTestObjectWithException()
    {
        return new class extends SyncManager {
            protected function syncHeadlinesNewsCategories(): array
            {
                throw new Exception;
            }
        };
    }
}
