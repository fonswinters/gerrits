<?php declare(strict_types=1);


namespace App\Site;


use App\Pages\Models\Page;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Komma\KMS\Sites\Models\Site as KmsSite;

class Site extends KmsSite
{
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }
}