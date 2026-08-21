<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EditorImageController extends Controller
{
    public function upload(Request $request)
    {
        // Support both CKEditor 4 (field: 'upload') and legacy Quill (field: 'image')
        $file = null;
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
        } elseif ($request->hasFile('image')) {
            $file = $request->file('image');
        }

        if ($file) {
            $path = $file->store('editor-images', 'public');
            $url = asset('storage/' . $path);

            // If CKEditor 4 expects a JS callback response
            if ($request->has('CKEditorFuncNum')) {
                $funcNum = $request->input('CKEditorFuncNum');
                $message = 'Image uploaded successfully';
                $urlJs = addslashes($url);
                $script = "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '$urlJs', '$message');</script>";
                return response($script, 200)->header('Content-Type', 'text/html; charset=utf-8');
            }

            // JSON response (useful for uploadimage plugin or other consumers)
            return response()->json([
                'uploaded' => 1,
                'url' => $url,
                'fileName' => basename($path),
            ]);
        }

        // CKEditor expects a JS error callback when CKEditorFuncNum is present
        if ($request->has('CKEditorFuncNum')) {
            $funcNum = $request->input('CKEditorFuncNum');
            $message = 'No image uploaded';
            $script = "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '', '$message');</script>";
            return response($script, 400)->header('Content-Type', 'text/html; charset=utf-8');
        }

        // Fallback JSON error response
        return response()->json(['error' => 'No image uploaded'], 400);
    }
}
