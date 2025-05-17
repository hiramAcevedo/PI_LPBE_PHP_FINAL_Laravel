<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Image;
use App\Models\UserFollow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Mostrar vista de búsqueda de usuarios.
     */
    public function search()
    {
        $users = User::where('id', '!=', Auth::id())
            ->withCount('galleries')
            ->get();
        return view('users.search', compact('users'));
    }

    /**
     * Buscar usuarios por nombre o email.
     */
    public function searchResults(Request $request)
    {
        $query = $request->input('query');
        
        $users = User::where('id', '!=', Auth::id())
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->withCount('galleries')
            ->get();
        
        return view('users.search', compact('users', 'query'));
    }

    /**
     * Mostrar perfil de un usuario con sus imágenes.
     */
    public function profile($id)
    {
        $user = User::findOrFail($id);
        $images = Image::whereHas('gallery', function($query) use ($id) {
            $query->where('user_id', $id);
        })->with('gallery')->orderBy('created_at', 'desc')->get();
        
        $authUser = Auth::user();
        $isFollowing = DB::table('user_follows')
            ->where('follower_id', $authUser->id)
            ->where('followed_id', $user->id)
            ->exists();
            
        $followersCount = DB::table('user_follows')
            ->where('followed_id', $user->id)
            ->count();
            
        $followingCount = DB::table('user_follows')
            ->where('follower_id', $user->id)
            ->count();
        
        return view('users.profile', compact('user', 'images', 'isFollowing', 'followersCount', 'followingCount'));
    }

    /**
     * Seguir a un usuario.
     */
    public function follow($id)
    {
        $user = User::findOrFail($id);
        
        if (Auth::id() == $id) {
            return back()->with('error', 'No puedes seguirte a ti mismo.');
        }
        
        $exists = DB::table('user_follows')
            ->where('follower_id', Auth::id())
            ->where('followed_id', $user->id)
            ->exists();
            
        if (!$exists) {
            DB::table('user_follows')->insert([
                'follower_id' => Auth::id(),
                'followed_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return back()->with('success', "Ahora sigues a {$user->name}.");
        }
        
        return back()->with('info', "Ya sigues a {$user->name}.");
    }

    /**
     * Dejar de seguir a un usuario.
     */
    public function unfollow($id)
    {
        $user = User::findOrFail($id);
        
        if (Auth::id() == $id) {
            return back()->with('error', 'No puedes dejar de seguirte a ti mismo.');
        }
        
        $deleted = DB::table('user_follows')
            ->where('follower_id', Auth::id())
            ->where('followed_id', $user->id)
            ->delete();
            
        if ($deleted) {
            return back()->with('success', "Has dejado de seguir a {$user->name}.");
        }
        
        return back()->with('info', "No sigues a {$user->name}.");
    }

    /**
     * Mostrar lista de seguidores.
     */
    public function followers($id)
    {
        $user = User::findOrFail($id);
        $followers = User::join('user_follows', 'users.id', '=', 'user_follows.follower_id')
            ->where('user_follows.followed_id', $user->id)
            ->get(['users.*']);
        
        return view('users.followers', compact('user', 'followers'));
    }

    /**
     * Mostrar lista de usuarios seguidos.
     */
    public function following($id)
    {
        $user = User::findOrFail($id);
        $following = User::join('user_follows', 'users.id', '=', 'user_follows.followed_id')
            ->where('user_follows.follower_id', $user->id)
            ->get(['users.*']);
        
        return view('users.following', compact('user', 'following'));
    }
}
