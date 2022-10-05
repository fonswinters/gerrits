<?php

namespace App\Buttons\Kms;

use Illuminate\Database\Eloquent\Model;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Attributes\OnOff;
use Komma\KMS\Core\Attributes\TextField;
use Komma\KMS\Core\Attributes\Title;
use Komma\KMS\Core\Sections\Section;
use Illuminate\Support\Collection;

final class ButtonSection extends Section
{

    public function defineAttributesAndTabs(Model $currentModel = null): void
    {

        $this->tabs->makeTab()->addItems([
            (new OnOff())
                ->setReference('active')
                ->setLabelText(__('KMS::global.active'))
                ->switchOn(),

            (new TextField())
                ->setReference('name')
                ->setLabelText(__('KMS::global.name')),
        ]);

        $this->tabs->makeLanguageTabTemplate()->addItems([
            (new TextField())
                ->setReference('label')
                ->setLabelText(__('KMS::global.label')),

            (new TextField())
                ->setReference('url')
                ->setLabelText(__('KMS::global.link')),
        ]);

    }

    /**
     * Generates the attributes for this section. They all must extend the App\Kms\Core\Attributes\Attribute class
     * This is the place where you need to setup your sections appearance. Just make sure you build an array of attributes
     * and put each attribute in a AbstractSectionTabItem with a SectionTabGroups constant to link them to a tab.
     *
     * @param Model $currentModel
     * @return \Illuminate\Support\Collection A collection of SectionTabItems
     * @see PageRepository::saveModel()
     */
    protected function generateFixedAttributesAddedToTabs(Model $currentModel = null): Collection
    {
        //*****************************************************************************************\\
        //*** Generate the attributes                                                           ***\\
        //*****************************************************************************************\\
        $attributes = [];

        //Build the general attributes and put them in the attributes array
        $attributes[] = (new Title(__('KMS::buttons.entity')));

        $attributes[] = (new OnOff())
            ->setLabelText(__('KMS::global.active'))
            ->switchOn()
            ->mapValueFrom(Attribute::ValueFromModel, 'active');

        $attributes[] = (new TextField(__('KMS::global.name')))
            ->mapValueFrom(Attribute::ValueFromModel, 'name');


        //Build an array with attributes for each current site language
        $languageIndexedAttributes = $this->createAttributesFromExistingAttributeForAllUsedLanguagesBySites([
            (new Title(__('KMS::global.information'))),

            (new TextField(__('KMS::global.label')))
                ->mapValueFrom(Attribute::ValueFromTranslationModel, 'label'),

            (new TextField(__('KMS::global.link')))
                ->mapValueFrom(Attribute::ValueFromTranslationModel, 'url'),
        ]);

        //Return all attributes as a collection
        return collect(array_merge($attributes, $languageIndexedAttributes));
    }
}