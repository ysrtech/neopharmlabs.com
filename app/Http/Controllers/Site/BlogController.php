<?php

namespace App\Http\Controllers\Site;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogController extends Controller
{
    public function index(int $page = 1)
    {
        return $this->listing(null, $page);
    }

    public function category(string $slug, int $page = 1)
    {
        $category = $this->findBySlug(PostCategory::class, $slug);

        return $this->listing($category, $page);
    }

    protected function listing(?PostCategory $category, int $page)
    {
        $query = Post::query()->published()->where('legacy', false)
            ->when($category, fn ($q) => $q->where('post_category_id', $category->id))
            ->whereHas('translations', fn ($q) => $q->where('locale', app()->getLocale())->where('active', true))
            ->with(['category', 'medias'])
            ->orderByDesc('published_at');

        $posts = $query->paginate(config('site.blog_per_page'), ['*'], 'page', $page);
        abort_if($page > 1 && $posts->isEmpty(), 404);

        $categories = PostCategory::query()->published()->orderBy('position')->get();
        $pageUrl = fn (int $p) => $category
            ? ($p > 1 ? lroute('blog.category.page', ['slug' => $category->getSlug(), 'page' => $p]) : lroute('blog.category', ['slug' => $category->getSlug()]))
            : ($p > 1 ? lroute('blog.page', ['page' => $p]) : lroute('blog.index'));

        return view('site.blog.index', [
            'posts' => $posts,
            'category' => $category,
            'categories' => $categories,
            'pageUrl' => $pageUrl,
            'seo' => [
                'title' => ($category ? $category->title . ' | ' : '') . __('site.blog.title'),
                'description' => null,
            ],
            'alternates' => $category ? $this->alternates($category, 'blog.category') : $this->staticAlternates('blog.index'),
        ]);
    }

    public function show(string $slug)
    {
        $post = $this->findBySlug(Post::class, $slug);
        if ($post->getSlug() !== $slug) {
            return redirect(lroute('blog.show', ['slug' => $post->getSlug()]), 301);
        }

        $related = Post::query()->published()->where('legacy', false)->whereKeyNot($post->id)
            ->when($post->post_category_id, fn ($q) => $q->where('post_category_id', $post->post_category_id))
            ->whereHas('translations', fn ($q) => $q->where('locale', app()->getLocale())->where('active', true))
            ->orderByDesc('published_at')->take(3)->get();

        return view('site.blog.show', [
            'post' => $post,
            'related' => $related,
            'seo' => $this->seo($post, $post->title, $post->excerpt),
            'alternates' => $this->alternates($post, 'blog.show'),
            'bodyClass' => 'bg-white',
        ]);
    }
}
