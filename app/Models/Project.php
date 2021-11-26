<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;


class Project extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'notes'];


    public $old = [];

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
        $this->activity()->create([
            'description' => $description,
            'changes' =>  $this->activityChanges($description)
        ]);
    }

    public function activityChanges($description): ?array
    {
        if($description != 'updated_project'){
            return null;
        }
        return [
            'before' => Arr::except(array_diff($this->old, $this->getAttributes()), 'updated_at'),
            'after' =>  Arr::except($this->getChanges(), 'updated_at')
        ];
    }

    public function activity(): HasMany
    {
        return $this->hasMany(Activity::class)->latest();
    }
}
