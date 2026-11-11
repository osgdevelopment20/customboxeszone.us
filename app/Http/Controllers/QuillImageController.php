<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuillImageController extends Controller
{
    public function upload(Request $request)
    {
        if (! $request->hasFile('image')) {
            return response()->json(['error' => 'No image uploaded'], 400);
        }

        $request->validate([
            'image' => 'file|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $path = $request->file('image')->store('quill-images', 'public');

        return response()->json(['url' => asset('storage/' . $path)]);
    }
}
