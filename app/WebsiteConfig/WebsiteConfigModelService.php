<?php
namespace App\WebsiteConfig;


use App\Helpers\KommaHelpers;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Komma\KMS\Core\ModelService;
use Komma\KMS\Documents\Kms\DocumentableInterface;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Attributes\Documents;
use Komma\KMS\Core\Attributes\Seperator;
use Komma\KMS\Core\Attributes\Title;
use App\WebsiteConfig\Model\WebsiteConfig;
use Illuminate\Database\Eloquent\Model;
use Komma\KMS\Documents\Kms\DocumentService;
use Komma\KMS\Documents\Resources\Document;

final class WebsiteConfigModelService extends ModelService
{
    public const CacheKey = 'websiteConfig';

    protected $sortable = false;

    /** @var DocumentService $documentService */
    private $documentService;

    function __construct()
    {
        $this->modelClassName = WebsiteConfig::class;
        $this->documentService = new DocumentService();

        parent::__construct();
    }


    /**
     * Puts the values of attributes in an Eloquent model. And then saves that model.
     *
     * @param Model $model
     * @param Collection $attributes
     * @return Model
     */
    public function save(Model $model, Collection $attributes = null): Model
    {
        if($attributes === null) return $model;

        $attributes->each(function(Attribute $attribute) use(&$model) {
            if(
                !is_a($attribute, Title::class) &&
                !is_a($attribute, Seperator::class) &&
                !is_a($attribute, Documents::class)
            ) {
                $model = WebsiteConfig::updateOrCreate(['code_name' => $attribute->getsValueFromReference()],
                    ['value' => $attribute->getValue()]);

            } elseif (is_a($attribute, Documents::class)) {
                $model = WebsiteConfig::firstOrCreate(['code_name' => $attribute->getsValueFromReference()]);
                /** @var Documents $attribute */
                $this->documentService->processUploadedDocumentsForModel($model, $attribute);
            }
        });

        return $model;
    }


    /**
     * Gets the values of an Eloquent model and passes them to a collection of attributes
     *
     * @param Model $model
     * @param \Illuminate\Support\Collection $attributes
     * @return mixed
     */
    public function load(Model $model, Collection $attributes = null): Collection
    {
        $attributes->each(function(Attribute $attribute) use(&$model) {
            if(is_a($attribute, Documents::class)) {
                $key = KommaHelpers::getShortNameFromClass($attribute) . '-' . $attribute->getKey()->getValuePart();
                /** @var $model DocumentableInterface */
                $websiteConfigModel = WebsiteConfig::where('code_name', '=', $attribute->getsValueFromReference())->first();
                if($websiteConfigModel) {
                    $value = json_encode(Document::collection($websiteConfigModel->documents()->where('key', '=', $key)->get()));
                    $attribute->setValue($value);
                }
            } else {
                $websiteConfigModel = WebsiteConfig::where('code_name', '=', $attribute->getsValueFromReference())->first();
                if ($websiteConfigModel) {
                    $attribute->setValue($websiteConfigModel->value);
                }
            }
        });

        return $attributes;
    }

    /**
     * Gets the config from the cache.
     * If it isn't cached, it makes sure it will be.
     *
     * @return DatabaseCollection a Collection of WebsiteConfig models
     */
    public static function getFromCache(): DatabaseCollection
    {
        return Cache::rememberForever(self::CacheKey, function () {
            return WebsiteConfig::all();
        });
    }

    /**
     * Clear the website config cache
     */
    public static function clearCache(): void {
        Cache::forget(self::CacheKey);
    }
}