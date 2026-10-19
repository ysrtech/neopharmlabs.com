<?php

use App\Http\Controllers\Site\BlogController;
use App\Http\Controllers\Site\CareersController;
use App\Http\Controllers\Site\FormController;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Site\ResourceController;
use App\Http\Controllers\Site\SearchController;
use App\Http\Controllers\Site\ServiceController;
use App\Http\Controllers\Site\SitemapController;
use App\Http\Controllers\Site\SubmissionAttachmentController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::get('sitemap.xml', SitemapController::class)->name('sitemap');

Route::post('forms/{form}', [FormController::class, 'store'])
    ->whereIn('form', array_keys(\App\Models\Submission::FORMS))
    ->middleware('throttle:10,1')
    ->name('forms.store');

Route::get('admin-files/submissions/{submission}/{key}', SubmissionAttachmentController::class)
    ->middleware(['twill_auth:twill_users'])
    ->name('submissions.attachment');

// French routes are registered first so /fr is never caught by the English page route.
foreach (array_reverse(config('site.locales'), true) as $locale => $prefix) {
    $s = fn (string $key) => config("site.segments.$key.$locale");

    Route::prefix($prefix)
        ->middleware(SetLocale::class . ':' . $locale)
        ->name($locale . '.')
        ->group(function () use ($s) {
            Route::get('/', [PageController::class, 'home'])->name('home');

            Route::get($s('services'), [ServiceController::class, 'index'])->name('services.index');
            Route::get($s('services') . '/{slug}', [ServiceController::class, 'show'])->name('services.show');

            Route::get($s('blog') . '/p{page}', [BlogController::class, 'index'])->whereNumber('page')->name('blog.page');
            Route::get($s('blog'), [BlogController::class, 'index'])->name('blog.index');
            Route::get($s('blog_category') . '/{slug}/p{page}', [BlogController::class, 'category'])->whereNumber('page')->name('blog.category.page');
            Route::get($s('blog_category') . '/{slug}', [BlogController::class, 'category'])->name('blog.category');
            Route::get($s('blog') . '/{slug}', [BlogController::class, 'show'])->name('blog.show');

            Route::get($s('resources'), [ResourceController::class, 'index'])->name('resources.index');
            Route::get($s('resources') . '/{type}', [ResourceController::class, 'index'])
                ->whereIn('type', array_keys(\App\Models\Resource::TYPES))->name('resources.type');
            Route::get($s('resources') . '/{slug}', [ResourceController::class, 'show'])->name('resources.show');

            Route::get($s('team') . '/{slug}', [PageController::class, 'teamMember'])->name('team.show');

            Route::get($s('jobs'), [CareersController::class, 'index'])->name('jobs.index');
            Route::get($s('jobs') . '/{reference}', [CareersController::class, 'show'])->name('jobs.show');
            Route::get($s('apply') . '/{reference}', [CareersController::class, 'apply'])->name('jobs.apply');
            Route::get($s('spontaneous'), [CareersController::class, 'spontaneous'])->name('jobs.spontaneous');

            Route::get($s('search'), SearchController::class)->name('search');

            Route::get('{slug}', [PageController::class, 'show'])
                ->where('slug', '^(?!' . implode('|', [config('twill.admin_app_path'), config('twill.glide.base_path', 'img'), 'storage', 'up']) . ')[^/]+$')
                ->name('pages.show');
        });
}
