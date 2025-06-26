<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueuedVerifyEmail extends VerifyEmail implements ShouldQueue
{
    public $queue = 'emails';

    /**
     * The queue connection that should be used for the notification.
     *
     * @var string|null
     */
    public $connection = null; // or set to 'database', 'redis', etc.

    /**
     * The number of seconds to delay before the job should be processed.
     *
     * @var \DateTimeInterface|\DateInterval|int|null
     */
    public $delay = null;
}
