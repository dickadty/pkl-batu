<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'modules';

    protected $fillable = [
        'nama',
        'slug',
        'route_name',
        'view_name',
        'description',
        'is_active'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function pages()
    {
        return $this->hasMany(Pages::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function getLinkAttribute()
{
    if ($this->page) {
        return route(
            'pages.show',
            $this->page->slug
        );
    }

    if ($this->module) {

        if (
            Route::has(
                $this->module->route_name
            )
        ) {

            return route(
                $this->module->route_name
            );

        }

        return '#';

    }

    return $this->url ?: '#';
}
}