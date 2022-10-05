<?php


namespace App\Composers;


use Illuminate\View\View;

class ButtonComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $viewData = $view->getData();

        $type = isset($viewData['type']) ? $viewData['type'] : '';

        $view->with('tagName', isset($viewData['isButton']) ? 'button' : 'a');
        $view->with('buttonLink', isset($viewData['buttonLink']) ? $viewData['buttonLink'] : '#0');
        $view->with('type', $type.'button');
    }
}