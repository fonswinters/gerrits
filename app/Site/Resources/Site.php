<?php declare(strict_types=1);


namespace App\Site\Resources;


use App\Posts\Resources\Post;
use App\Products\Product\ProductResource;
use App\Products\ProductComposite\ProductCompositeResource;
use App\Products\ProductGroup\ProductGroupResource;

class Site extends \Komma\KMS\Sites\Resources\Site
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request) {
        $data = parent::toArray($request);
        $data['posts'] = Post::collection($this->whenLoaded('posts'));
        $data['products'] = ProductResource::collection($this->whenLoaded('products'));
        $data['product_groups'] = ProductGroupResource::collection($this->whenLoaded('product_groups'));
        $data['product_composites'] = ProductCompositeResource::collection($this->whenLoaded('product_composites'));

        return $data;
    }
}