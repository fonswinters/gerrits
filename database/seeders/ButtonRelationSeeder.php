<?php
namespace Database\Seeders;

use App\Buttons\Models\Button;
use App\Pages\Models\Page;
use Illuminate\Database\Seeder;

class ButtonRelationSeeder extends Seeder
{
    public function run()
    {
        /** @var Page $page */
        //Home
        $page = Page::where('code_name', '=', 'home')->first();
        $button = Button::where('name', '=', 'Bekijk onze diensten')->first();
        $page->callToActionButton()->associate($button)->save();

        //Projects
        $page = Page::where('code_name', '=', 'projects')->first();
        $button = Button::where('name', '=', 'Ontdek onze projecten')->first();
        $page->callToActionButton()->associate($button)->save();
    }
}