<?php

namespace App\Console\Commands;

use App\Models\PlanSubscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckExpiredPlans extends Command
{
    protected $signature = 'plans:check-expired';
    protected $description = 'Check and update expired plan subscriptions';

    public function handle()
    {
        $expiredSubscriptions = PlanSubscription::where('status', 'active')
            ->where('end_date', '<', Carbon::now())
            ->get();

        foreach ($expiredSubscriptions as $subscription) {
            $subscription->update(['status' => 'expired']);
        }

        $this->info('Checked and updated ' . $expiredSubscriptions->count() . ' expired subscriptions.');
    }
}
