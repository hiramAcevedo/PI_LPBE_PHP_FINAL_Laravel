<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Constructor para aplicar middleware de autenticación y admin
     */
    public function __construct()
    {
        // Los middlewares 'auth' y 'admin' ya están aplicados en las rutas
    }
    
    /**
     * Mostrar el panel de administración
     */
    public function dashboard()
    {
        $users = User::withCount('galleries')->get();
        $galleries = Gallery::withCount('images')->get();
        
        return view('admin.dashboard', compact('users', 'galleries'));
    }
    
    /**
     * Mostrar lista de usuarios
     */
    public function users()
    {
        $users = User::withCount('galleries')->get();
        return view('admin.users', compact('users'));
    }
    
    /**
     * Editar un usuario
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }
    
    /**
     * Actualizar un usuario
     */
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
        ]);
        
        $user = User::findOrFail($id);
        
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role_id' => $request->input('role_id'),
        ]);
        
        return redirect()->route('admin.users')
            ->with('success', 'Usuario actualizado exitosamente');
    }
    
    /**
     * Eliminar un usuario
     */
    public function destroyUser($id)
    {
        if ($id == Auth::id()) {
            return redirect()->route('admin.users')
                ->with('error', 'No puedes eliminar tu propio usuario');
        }
        
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect()->route('admin.users')
            ->with('success', 'Usuario eliminado exitosamente');
    }
    
    /**
     * Mostrar todas las galerías
     */
    public function galleries()
    {
        $galleries = Gallery::with('user')->withCount('images')->get();
        return view('admin.galleries', compact('galleries'));
    }
}
