<?php

namespace App\Models;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Project extends Model
{
    use HasFactory, RecordsActivity;

    protected $fillable = ['title', 'description', 'notes'];


    public function path(): string
    {
        return "/projects/{$this->id}";
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function addTasks($tasks): Collection
    {
        return $this->tasks()->createMany($tasks);
    }

    public function addTask(string $body): Model
    {
        return $this->tasks()->create(['body' => $body]);
    }

    public function activity(): HasMany
    {
        return $this->hasMany(Activity::class)->latest();
    }

    public function invite(User $user)
    {
        $this->members()->attach($user);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->withTimestamps();
    }
}
