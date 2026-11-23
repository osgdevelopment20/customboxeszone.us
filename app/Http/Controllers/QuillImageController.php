<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $path = $request->file('image')->store('quill-images', 's3');

        return response()->json(['url' => Storage::disk('s3')->url($path)]);
    }
}
