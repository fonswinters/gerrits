<?php


namespace App\WebsiteConfig;


use App\Buttons\Kms\ButtonService;
use Illuminate\Database\Eloquent\Model;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Attributes\Documents;
use Komma\KMS\Core\Attributes\Models\ImageProperty;
use Komma\KMS\Core\Attributes\MultiSelect;
use Komma\KMS\Core\Attributes\Seperator;
use Komma\KMS\Core\Attributes\TextField;
use Komma\KMS\Core\Attributes\Title;
use Komma\KMS\Core\Attributes\Video;
use Komma\KMS\Core\Sections\Section;
use App\Servicepoints\Kms\ServicepointService;
use App\Servicepoints\Models\Servicepoint;
use Komma\KMS\Users\Models\KmsUserRole;

class WebsiteConfigSection extends Section
{
    public function defineAttributesAndTabs(Model $currentModel = null): void
    {
        $buttonsService = \App::make(ButtonService::class);
        $buttonModels = $buttonsService->getOptionsForSelect();

        $servicePointService = \App::make(ServicepointService::class);
        $servicePointOptions = $servicePointService->getOptionsForSelect(false, true);

        $attributes = [];

        $this->tabs->makeTab('Bedrijfsgegevens')->addItems([

            (new TextField())
                ->setLabelText('Url')
                ->setReference('company_url'),

            (new TextField())
                ->setLabelText('Bedrijfsnaam')
                ->setReference('company_name'),

            (new TextField())
                ->setLabelText('Adres')
                ->setReference('company_address'),

            (new TextField())
                ->setLabelText('Postcode')
                ->setReference('company_zip'),

            (new TextField())
                ->setLabelText('Stad')
                ->setReference('company_city'),
            (new TextField())
                ->setLabelText('Land')
                ->setReference('company_country'),

            (new TextField())
                ->setLabelText('Bankrekening')
                ->setReference('company_bank'),

            (new TextField())
                ->setLabelText('BTW-nummer')
                ->setReference('company_vat'),

            (new TextField())
                ->setLabelText('KVK')
                ->setReference('company_kvk'),

            (new TextField())
                ->setLabelText('E-mail')
                ->setReference('company_email'),

            (new TextField())
                ->setLabelText('Telefoon link')
                ->setReference('company_phone_call'),

            (new TextField())
                ->setLabelText('Telefoon weergave')
                ->setReference('company_phone_display'),

        ]);

        $this->tabs->makeTab('Google diensten')->addItems([

            (new Seperator()),

            (new Title())
                ->setLabelText('GTM'),

            (new TextField())
                ->setPlaceholderText('Ex. GTM-WPV6L7')
                ->setReference('google_tag_manager_container')
                ->setLabelText('Container ID'),

            (new Seperator()),

            (new Title())
                ->setLabelText('Google Maps'),

            (new TextField())
                ->setPlaceholderText('Ex. 51.257929')
                ->setReference('google_maps_lat')
                ->setLabelText('Latitude'),

            (new TextField())
                ->setPlaceholderText('Ex. 5.595330')
                ->setReference('google_maps_long')
                ->setLabelText('Longitude'),
        ]);

        $this->tabs->makeTab('Social Media')->addItems([
            (new TextField())
                ->setReference('company_social_facebook')
                ->setLabelText('Facebook'),

            (new TextField())
                ->setReference('company_social_linkedin')
                ->setLabelText('Linkedin'),

            (new TextField())
                ->setReference('company_social_instagram')
                ->setLabelText('Instagram'),

            (new TextField())
                ->setReference('company_social_twitter')
                ->setLabelText('Twitter'),
        ]);

        if (auth()->user()->isAtleast(KmsUserRole::SuperAdmin)) {

            $this->tabs->makeTab('Basic')->addItems([
                (new Seperator()),

                (new Title())
                    ->setLabelText(__('KMS::global.defaultServicePoint')),

                (new TextField())
                    ->setLabelText(__('KMS::global.servicePointHeading'))
                    ->setReference('global_servicePoint_heading'),

                (new MultiSelect())
                    ->setItems($servicePointOptions->toArray())
                    ->setLabelText(__('KMS::servicepoints.entity'))
                    ->setMaxItemsToSelect(1)
                    ->canBeLinkedWith(Servicepoint::class)
                    ->setReference('global_servicePoint_id'),

                (new MultiSelect())
                    ->setItems($buttonModels->toArray())
                    ->setMaxItemsToSelect(1)
                    ->setLabelText(__('KMS::global.servicePointButton'))
                    ->setReference('global_servicePoint_button_id'),

                (new Seperator()),

                (new Title())
                    ->setLabelText(__('KMS::global.formMailAddresses')),
                (new TextField())
                    ->setPlaceholderText(config('site.mailTo')) //Shows and uses the site.mailToAdress by default
                    ->setReference('contact_form_mail')
                    ->setLabelText(__('KMS::global.contact_form')),

                (new Seperator()),

                (new Title())
                    ->setLabelText(__('KMS::global.defaultCalloutBar')),

                (new TextField())
                    ->setLabelText(__('KMS::global.calloutBarHeading'))
                    ->setReference('global_CTA_heading'),

                (new MultiSelect())
                    ->setItems($buttonModels->toArray())
                    ->setMaxItemsToSelect(1)
                    ->setLabelText(__('KMS::global.calloutBarButton'))
                    ->setReference('global_CTA_button_id'),


                (new Seperator()),

                (new Title())
                    ->setLabelText(__('KMS::global.logos')),

                (new Documents())
                    ->setLabelText(__('KMS::global.logo_on_light'))
                    ->onlyAllowImages()
                    ->setSmallDragAndDropArea()
                    ->setMaxDocuments(1)
                    ->setSubFolder('home')
                    ->setImageProperties([
                        (new ImageProperty())->setName('small')->setCropMethod(ImageProperty::Fit)->setWidth(147)->setHeight(48),
                    ])
                    ->setReference('logo_on_light'),

                (new Documents())
                    ->setLabelText(__('KMS::global.logo_on_dark'))
                    ->onlyAllowImages()
                    ->setSmallDragAndDropArea()
                    ->setMaxDocuments(1)
                    ->setSubFolder('home')
                    ->setImageProperties([
                        (new ImageProperty())->setName('small')->setCropMethod(ImageProperty::Fit)->setWidth(152)->setHeight(50),
                    ])
                    ->setReference('logo_on_dark'),


                (new Seperator()),

                (new Title())
                    ->setLabelText(__('KMS::global.homeHero')),

                (new Documents())
                    ->setLabelText(__('KMS::global.homeHeroImages'))
                    ->onlyAllowImages()
                    ->setSmallDragAndDropArea()
                    ->setMaxDocuments(5)
                    ->setSubFolder('home')
                    ->setImageProperties([
                        (new ImageProperty())->setName('large')->setCropMethod(ImageProperty::Fit)->setWidth(1152)->setHeight(640),
                        (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Fit)->setWidth(920)->setHeight(460),
                        (new ImageProperty())->setName('small')->setCropMethod(ImageProperty::Fit)->setWidth(837)->setHeight(465),
                    ])
                    ->setReference('home_hero_images'),

                (new TextField())
                    ->setLabelText(__('KMS::global.homeHeroTitle'))
                    ->setReference('home_hero_title'),

                (new Video())
                    ->setLabelText(__('KMS::global.homeHeroVideo'))
                    ->setReference('home_hero_video'),

                (new Seperator()),
            ]);


        }


    }
}