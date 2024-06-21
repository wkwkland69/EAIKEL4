<?php

namespace App\Listeners;

use App\Events\TaskProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewTask
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TaskProcessed  $event
     * @return void
     */
    public function handle(TaskProcessed $event)
    {
        $task = $event->task;
        $task->addTask($task->name, $task->due_date, $task->responsible_user, $task->status, $task->user_id);
    }
}
