<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Task;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function created(Task $task)
    {
        $task->project->recordActivity('created-task');
    }

    public function deleted(Task $task)
    {
        $task->project->recordActivity('deleted-task');
    }

}
