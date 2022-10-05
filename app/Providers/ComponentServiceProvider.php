<?php

namespace App\Providers;

use App\Components\ComponentTypes;
use App\Components\Types\ContentPersonal;
use App\Components\Types\ContentSlider;
use App\Components\Types\DoubleImage;
use App\Components\Types\DoubleText;
use App\Components\Types\DoubleUSP;
use App\Components\Types\Downloads;
use App\Components\Types\FeaturedVacancies;
use App\Components\Types\Image;
use App\Components\Types\Quote;
use App\Components\Types\Text;
use App\Components\Types\TextImage;
use App\Components\Types\TextImageButton;
use App\Components\Types\USP;
use App\Components\Types\VacancyProcessPersonal;
use App\Components\Types\Video;
use Illuminate\Support\ServiceProvider;
use Komma\KMS\Components\ComponentType\ComponentTypeFactory;
use Komma\KMS\Components\ComponentType\ComponentTypeFactory as KmsComponentTypeFactory;

class ComponentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        KmsComponentTypeFactory::registerType(ComponentTypes::TEXT, Text::class);
        KmsComponentTypeFactory::registerType(ComponentTypes::TEXT_IMAGE, TextImage::class);
        KmsComponentTypeFactory::registerType(ComponentTypes::DOUBLE_TEXT, DoubleText::class);
        KmsComponentTypeFactory::registerType(ComponentTypes::DOUBLE_IMAGE, DoubleImage::class);
        KmsComponentTypeFactory::registerType(ComponentTypes::VIDEO, Video::class);
        KmsComponentTypeFactory::registerType(ComponentTypes::IMAGE, Image::class);
        KmsComponentTypeFactory::registerType(ComponentTypes::QUOTE, Quote::class);


        ComponentTypeFactory::registerType(ComponentTypes::TEXT, Text::class);
        ComponentTypeFactory::registerType(ComponentTypes::DOUBLE_TEXT, DoubleText::class);
        ComponentTypeFactory::registerType(ComponentTypes::TEXT_IMAGE_BUTTON, TextImageButton::class);
        ComponentTypeFactory::registerType(ComponentTypes::CONTENT_PERSONAL, ContentPersonal::class);
        ComponentTypeFactory::registerType(ComponentTypes::CONTENT_SLIDER, ContentSlider::class);
        ComponentTypeFactory::registerType(ComponentTypes::USP, USP::class);
        ComponentTypeFactory::registerType(ComponentTypes::DOWNLOADS, Downloads::class);
        ComponentTypeFactory::registerType(ComponentTypes::FEATURED_VACANCIES, FeaturedVacancies::class);
        ComponentTypeFactory::registerType(ComponentTypes::DOUBLE_USP, DoubleUSP::class);
        ComponentTypeFactory::registerType(ComponentTypes::VACANCY_PROCESS_PERSONAL, VacancyProcessPersonal::class);
    }
}
