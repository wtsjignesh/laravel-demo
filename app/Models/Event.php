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

    /**
     * Access all of the future workshops for the event.
     *
     * @return "Event": [{}]
     */
    public function scopeFuture($query){
        $constraint = fn ($query) =>
        $query->where('start', '>', date("Y-m-d H:i:s"));
        return $query->whereHas("workshops", $constraint)
        ->with(["workshops" => $constraint]);
    }

    /**
     * Get all of the future workshops for the event.
     *
     * @return "Event": [{}]
     */
    public static function getFutureEvents()
    {
        return self::future()->get();
    }
}
