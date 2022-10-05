<?php
namespace App\WebsiteConfig;

use App\Helpers\KommaHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;
use Illuminate\View\View;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Attributes\Models\Traits\HasThumbnailInterface;
use Komma\KMS\Core\Entities\DisplayNameInterface;
use Komma\KMS\Core\SectionController;
use App\WebsiteConfig\Model\WebsiteConfig;

class WebsiteConfigController extends SectionController
{
    protected string $slug = "websiteconfig";
    protected string $classModelName = WebsiteConfig::class;


    /**
     * Constructor
     * @param $section
     */
    public function __construct()
    {
        $section = new WebsiteConfigSection($this->slug);
        parent::__construct($section);
        $this->modelService = new WebsiteConfigModelService();
    }

    /**
     * Returns a rendered view
     *
     * @param Model $model
     * @return View
     */
    protected function render(Model $model = null)
    {
        $this->section->defineAttributesAndTabs($this->forModelInstance);
        $byValueFrom = $this->section->getAttributes()->mapToGroups(function($attribute, $index) {
            /** @var Attribute $attribute */
            return [$attribute->getsValueFrom() => $attribute];
        });
        $this->load($model ?? new WebsiteConfig(), $byValueFrom);
        return $this->makeView();
    }

    /**
     * @param Model $model
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function show($model)
    {
        return Redirect::action('\\'.get_class($this) . '@index');
    }


    /**
     * Makes the view and returns it
     *
     * @return \Illuminate\View\View
     */
    protected function makeView(): View
    {
        $modelId = ($this->forModelInstance) ? $this->forModelInstance->id : null;
        $successes = (Session::has('successes')) ? Session::get('successes') : new MessageBag();

        $saveRoute = $this->modelService->getSaveRoute($this->slug, $modelId);

        $siteSlug = !$this->siteService->getCurrentSite()->exists ? null : $this->siteService->getCurrentSite()->slug;
        return \View::make('KMS::section.fullwidth', [
            'sectionTabs'                  => $this->section->getTabs(),
            'slug'                         => $this->slug,
            'siteSlug'                     => $siteSlug,
            'successes'                    => $successes,
            'maxUploadSize'                => \Komma\KMS\Helpers\KommaHelpers::fileUploadMaxSize(),
            'maxPostSize'                  => KommaHelpers::maxPostSize(),
            'modelClassName'               => $this->classModelName,
            'saveRoute'                    => $saveRoute,
            'showEntity'                   => true,
            'thumbnail'                    => 'WC',
            'displayName'                  => $this->section->getSectionTitle(),
            'currentModel'                 => $this->forModelInstance,
            'submitButtonLabel'            => $this->section->getSubmitButtonLabel(),
            'preventNavigationTranslation' => json_encode(__('KMS::prevent-navigation'))
        ]);
    }

    /**
     * A function that MUST delegate to services to accomplish loading.
     * It MUST NOT act like a service.
     *
     * @param Model $model
     * @param Collection $attributesByValueFrom Keys must be ValueFrom integers. Values must be Attributes that have the ValueFrom integers
     * @return Collection The same collection as you've passed in. Only "filled".
     */
    protected function load(Model $model, Collection $attributesByValueFrom = null): Collection
    {
        if($attributesByValueFrom === null) return new Collection();

        /** @var Attribute $attribute */
        $this->modelService->load($model, $attributesByValueFrom->collapse());
        return $attributesByValueFrom;
    }

    protected function save(Model $model, Collection $attributesByValueFrom = null): Model
    {
        if($attributesByValueFrom == null) return $model;

        /** @var Attribute $attribute */
        $model = $this->modelService->save($model, $attributesByValueFrom->collapse());
        //Refresh the cache so that it directly is available on the site side
        WebsiteConfigModelService::clearCache();
        WebsiteConfigModelService::getFromCache();
        return $model;
    }

    public function store()
    {
        //Prepare the new model, and attributes
        $model = $this->modelService->newModel();
        if($this->forTranslationModelName) $model = $this->translationService->makeAndInjectEmptyTranslationsIntoTranslatableIfNeeded($model);
        $this->section->defineAttributesAndTabs($model);
        $attributes = $this->section->getAttributes();

        //Validate the form data and fill the attributes from input
        $validator = $this->dataService->validateInputAndReturnValidator($attributes);
        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);
        $attributes = $this->dataService->fillAttributesFromInput($attributes);

        $byValueFrom = $attributes->mapToGroups(function($attribute, $index) {
            /** @var Attribute $attribute */
            return [$attribute->getsValueFrom() => $attribute];
        });

        $model = $this->save($model, $byValueFrom);

        $sessionData = [
            'tabslug' => request('tabslug'),
            'success' => __('KMS::global.saved')
        ];
        return redirect()->action('\\'.get_class($this) . '@index')->with($sessionData);
    }

    public function destroy(Model $model)
    {
        $result = parent::destroy($model);
        //Refresh the cache so that it directly is available on the site side
        WebsiteConfigModelService::clearCache();
        WebsiteConfigModelService::getFromCache();
        return $result;
    }
}