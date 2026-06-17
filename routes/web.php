<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HeroSectionController;
use App\Http\Controllers\PageSectionController;
use App\Http\Controllers\PageDashboardController;
use App\Http\Controllers\GalleryCategoryController;
use App\Http\Controllers\GalleryImageController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\ExperienceActivityController;
use App\Http\Controllers\ActivityPackageCategoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomGalleryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Api\v1\CustomerMessageController;



Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

// Public blog post view
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blogs.show');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/homepage', [HeroSectionController::class, 'index'])->name('pages.homepage.index');
    Route::post('/api/translate-to-nepali', [PageSectionController::class, 'translateToNepali'])
        ->name('pages.section.translate');

    Route::prefix('pages/{page:slug}')->group(function () {
        Route::get('/', [PageDashboardController::class, 'index'])->name('pages.dashboard');
        Route::post('/update-order', [PageDashboardController::class, 'updateOrder'])->name('pages.updateOrder');
        Route::get('/hero-section', [HeroSectionController::class, 'getHeroSection'])
            ->name('pages.herosection');
        Route::post('/hero-section', [HeroSectionController::class, 'store'])
            ->name('pages.herosection.store');
        Route::get('/hero-section/{hero}/edit', [HeroSectionController::class, 'edit'])
            ->name('pages.herosection.edit');
        Route::put('/hero-section/{hero}', [HeroSectionController::class, 'update'])
            ->name('pages.herosection.update');

        Route::get('/section/{sectionType}', [PageSectionController::class, 'getSection'])
            ->name('pages.section');
        Route::post('/section/{sectionType}', [PageSectionController::class, 'storeSection'])
            ->name('pages.section.store');
        Route::get('/section/{section:slug}/edit', [PageSectionController::class, 'editSection'])
            ->name('pages.section.edit');
        Route::put('/section/{section:slug}', [PageSectionController::class, 'updateSection'])
            ->name('pages.section.update');
    });


    Route::get('/homepage/section/{sectionType}', function ($sectionType) {
        return redirect()->route('pages.section', ['home', $sectionType]);
    })->name('pages.homepage.section');

    Route::resource('rooms', RoomController::class);
    Route::resource('roomgallery', RoomGalleryController::class);


    // Blog / Content routes
    Route::prefix('blogs')->name('blogs.')->group(function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/create', [BlogController::class, 'create'])->name('create');
        Route::post('/', [BlogController::class, 'store'])->name('store');
        Route::get('/{blog}/edit', [BlogController::class, 'edit'])->name('edit');
        Route::put('/{blog}', [BlogController::class, 'update'])->name('update');
        Route::delete('/{blog}', [BlogController::class, 'destroy'])->name('destroy');
        Route::patch('/{blog}/toggle-featured', [BlogController::class, 'toggleFeatured'])->name('toggle-featured');
    });

    Route::get('gallery-categories', [GalleryCategoryController::class, 'index'])->name('gallery-categories.index');
    Route::post('gallery-categories', [GalleryCategoryController::class, 'store'])->name('gallery-categories.store');
    Route::get('gallery-categories/{id}', [GalleryCategoryController::class, 'show'])->name('gallery-categories.show');
    Route::put('gallery-categories/{category}', [GalleryCategoryController::class, 'update'])->name('gallery-categories.update');
    Route::delete('gallery-categories/{category}', [GalleryCategoryController::class, 'destroy'])->name('gallery-categories.destroy');
    Route::post('gallery-images', [GalleryImageController::class, 'store'])->name('gallery-images.store');
    Route::put('gallery-images/{galleryImage}', [GalleryImageController::class, 'update'])->name('gallery-images.update');
    Route::delete('gallery-images/{galleryImage}', [GalleryImageController::class, 'destroy'])->name('gallery-images.destroy');

    Route::resource('testimonials', TestimonialController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('abouts', AboutController::class);

    Route::resource('experience-activities', ExperienceActivityController::class);
    Route::post('activity-package-categories', [ActivityPackageCategoryController::class, 'store'])->name('activity-package-categories.store');
    Route::get('activity-package-categories', [ActivityPackageCategoryController::class, 'index'])->name('activity-package-categories.index');

    Route::resource('packages', \App\Http\Controllers\PackageController::class);
    Route::get('package-galleries', [\App\Http\Controllers\PackageGalleryController::class, 'index'])->name('package-galleries.index');
    Route::post('package-galleries/{package}', [\App\Http\Controllers\PackageGalleryController::class, 'store'])->name('package-galleries.store');
    Route::get('package-faqs', [\App\Http\Controllers\PackageFaqController::class, 'index'])->name('package-faqs.index');
    Route::post('package-faqs/{package}', [\App\Http\Controllers\PackageFaqController::class, 'store'])->name('package-faqs.store');
    Route::resource('currencies', \App\Http\Controllers\CurrencyController::class);


    // Route::resource('blogs', BlogController::class);
    Route::post('/admin/api/upload-editor-image', [\App\Http\Controllers\Api\BlogimageController::class, 'uploadEditorImage'])->name('suneditor.upload');
    Route::post('/admin/api/delete-editor-image', [\App\Http\Controllers\Api\BlogimageController::class, 'deleteImage'])->name('suneditor.delete');

    // Contact Page
    Route::get('contact', [ContactController::class, 'index'])->name('contactpage.index');
    Route::post('contact', [ContactController::class, 'store'])->name('contactpage.store');
    Route::put('contact/{contact}', [ContactController::class, 'update'])->name('contactpage.update');

    Route::get('/messages', [CustomerMessageController::class, 'index'])->name('messages.index');
    Route::post('/customer-messages/{id}/mark-read', [CustomerMessageController::class, 'markAsRead'])->name('customer-messages.mark-read');

});
Route::middleware('auth')
    ->prefix('categories')
    ->name('categories.')
    ->group(function () {
        Route::get('/', [BlogCategoryController::class, 'index'])->name('index');
        Route::post('/', [BlogCategoryController::class, 'store'])->name('store');
    });


require __DIR__ . '/auth.php';