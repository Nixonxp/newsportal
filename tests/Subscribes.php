<?php

namespace Tests;

use App\Models\Subscriber;

trait Subscribes
{
    private function subscribeManually(int $userId, int $authorId): void
    {
        $subscribeAction = Subscriber::create([
            'user_id' => $userId,
            'author_id' => $authorId,
        ]);
    }
}
