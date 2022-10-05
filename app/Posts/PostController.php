<?php

namespace App\Posts;

use App\Base\Controller;
use App\Buttons\Models\Button;
use App\Pages\Models\Page;
use App\Posts\Models\Post;
use Komma\KMS\Components\ComponentService;

final class PostController extends Controller
{
    private $postService;
    private $postPaginationKey = 'postPagination';

    public function __construct(PostService $postService)
    {
        parent::__construct();
        $this->postService = $postService;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // If you don't know the code_name get page through given page id through request
//        $page = $this->pageService->getPage(request()->get('page_id'));

        $page = $this->links->posts->node;

        $posts = $this->postService->getPostsPaginated();
        $posts->withPath($this->links->posts->route);

        // split the Hero images from the "normal" page images
        list($documents, $heroDocuments) = $page->translation->documents->partition(function ($document) {
            return $document->key == 'Documents-pages';
        });
        $page->documents = $documents;

        // fill the page hero object
        $heroButton = Button::where('id', $page->translation->hero_button_id)->with('translation')->first();
        $page->hero = (object)[
            'documents' => $heroDocuments,
            'active' => $page->translation->hero_active,
            'title' => $page->translation->hero_title,
            'description' => $page->translation->hero_description,
            'buttons' => !empty($heroButton) ? $heroButton : null,
        ];

        /** @var ComponentService $componentService */
        $componentService = app(ComponentService::class);
        $components = $componentService->getViewComponents($page->translation);

        // Make language menu for given page
        $languageMenu = $this->pageService->makeLanguageSwitchForPage($this->links->{$page->code_name}, $this->links->home);

        $this->keepTrackOfPagination($this->postPaginationKey);

        $this->pageService->setSharableVariables($page->servicepoint_id, $page->servicepoint_button_id, $page->servicepoint_heading);

        //Get the "discover more" page code names.
        $discover_more_page_codenames = $page->discoverPages()->get()->map(function(Page $page) {
            return $page->code_name;
        })->toArray();

        // Return view
        return view('templates.posts_index',[
            'page' => $page,
            'components' => $components,
            'posts' => $posts,
            'links' => $this->links,
            'languageMenu' => $languageMenu,
            'discover_page_codenames' => $discover_more_page_codenames,
        ]);
    }

    /**
     * @param Post $post
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Post $post)
    {
        $post->load('translation','translations');
        $latestPosts = $this->postService->getLatestPost(10)->get();

        $page = $this->links->posts->node;
        // Make language menu for given page
        $languageMenu = $this->pageService->makeLanguageSwitchForPage($this->links->{$page->code_name}, $this->links->home);
        $this->pageService->extendLanguageMenuWithResource($languageMenu, $post);

        $componentService = app(ComponentService::class);
        $components = $componentService->getViewComponents($post->translation);

        // Create previous route for better navigation UX
        $previousRoute = $this->createPreviousRoute($this->postPaginationKey, $this->links->posts->route);

        // split the Hero images from the "normal" post images
        list($documents, $heroDocuments) = $post->documents->partition(function ($document) {
            return $document->key == 'Documents-posts';
        });
        $post->documents = $documents;

        // fill the page hero object
        $heroButton = Button::find($post->translation->hero_button_id);
        $post->hero = (object)[
            'documents' => $heroDocuments,
            'active' => $post->translation->hero_active,
            'title' => $post->translation->hero_title,
            'description' => $post->translation->hero_description,
            'buttons' => !empty($heroButton) ? $heroButton : null,
        ];

        $this->pageService->setSharableVariables($page->servicepoint_id, $page->servicepoint_button_id, $page->servicepoint_heading);

        // Return view
        return view('templates.posts_show',[
            'page' => $page,
            'post' => $post,
            'latestPosts' => $latestPosts,
            'components' => $components,
            'links' => $this->links,
            'previousRoute' => $previousRoute,
            'languageMenu' => $languageMenu,
        ]);
    }

}