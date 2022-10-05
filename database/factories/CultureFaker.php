<?php
namespace Database\Factories;

use Komma\KMS\Globalization\RegionInfo;
use Faker\Provider\Base;


class CultureFaker extends Base
{
    public static function culture():RegionInfo
    {
        return RegionInfo::getSpecificCultures()->random(1)->first();
    }
}