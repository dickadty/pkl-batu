<?php

namespace App\View\Components;

use App\Models\Menu;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view): void
    {
        $menus = Menu::with([
            'page',
            'module',
            'children' => function ($query) {
                $query->where('is_active', 1)
                    ->with([
                        'page',
                        'module',
                        'children' => function ($childQuery) {
                            $childQuery->where('is_active', 1)
                                ->with([
                                    'page',
                                    'module'
                                ])
                                ->orderBy('sort_order');
                        }
                    ])
                    ->orderBy('sort_order');
            }
        ])
        ->whereNull('parent_id')
        ->where('is_active', 1)
        ->orderBy('sort_order')
        ->get();

        $view->with('menus', $menus);
    }
}