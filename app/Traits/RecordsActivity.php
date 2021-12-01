<?php


namespace App\Traits;


use App\Models\Activity;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;

trait RecordsActivity
{
    public $oldAttributes = [];

    public static function bootRecordsActivity()
    {
        foreach(self::recordableEvents() as $event){
            static::$event(function ($model) use($event){
                $model->recordActivity($model->activityDescription($event));
            });
            if($event === 'updated'){
                static::updating(function ($model) {
                    $model->oldAttributes = $model->getOriginal();
                });
            }
        }
    }

    /**
     * @return string[]
     */
    public static function recordableEvents(): array
    {
        if (isset(static::$recordableEvents)) {
            return static::$recordableEvents;
        }
        return ['created', 'updated'];

    }

    /**
     * @param string $description
     * @return string
     */
    protected function activityDescription(string $description): string
    {
        return "{$description}_" . strtolower(class_basename($this));
    }


    public function recordActivity($description)
    {
        $this->activity()->create([
            'user_id' => ($this->project ?? $this)->owner->id,
            'description' => $description,
            'changes' =>  $this->activityChanges(),
            'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project_id
        ]);
    }


    public function activity(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    public function activityChanges()
    {
        if($this->wasChanged()){
            return [
                'before' => Arr::except(array_diff($this->oldAttributes, $this->getAttributes()),'updated_at'),
                'after' =>  Arr::except($this->getChanges(), 'updated_at')
            ];
        }

    }

}
