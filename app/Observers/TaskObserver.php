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
        $task->recordActivity('created_task');
    }

    public function deleted(Task $task)
    {
        $task->recordActivity('deleted_task');
    }

}
