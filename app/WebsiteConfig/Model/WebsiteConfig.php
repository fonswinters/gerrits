<?php

namespace App\WebsiteConfig\Model;

use Komma\KMS\Core\Attributes\Models\Traits\HasThumbnailInterface;
use Komma\KMS\Core\Attributes\Models\Traits\HasThumbnailTrait;
use Illuminate\Database\Eloquent\Model;
use Komma\KMS\Documents\DocumentsTrait;
use Komma\KMS\Documents\Kms\DocumentableInterface;

class WebsiteConfig extends Model implements DocumentableInterface, HasThumbnailInterface
{
    use DocumentsTrait, HasThumbnailTrait;

    protected $table = 'website_config';

    protected $fillable = ['code_name', 'value'];
}