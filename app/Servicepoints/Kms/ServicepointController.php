<?php

namespace App\Servicepoints\Kms;


use App\Servicepoints\Models\ServicepointTranslation;
use Komma\KMS\Core\SectionController;
use App\Servicepoints\Models\Servicepoint;

final class ServicepointController extends SectionController
{
    protected string $slug = "servicepoints";
    protected string $classModelName = Servicepoint::class;
    protected ?string $forTranslationModelName = ServicepointTranslation::class;

    function __construct()
    {
        $section = new ServicepointSection($this->slug);
        parent::__construct($section);
    }
}