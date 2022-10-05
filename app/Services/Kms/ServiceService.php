<?php
namespace App\Services\Kms;


use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Tree\NestedSets\Nodes\TreeModel;
use Komma\KMS\Core\Sections\SectionService;
use Komma\KMS\Core\Sections\SectionTabItem;
use Komma\KMS\Globalization\Languages\Models\Language;
use App\Services\Models\Service;
use Komma\KMS\Sites\HasSiteInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;

final class ServiceService extends SectionService
{
    protected $sortable = true;

    function __construct()
    {
        $this->forModelName = Service::class;

        parent::__construct();
    }

    /**
     * This method will save an model
     *
     * @param $model Model or null
     * @param Collection $sectionTabItems These must be filled with data. This is something you need to do yourself.
     *
     * @return mixed
     */
    public function saveModel(Model $model = null, Collection $sectionTabItems): Model
    {
        /** @var TreeModel $model */
        //Process Page Specific attributes

        $sectionTabItems->each(function($sectionTabItem, $key) use($model) {
            /** @var SectionTabItem $sectionTabItem */

            $attribute = $sectionTabItem->getAttribute();

            $reference = $attribute->getsValueFromReference();
            switch ($attribute->getsValueFrom())
            {
                case Attribute::ValueFromTranslationModel;
                    /** @var Language $language */
                    $this->doAfterSave(function() use($attribute, $model, $reference) {
                        $language = $attribute->getAssociatedLanguage();
                        $translation = $this->getTranslationModelForModelByLanguage($model, $language);

                    if($reference == 'name')
                    {
                        $slug = $this->createOrGetUniqueSlug($translation, $attribute->getValue() );
                        $translation['slug'] = $slug;

                    }});
                    break;
            }
        });

        if($model->exists == false ) $model->makeLastChildOf($this->getRootModelForTree());
        else $model->save(); //Save the page

        $this->siteService->linkModelToSiteWithId($model, 1);
        $this->saveModelTranslations($model);

        $model = parent::saveModel($model, $sectionTabItems); //First make sure we have a model and save the attributes in them from the SectionTabItem attributes

        //Return the page
        return $model;
    }


    /**
     * Fills non-service specific attributes. Service specific attributes are processed in the parent
     *
     * @param DatabaseCollection $sectionTabItems A collection containing implementations AbstractSectionTabItem's
     * @param Model $model
     * @return DatabaseCollection
     */
    public function fillAttributesWithData(DatabaseCollection $sectionTabItems, Model $model)
    {
        $filledAttributesCollection = parent::fillAttributesWithData($sectionTabItems, $model);

        $sectionTabItems->each(
            function ($sectionTabItem, $key) use ($model, $filledAttributesCollection, &$quantityDiscountAttribute, &$quantityPriceAttribute) {
                /** @var $sectionTabItem SectionTabItem */

                $attribute = $sectionTabItem->getAttribute();
                if (!is_a($attribute, Attribute::class)) throw new \InvalidArgumentException("One of the attributes in a AbstractSectionTabItem instance is not but must be an child instance of Attribute.");

                $valueReference = $sectionTabItem->getAttribute()->getsValueFromReference();
                switch ($valueReference) {
                    case 'site_id':
                        /** @var HasSitesInterface $model */
                        $idString = $this->siteService->getSiteIdsForModel($model);
                        if($idString) $attribute->setValue($idString);
                        break;
                }
            }
        );

        return $filledAttributesCollection;
    }
}