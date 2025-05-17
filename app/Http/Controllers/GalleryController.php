<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
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
        $user = Auth::user();
        
        if ($user->role?->name === 'admin') {
            $galleries = Gallery::with(['user', 'images' => function($query) {
                $query->orderBy('position', 'asc')->limit(4);
            }])->orderBy('created_at', 'desc')->get();
        } else {
            $galleries = Gallery::where('user_id', $user->id)
                ->with(['user', 'images' => function($query) {
                    $query->orderBy('position', 'asc')->limit(4);
                }])
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('galleries.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('galleries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $user = Auth::user();
        
        $gallery = new Gallery([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'user_id' => $user->id,
        ]);
        
        $gallery->save();
        
        return redirect()->route('galleries.show', $gallery->id)
            ->with('success', 'Galería creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $gallery = Gallery::with(['images' => function($query) {
            $query->orderBy('position', 'asc');
        }])->findOrFail($id);
        
        // Verificar que el usuario tenga acceso a esta galería
        $user = Auth::user();
        if ($user->role?->name !== 'admin' && $gallery->user_id !== $user->id) {
            return redirect()->route('galleries.index')
                ->with('error', 'No tienes acceso a esta galería');
        }
        
        return view('galleries.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gallery = Gallery::findOrFail($id);
        
        // Verificar que el usuario tenga acceso a esta galería
        $user = Auth::user();
        if ($user->role?->name !== 'admin' && $gallery->user_id !== $user->id) {
            return redirect()->route('galleries.index')
                ->with('error', 'No tienes acceso a esta galería');
        }
        
        return view('galleries.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
        ]);
        
        $gallery = Gallery::findOrFail($id);
        
        // Verificar que el usuario tenga acceso a esta galería
        $user = Auth::user();
        if ($user->role?->name !== 'admin' && $gallery->user_id !== $user->id) {
            return redirect()->route('galleries.index')
                ->with('error', 'No tienes acceso a esta galería');
        }
        
        $gallery->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);
        
        return redirect()->route('galleries.show', $gallery->id)
            ->with('success', 'Galería actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gallery = Gallery::findOrFail($id);
        
        // Verificar que el usuario tenga acceso a esta galería
        $user = Auth::user();
        if ($user->role?->name !== 'admin' && $gallery->user_id !== $user->id) {
            return redirect()->route('galleries.index')
                ->with('error', 'No tienes acceso a esta galería');
        }
        
        // Las imágenes se eliminarán automáticamente por la relación onDelete('cascade')
        $gallery->delete();
        
        return redirect()->route('galleries.index')
            ->with('success', 'Galería eliminada exitosamente');
    }
}
