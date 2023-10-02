<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\UpdateAvatarRequest;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    public function avatar(UpdateAvatarRequest $request): RedirectResponse
    {
        if ($oldAvatar = $request->user()->avatar) {
            Storage::delete($oldAvatar);
        }
        $validated['avatar'] =  $request->file('avatar')->store('avatars');
        User::where('id', auth()->user()->id)->update($validated);
        return redirect(route('profile.edit'))->with('status', 'Avatar Is Updated');
    }

    public function generate(Request $request)
    {
        $result = OpenAI::images()->create([
            'prompt' => "create avatar for user with cool style animated",
            'n' => 1,
            'size' => "256x256",
        ]);
        $content = file_get_contents($result->data[0]->url);
        $filename = Str::random(25);
        if ($oldAvatar = $request->user()->avatar) {
            Storage::delete($oldAvatar);
        }
        Storage::put("avatars/$filename.jpg", $content);
        User::where('id', auth()->user()->id)->update(['avatar' => "avatars/$filename.jpg"]);
        return redirect(route('profile.edit'))->with('status', "Avatar Is Updated From Generated AI");
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
