<?php

namespace App\References\Kms;

use App\References\Models\ReferenceTranslation;
use Komma\KMS\Core\SectionController;
use App\References\Models\Reference;

final class ReferenceController extends SectionController
{
    protected string $slug = "references";
    protected string $classModelName = Reference::class;
    protected ?string $forTranslationModelName = ReferenceTranslation::class;

    function __construct()
    {
        $section = new ReferenceSection($this->slug);
        parent::__construct($section);
    }
}