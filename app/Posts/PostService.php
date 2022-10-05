<?php


namespace App\Posts;


use App\Base\Service;
use App\Posts\Models\Post;
use Carbon\Carbon;

final class PostService extends Service
{

    private $today;

    public function __construct()
    {
        $this->today = now()->endOfDay();
        $this->today = $this->today->format('Y-m-d H:i:s');
        parent::__construct();
    }

    /**
     * Base query for get post from DB
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    private function basePostQuery()
    {
        return Post::with('translation', 'images')
            ->where('active', 1)
            ->where('date', '<=', $this->today)
            ->orderBy('date','desc')
            ->orderBy('created_at', 'desc')
            ->whereHas('translation');
    }

    /**
     * Get all posts
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPosts()
    {
        return $this->basePostQuery()->get();
    }

    /**
     * Get $amount of latest posts
     *
     * @param  int  $amount
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getLatestPost($amount = 3)
    {
        return $this->basePostQuery()
            ->take($amount);
    }

    /**
     * Get posts paginated per $amount on a page
     *
     * @param  int  $amountPerPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPostsPaginated($amountPerPage = 8)
    {
        return $this->basePostQuery()->paginate($amountPerPage);
    }

    /**
     * Get the next posts after give $post
     *
     * @param  Post  $post
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getNextPosts(Post $post)
    {
        return $this->basePostQuery()
            ->where('posts.id', '!=', $post->id)
            ->where('date', '<=', $post->date->format(Carbon::DEFAULT_TO_STRING_FORMAT))
            ->take(2)
            ->get();
    }

}