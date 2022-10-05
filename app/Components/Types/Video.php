<?php declare(strict_types=1);


namespace App\Components\Types;


use App\Components\ComponentTypes;
use Komma\KMS\Components\ComponentType\AbstractComponentType;
use Komma\KMS\Core\Attributes\Video as VideoAttribute;

class Video extends AbstractComponentType
{
    //Set an id that corresponds to the ComponentTypes enum
    protected int $id = ComponentTypes::VIDEO;

    //Set icon and name
    protected string $name = 'video';
    public function defineAttributesAndTabs()
    {
        $this->addItems([
            (new VideoAttribute())
                ->setReference('video_video')
        ]);
    }
}