<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => request()->user(),
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $request->user()->update($request->validated());

        return redirect()->route('profile.edit')->with('status', 'Profile updated.');
    }
}
