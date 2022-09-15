<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    /**
     * Get all of the menu has children.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id', 'id')->with('children');
    }

    /**
     * Get infinite level of menus.
     *
     * @return "MenuItem": [{}]
     */
    public static function getNestedMenus()
    {
        return self::with('children')->whereNull('parent_id')->get();
    }
}
