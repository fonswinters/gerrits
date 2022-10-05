<?php

namespace App\Posts\Kms;


use App\Posts\Models\Post;
use App\Posts\Models\PostTranslation;
use Komma\KMS\Core\SectionController;

final class PostController extends SectionController
{
    protected string $slug = "posts";
    protected string $classModelName = Post::class;
    protected ?string $forTranslationModelName = PostTranslation::class;

    function __construct()
    {
        $postSection = new PostSection($this->slug);
        parent::__construct($postSection);
    }
}