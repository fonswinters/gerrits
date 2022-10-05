<?php

namespace App\Events\Kms;


use App\Events\Models\Event;
use App\Events\Models\EventTranslation;
use Komma\KMS\Core\SectionController;

final class EventController extends SectionController
{
    protected string $slug = "events";
    protected string $classModelName = Event::class;
    protected ?string $forTranslationModelName = EventTranslation::class;

    function __construct()
    {
        $eventSection = new EventSection($this->slug);
        parent::__construct($eventSection);
    }
}