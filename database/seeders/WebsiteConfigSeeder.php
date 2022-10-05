<?php
namespace Database\Seeders;

use App\WebsiteConfig\Model\WebsiteConfig;
use App\WebsiteConfig\WebsiteConfigModelService;
use Illuminate\Database\Seeder;
use App\Servicepoints\Models\Servicepoint;
use Faker\Generator;
use Illuminate\Support\Str;


class WebsiteConfigSeeder extends Seeder
{
    private Generator $fakerNL;

    /**
     * Run the seed
     */
    public function run()
    {
        $servicePoint = Servicepoint::first();

        $this->fakerNL = \Faker\Factory::create('nl_NL');

        $companyName = $this->fakerNL->company;
        $companyUrl = 'http://'.Str::slug($companyName).'.com';
        $companyStreet = $this->fakerNL->streetName;
        $companyZip = $this->fakerNL->postcode;
        $companyCity = $this->fakerNL->city;
        $companyEmail = 'info@'.Str::slug($companyName).'.com';

        (new WebsiteConfig(['code_name' => 'company_url',                  'value' => $companyUrl                                                       ]))->save();
        (new WebsiteConfig(['code_name' => 'company_name',                 'value' => $companyName                                                      ]))->save();
        (new WebsiteConfig(['code_name' => 'company_address',              'value' => $companyStreet                                                    ]))->save();
        (new WebsiteConfig(['code_name' => 'company_zip',                  'value' => $companyZip                                                       ]))->save();
        (new WebsiteConfig(['code_name' => 'company_city',                 'value' => $companyCity                                                      ]))->save();
        (new WebsiteConfig(['code_name' => 'company_country',              'value' => 'Nederland'                                                       ]))->save();
        (new WebsiteConfig(['code_name' => 'company_bank',                 'value' => 'NL 00 ABCD 0123 45678 90'                                        ]))->save();
        (new WebsiteConfig(['code_name' => 'company_kvk',                  'value' => '22446688'                                                        ]))->save();
        (new WebsiteConfig(['code_name' => 'company_vat',                  'value' => 'NL.123456789.B01'                                                ]))->save();
        (new WebsiteConfig(['code_name' => 'company_email',                'value' => $companyEmail                                                     ]))->save();
        (new WebsiteConfig(['code_name' => 'company_phone_call',           'value' => '+00000000000'                                                    ]))->save();
        (new WebsiteConfig(['code_name' => 'company_phone_display',        'value' => '+00 (0)000 00 00 00'                                             ]))->save();
        (new WebsiteConfig(['code_name' => 'company_social_facebook',      'value' => ''                                                                ]))->save();
        (new WebsiteConfig(['code_name' => 'company_social_linkedin',      'value' => ''                                                                ]))->save();
        (new WebsiteConfig(['code_name' => 'company_social_instagram',     'value' => ''                                                                ]))->save();
        (new WebsiteConfig(['code_name' => 'company_social_twitter',       'value' => ''                                                                ]))->save();
        (new WebsiteConfig(['code_name' => 'home_hero_images',             'value' => ''                                                                ]))->save();
        (new WebsiteConfig(['code_name' => 'home_hero_title',              'value' => 'Hier kan een mooie heading komen'                                ]))->save();
        (new WebsiteConfig(['code_name' => 'home_hero_video',              'value' => '0,'                                                              ]))->save();
        (new WebsiteConfig(['code_name' => 'global_servicePoint_heading',  'value' => 'Heeft u nog vragen? <br> Ik wil u graag helpen.'                 ]))->save();
        (new WebsiteConfig(['code_name' => 'global_servicePoint_id',       'value' => $servicePoint->id                                                 ]))->save();
        (new WebsiteConfig(['code_name' => 'global_servicePoint_button_id','value' => '2'                                                               ]))->save();
        (new WebsiteConfig(['code_name' => 'global_CTA_heading',           'value' => 'Nieuwsgierig? <br> Neem contact met ons op!'                     ]))->save();
        (new WebsiteConfig(['code_name' => 'global_CTA_button_id',         'value' => '1'                                                               ]))->save();
        (new WebsiteConfig(['code_name' => 'logo_on_light',                'value' => ''                                                                ]))->save();
        (new WebsiteConfig(['code_name' => 'logo_on_dark',                 'value' => ''                                                                ]))->save();
        (new WebsiteConfig(['code_name' => 'logo_on_dark',                 'value' => ''                                                                ]))->save();
        (new WebsiteConfig(['code_name' => 'contact_form_mail',            'value' => ''                                                                ]))->save();

        WebsiteConfigModelService::clearCache();
        WebsiteConfigModelService::getFromCache();
    }
}










