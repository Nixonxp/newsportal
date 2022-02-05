<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AdminMenu extends Component
{
    public $menu = [];
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->menu = collect(config('adminmenu'))->map(function ($item) {
            return $this->mapMenuArray($item);
        });
    }

    private function mapMenuArray(array $item):array
    {
        $items  = [];

        foreach ($item as $arItem) {

            if (!empty($arItem['viewPolicy'])) {
                if (!\Auth::user()->can($arItem['viewPolicy'][0], $arItem['viewPolicy'][1])) {
                    continue;
                }
            }

            $arItem['text'] = !empty($arItem['text_lang']) ? __($arItem['text_lang']) : $arItem['text'];
            $arItem['link'] = empty($arItem['link']) ? '#' : route($arItem['link']);
            $arItem['active'] = !empty($arItem['active']) ? \Route::currentRouteNamed($arItem['active']) : false;

            if (!empty($arItem['child'])) {
                $arItem['child'] = $this->mapMenuArray($arItem['child']);
            }

            $items[] = $arItem;
        }

        return $items;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin-menu');
    }
}
