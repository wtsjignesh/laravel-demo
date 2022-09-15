<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * Get all of the workshops for the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function workshops()
    {
        return $this->hasMany(Workshop::class);
    }

    /**
     * Access all of the workshops for the event.
     *
     * @return "Event": [{}]
     */
    public static function getAllEventsWithWorkshops(){
        return self::with('workshops')->get();
    }
}
