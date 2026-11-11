<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EditorImageController extends Controller
{
    public function upload(Request $request)
    {
        // Support both CKEditor 4 (field: 'upload') and legacy Quill (field: 'image')
        $field = $request->hasFile('upload') ? 'upload' : 'image';

        // CKEditor echoes this back into a <script>; it must only ever be a number.
        $funcNum = (int) $request->input('CKEditorFuncNum');

        $validator = Validator::make($request->all(), [
            $field => 'required|file|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first($field) ?: 'No image uploaded';

            // CKEditor expects a JS error callback when CKEditorFuncNum is present
            if ($request->has('CKEditorFuncNum')) {
                return $this->ckeditorCallback($funcNum, '', $message, 400);
            }

            // Fallback JSON error response
            return response()->json(['error' => $message], 400);
        }

        // store() names the file from its detected content type, never the client's name.
        $path = $request->file($field)->store('editor-images', 'public');
        $url = asset('storage/' . $path);

        // If CKEditor 4 expects a JS callback response
        if ($request->has('CKEditorFuncNum')) {
            return $this->ckeditorCallback($funcNum, $url, 'Image uploaded successfully');
        }

        // JSON response (useful for uploadimage plugin or other consumers)
        return response()->json([
            'uploaded' => 1,
            'url' => $url,
            'fileName' => basename($path),
        ]);
    }

    private function ckeditorCallback(int $funcNum, string $url, string $message, int $status = 200)
    {
        $flags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;
        $urlJs = json_encode($url, $flags);
        $messageJs = json_encode($message, $flags);
        $script = "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, $urlJs, $messageJs);</script>";

        return response($script, $status)->header('Content-Type', 'text/html; charset=utf-8');
    }
}
