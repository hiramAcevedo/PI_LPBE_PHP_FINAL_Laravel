<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Image;
use App\Models\Gallery;

// Registrar middleware admin
Route::aliasMiddleware('admin', AdminMiddleware::class);

// Ruta principal
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Rutas autenticadas
Route::middleware(['auth'])->group(function () {
    // Dashboard - Mosaico de imágenes
    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        // Obtener imágenes del usuario actual
        $galleryIds = Gallery::where('user_id', $user->id)
            ->pluck('id');
        
        $images = Image::with('gallery')
            ->whereIn('gallery_id', $galleryIds)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Obtener imágenes de usuarios seguidos
        $followedUsers = DB::table('user_follows')
            ->where('follower_id', $user->id)
            ->pluck('followed_id');
        
        $followedImages = Image::with('gallery.user')
            ->whereHas('gallery', function($query) use ($followedUsers) {
                $query->whereIn('user_id', $followedUsers);
            })
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get()
            ->groupBy('gallery.user_id');
        
        return view('dashboard', compact('images', 'followedImages'));
    })->name('dashboard');
    
    // Explorar imágenes de todos los usuarios
    Route::get('/explore', function () {
        $images = Image::with('gallery.user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('explore', compact('images'));
    })->name('explore');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Galerías
    Route::resource('galleries', GalleryController::class);
    
    // Imágenes
    Route::resource('images', ImageController::class);
    
    // Ruta para actualizar posiciones de imágenes
    Route::post('/images/update-positions', [ImageController::class, 'updatePositions'])->name('images.update-positions');
    
    // Ruta para subir imagen sin elegir galería
    Route::get('/upload', [ImageController::class, 'createDirect'])->name('images.create-direct');
    Route::post('/upload', [ImageController::class, 'storeDirect'])->name('images.store-direct');
    
    // Ruta para obtener detalles de una imagen
    Route::get('/images/{id}/details', [ImageController::class, 'getDetails']);
    
    // Rutas para usuarios
    Route::get('/users', [UserController::class, 'search'])->name('users.search');
    Route::get('/users/search', [UserController::class, 'searchResults'])->name('users.search.results');
    Route::get('/users/{id}', [UserController::class, 'profile'])->name('users.profile');
    Route::post('/users/{id}/follow', [UserController::class, 'follow'])->name('users.follow');
    Route::post('/users/{id}/unfollow', [UserController::class, 'unfollow'])->name('users.unfollow');
    Route::get('/users/{id}/followers', [UserController::class, 'followers'])->name('users.followers');
    Route::get('/users/{id}/following', [UserController::class, 'following'])->name('users.following');
});

// Rutas de administración
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::get('/galleries', [AdminController::class, 'galleries'])->name('admin.galleries');
});

require __DIR__.'/auth.php';
