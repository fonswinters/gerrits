<?php
namespace Database\Seeders;

use App\Buttons\Models\Button;
use App\Pages\Models\Page;
use Illuminate\Database\Seeder;

class DiscoverMorePageRelationSeeder extends Seeder
{
    public function run()
    {
        /** @var Page $page */
        $page = Page::where('code_name', '=', 'home')->first();
        $discoverPages = Page::whereIn('code_name', ['about', 'services', 'contact'])->get();
        $page->discoverPages()->saveMany($discoverPages);
    }
}