<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\Feature\ActivityFeedTest;


class Project extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'notes'];

    public function path(): string
    {
        return "projects/{$this->id}";
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function addTask(string $body)
    {
        return $this->tasks()->create(['body' => $body]);
    }

    public function recordActivity($description)
    {
        $this->activity()->create(compact('description'));
    }

    public function activity(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
