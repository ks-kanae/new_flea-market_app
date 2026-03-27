<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile');
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $isFirstTime = !$user->profile_completed;

        $profile = $user->profile ?? new Profile([
        'user_id' => $user->id,
        ]);

        if ($request->hasFile('profile_image')) {

            if ($profile->profile_image && Storage::disk('public')->exists($profile->profile_image)) {
            Storage::disk('public')->delete($profile->profile_image);
            }

            $path = $request->file('profile_image')->store('profile_images', 'public');
            $profile->profile_image = $path;
        }

        $profile->name = $request->input('name');
        $profile->postcode = $request->input('postcode');
        $profile->address = $request->input('address');
        $profile->building = $request->input('building');
        $profile->save();

        $user->forceFill([
        'name' => $request->input('name'),
        'profile_completed' => true,
        ])->save();

        if ($isFirstTime) {
        return redirect('/');
        }

        return redirect()->route('mypage.index')->with('success', 'プロフィールを更新しました！');
    }
}
