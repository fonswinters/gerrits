<?php

namespace App\TeamMembers\Kms;


use Illuminate\Database\Eloquent\Model;
use Komma\KMS\Core\Attributes\Documents;
use Komma\KMS\Core\Sections\Section;
use Komma\KMS\Core\Attributes\Models\ImageProperty;
use Komma\KMS\Core\Attributes\OnOff;
use Komma\KMS\Core\Attributes\TextField;


final class TeamMemberSection extends Section
{
    public function defineAttributesAndTabs(Model $currentModel = null): void
    {
        $this->tabs->makeTab()->addItems([
            (new OnOff())
                ->setLabelText(__('KMS::global.active'))
                ->switchOn()
                ->setReference( 'active'),

           (new Documents())
                ->setLabelText(__('KMS::global.images'))
                ->onlyAllowImages()
                ->setSmallDragAndDropArea()
                ->setMaxDocuments(1)
                ->setSubFolder('teamMembers')
                ->setImageProperties([
                    (new ImageProperty())->setName('thumb')->setCropMethod(ImageProperty::Fit)->setWidth(100)->setHeight(100),
                    (new ImageProperty())->setName('small')->setCropMethod(ImageProperty::Fit)->setWidth(200)->setHeight(200),
                    (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Fit)->setWidth(300)->setHeight(300),
                ])
               ->setReference( 'teamMembers'),

            (new TextField())
                ->setLabelText(__('KMS::global.name'))
                ->setReference('name'),

            (new TextField())
                ->setLabelText(__('KMS::global.email'))
                ->setReference('email'),

            (new TextField())
                ->setLabelText('LinkedIn url')
                ->setReference('linkedinurl'),
        ]);

        //Build an array with attributes for each current site language
        $this->tabs->makeLanguageTabTemplate()->addItems([
            (new TextField())
                ->setLabelText(__('KMS::global.subtitle'))
                ->setReference( 'function'),
        ]);
    }
}