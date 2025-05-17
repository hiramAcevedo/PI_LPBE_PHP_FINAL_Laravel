<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Constructor para aplicar middleware de autenticación
     */
    public function __construct()
    {
        // El middleware 'auth' ya está aplicado en las rutas
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('galleries.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gallery_id = request('gallery_id');
        $gallery = Gallery::findOrFail($gallery_id);
        
        // Verificar que el usuario tenga acceso a esta galería
        $user = Auth::user();
        if ($user->role?->name !== 'admin' && $gallery->user_id !== $user->id) {
            return redirect()->route('galleries.index')
                ->with('error', 'No tienes acceso a esta galería');
        }
        
        return view('images.create', compact('gallery'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'image' => 'required|image|max:2048', // max 2MB
            'gallery_id' => 'required|exists:galleries,id',
        ]);

        $gallery = Gallery::findOrFail($request->input('gallery_id'));
        
        // Verificar que el usuario tenga acceso a esta galería
        $user = Auth::user();
        if ($user->role?->name !== 'admin' && $gallery->user_id !== $user->id) {
            return redirect()->route('galleries.index')
                ->with('error', 'No tienes acceso a esta galería');
        }
        
        // Obtener la posición más alta de las imágenes en esta galería
        $position = Image::where('gallery_id', $gallery->id)
            ->max('position') + 1;
        
        // Subir la imagen
        $path = $request->file('image')->store('images', 'public');
        
        $image = new Image([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'file_path' => $path,
            'position' => $position,
            'gallery_id' => $gallery->id,
        ]);
        
        $image->save();
        
        return redirect()->route('galleries.show', $gallery->id)
            ->with('success', 'Imagen subida exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $image = Image::with('gallery')->findOrFail($id);
        $gallery = $image->gallery;
        
        // Verificar que el usuario tenga acceso a esta galería
        $user = Auth::user();
        if ($user->role?->name !== 'admin' && $gallery->user_id !== $user->id) {
            return redirect()->route('galleries.index')
                ->with('error', 'No tienes acceso a esta imagen');
        }
        
        return view('images.show', compact('image'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $image = Image::findOrFail($id);
        $gallery = $image->gallery;
        
        // Verificar que el usuario tenga acceso a esta galería
        $user = Auth::user();
        if ($user->role?->name !== 'admin' && $gallery->user_id !== $user->id) {
            return redirect()->route('galleries.index')
                ->with('error', 'No tienes acceso a esta imagen');
        }
        
        return view('images.edit', compact('image'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'image' => 'nullable|image|max:2048', // max 2MB
        ]);
        
        $image = Image::findOrFail($id);
        $gallery = $image->gallery;
        
        // Verificar que el usuario tenga acceso a esta galería
        $user = Auth::user();
        if ($user->role?->name !== 'admin' && $gallery->user_id !== $user->id) {
            return redirect()->route('galleries.index')
                ->with('error', 'No tienes acceso a esta imagen');
        }
        
        $image->title = $request->input('title');
        $image->description = $request->input('description');
        
        // Si se subió una nueva imagen
        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior
            if ($image->file_path) {
                Storage::disk('public')->delete($image->file_path);
            }
            
            // Subir nueva imagen
            $path = $request->file('image')->store('images', 'public');
            $image->file_path = $path;
        }
        
        $image->save();
        
        return redirect()->route('galleries.show', $gallery->id)
            ->with('success', 'Imagen actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $image = Image::findOrFail($id);
        $gallery = $image->gallery;
        
        // Verificar que el usuario tenga acceso a esta galería
        $user = Auth::user();
        if ($user->role?->name !== 'admin' && $gallery->user_id !== $user->id) {
            return redirect()->route('galleries.index')
                ->with('error', 'No tienes acceso a esta imagen');
        }
        
        // Eliminar el archivo de imagen
        if ($image->file_path) {
            Storage::disk('public')->delete($image->file_path);
        }
        
        $image->delete();
        
        return redirect()->route('galleries.show', $gallery->id)
            ->with('success', 'Imagen eliminada exitosamente');
    }
    
    /**
     * Actualizar la posición de las imágenes
     */
    public function updatePositions(Request $request)
    {
        $request->validate([
            'positions' => 'required|array',
            'gallery_id' => 'required|exists:galleries,id',
        ]);
        
        $gallery = Gallery::findOrFail($request->input('gallery_id'));
        
        // Verificar que el usuario tenga acceso a esta galería
        $user = Auth::user();
        if ($user->role?->name !== 'admin' && $gallery->user_id !== $user->id) {
            return response()->json([
                'error' => 'No tienes acceso a esta galería'
            ], 403);
        }
        
        $positions = $request->input('positions');
        
        foreach ($positions as $id => $position) {
            Image::where('id', $id)
                ->where('gallery_id', $gallery->id)
                ->update(['position' => $position]);
        }
        
        return response()->json(['success' => true]);
    }

    /**
     * Show the form for creating a new image directly without selecting gallery.
     */
    public function createDirect()
    {
        return view('images.create_direct');
    }
    
    /**
     * Store an image directly to the general gallery.
     */
    public function storeDirect(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'image' => 'required|image|max:2048', // max 2MB
        ]);

        $user = Auth::user();
        
        // Buscar o crear la galería general del usuario
        $gallery = Gallery::firstOrCreate(
            ['user_id' => $user->id, 'is_general' => true],
            ['name' => 'General', 'description' => 'Galería general de imágenes']
        );
        
        // Obtener la posición más alta de las imágenes en esta galería
        $position = Image::where('gallery_id', $gallery->id)
            ->max('position') + 1;
        
        // Subir la imagen
        $path = $request->file('image')->store('images', 'public');
        
        $image = new Image([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'file_path' => $path,
            'position' => $position,
            'gallery_id' => $gallery->id,
        ]);
        
        $image->save();
        
        return redirect()->route('dashboard')
            ->with('success', 'Imagen subida exitosamente');
    }

    /**
     * Get image details in JSON format for modal display.
     */
    public function getDetails(string $id)
    {
        $image = Image::with('gallery.user')->findOrFail($id);
        
        // Verificar si el usuario es propietario de la imagen
        $isOwner = Auth::id() === $image->gallery->user_id;
        
        return response()->json([
            'id' => $image->id,
            'title' => $image->title,
            'description' => $image->description,
            'file_path' => asset('storage/' . $image->file_path),
            'created_at' => $image->created_at->format('d/m/Y H:i'),
            'gallery' => [
                'id' => $image->gallery->id,
                'name' => $image->gallery->name,
                'user_id' => $image->gallery->user_id,
                'user_name' => $image->gallery->user->name,
            ],
            'is_owner' => $isOwner,
        ]);
    }
}
