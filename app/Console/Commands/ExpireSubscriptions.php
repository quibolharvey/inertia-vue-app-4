<?php

namespace App\Console\Commands;

// app/Console/Commands/ExpireSubscriptions.php

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\Subscription;

class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';
    protected $description = 'Expire subscriptions that passed their expiry_date';

    public function handle()
    {
        Subscription::where('status', '!=', 'expired')
            ->whereDate('expiry_date', '<', Carbon::now())
            ->update(['status' => 'expired']);

        $this->info('Expired subscriptions updated.');
    }
}
