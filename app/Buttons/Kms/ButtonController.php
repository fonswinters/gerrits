<?php

namespace App\Buttons\Kms;

use App\Buttons\Models\ButtonTranslation;
use Komma\KMS\Core\SectionController;
use App\Buttons\Models\Button;

final class ButtonController extends SectionController
{
    protected string $slug = "buttons";
    protected string $classModelName = Button::class;
    protected ?string $forTranslationModelName = ButtonTranslation::class;

    function __construct()
    {
        $section = new ButtonSection($this->slug);
        parent::__construct($section);
    }
}