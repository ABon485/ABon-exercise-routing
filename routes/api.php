<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('/user',function(){
    global $users;
    return $users;
});

Route::prefix('/user')->group(function () {
    global $users; 
    Route::get('/{userIndex}/post/{postIndex}', function ($userIndex, $postIndex) use ($users) {
        if (!isset($users[$userIndex])) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        $user = $users[$userIndex];
    
        if (!isset($user['posts'][$postIndex])) {
            return response()->json(['error' => 'Post not found'], 404);
        }
    
        $post = $user['posts'][$postIndex];
    
        return response()->json($post);
    });
    Route::get('/{id}', function ($id) use ($users) {
        $user = isset($users[$id]) ? $users[$id] : null;
        
        if (!$user) {
            return response()->json(['error' => 'Can not find the user with ID: ' . $id], 404);
        }
        
        return response()->json($user);
    });
    
    Route::get('/{userName}', function ($userName) use ($users) {
        $user = collect($users)->firstWhere('name', $userName);

        if (!$user) {
            // User not found, return an error response
            return response()->json(['error' => 'Can not find the user with name: ' . $userName], 404);
        }
        return response()->json($user);
    });
});