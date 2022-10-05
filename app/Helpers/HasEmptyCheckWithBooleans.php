<?php declare(strict_types=1);


namespace App\Helpers;

use Komma\KMS\Core\AbstractTranslationModel;

/**
 * Extended version of the is empty check on AbstractTranslationModels
 * Trait IsEmptyWithHeroActiveTrait
 *
 * @package App\Helpers
 * @mixin AbstractTranslationModel
 */
trait HasEmptyCheckWithBooleans
{
    public function isEmpty(): bool
    {
        $empty = true;

        foreach($this->attributes as $attributeName => $value)
        {
            if(substr($attributeName, -3) == '_id' || in_array($attributeName, $this->booleanAttributes ?? [])) continue;
            if($value != "" && $value != "[]") {
                $empty = false;
                break;
            }
        }

        return $empty;
    }
}