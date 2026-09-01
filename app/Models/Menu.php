<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pages;
use App\Models\Module;
use Illuminate\Support\Facades\Route;

class Menu extends Model
{
    protected $table = 'menu';

    protected $fillable = [
    'nama',
    'tipe',
    'page_id',
    'module_id',
    'url',
    'parent_id',
    'sort_order',
    'is_active',
];

   public function page()
{
    return $this->belongsTo(Pages::class);
}

public function module()
{
    return $this->belongsTo(Module::class);
}

public function parent()
{
    return $this->belongsTo(Menu::class, 'parent_id');
}

public function children()
{
    return $this->hasMany(Menu::class, 'parent_id')
        ->where('is_active', 1)
        ->orderBy('sort_order');
}

public function getFullPathAttribute(): string
{
    $segments = [];
    $menu = $this;

    while ($menu) {
        $segments[] = $menu->nama;
        $menu = $menu->parent;
    }

    return implode(' > ', array_reverse($segments));
}

public function getLinkAttribute()
{
    if ($this->page) {

        if (Route::has('public.pages.show')) {

            return route(
                'public.pages.show',
                $this->page->slug
            );

        }

        return route('not-found');

    }

    if ($this->module) {

        if (Route::has($this->module->route_name)) {

            return route(
                $this->module->route_name
            );

        }

        return route('not-found');

    }

    return $this->url ?: route('not-found');
}
}