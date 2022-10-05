<?php


namespace App\Composers;


use App\Pages\Models\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

class NavItemComposer
{
    /** @var Collection */
    static $navItems;

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        if(!self::$navItems) self::$navItems = Page::where('active', '=', 1)->where('inNav', '=', 1)->with('translations')->orderBy('lft')->get();
        $view->with('navItems', self::$navItems);
    }
}