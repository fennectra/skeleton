<?php

/**
 * Definition des taches planifiees.
 *
 * Usage : php bin/cli schedule:run
 *
 * En production, ajoutez un cron systeme :
 *   * * * * * cd /path/to/project && php bin/cli schedule:run >> /dev/null 2>&1
 */

use Fennec\Core\Scheduler\Schedule;

return function (Schedule $schedule): void {
    // Exemples :

    // $schedule->call(fn () => echo "Ping!\n")
    //     ->name('heartbeat')
    //     ->everyFiveMinutes();

    // $schedule->command('queue:work')
    //     ->name('process-queue')
    //     ->everyMinute()
    //     ->withoutOverlapping(300);

    // $schedule->call([App\Jobs\CleanupExpiredTokens::class, 'handle'])
    //     ->name('cleanup:tokens')
    //     ->daily()
    //     ->withoutOverlapping();
};
