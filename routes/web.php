<?php

use App\Http\Controllers\TicketController;
use App\Models\User;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Github Login
Route::post('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('login.github');
Route::get('/auth/callback', function () {

    $user = Socialite::driver('github')->user();
    $user = User::firstOrCreate(
        [
            'email' => $user->email
        ],
        [
            'name' => $user->name,
            'password' => bcrypt('password'),
        ]
    );


    Auth::login($user);
    return redirect(url('/dashboard'));

    // $user->token
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [ProfileController::class, 'avatar'])->name('update.avatar');
    Route::patch('/profile/avatar/ai', [ProfileController::class, 'generate'])->name('avatar.ai');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Route For Help Tickets
Route::middleware('auth')->group(function () {
    Route::resource('/ticket', TicketController::class);
});



require __DIR__ . '/auth.php';
