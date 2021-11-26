<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'project_id', 'completed'];

    protected $touches = ['project'];

    protected $casts = [
        'completed' => 'boolean'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function path(): string
    {
        return 'projects/'.$this->project->id.'/tasks/'.$this->id;
    }

    public function complete()
    {
        $this->update(['completed' => true]);
        $this->recordActivity('completed_task');
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);
        $this->recordActivity('incompleted_task');
    }

    public function recordActivity($description)
    {
        $this->activity()->create([
            'description' => $description,
            'project_id' => $this->project_id
        ]);
    }

    public function activity(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }
}
