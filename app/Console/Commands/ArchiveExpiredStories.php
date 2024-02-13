<?php

namespace App\Console\Commands;

use App\Models\Story;
use App\Models\Subscription;
use Illuminate\Console\Command;

class ArchiveExpiredStories extends Command
{

    protected $signature = 'stories:archive';
    protected $description = 'Archive stories of expired subscriptions';

    public function handle()
    {
        $expiredSubscriptions = Subscription::where('end_date', '<', now())->get();
        foreach ($expiredSubscriptions as $subscription) {
            Story::where('user_id', $subscription->user_id)->update(['archived' => true]);
        }
    }
}
