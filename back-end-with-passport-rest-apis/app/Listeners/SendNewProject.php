<?php

namespace App\Listeners;

use App\Events\ProjectProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewProject
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
     * @param  \App\Events\ProjectProcessed  $event
     * @return void
     */
    public function handle(ProjectProcessed $event)
    {
        $project = $event->project;
        $project->addProject($project->name, $project->budget, $project->responsible_user, $project->status, $project->user_id);
    }
}
