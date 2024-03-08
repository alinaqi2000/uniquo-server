<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    function aws_test_upload(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $imageName = time() . '.' . $request->image->extension();

            $path = Storage::disk('s3')->put('images', $request->image);
            $path = Storage::disk('s3')->url($path);

            /* Store $imageName name in DATABASE from HERE */
            return response()->json(['image' => $imageName, 'path' => $path]);
        } catch (\Throwable $th) {

            return response()->json(['error' => $th->getMessage()]);
        }
    }
    function aws_test_delete(Request $request)
    {
        try {
            $request->validate([
                'image_path' => 'required|string',
            ]);

            $path = Storage::disk('s3')->url($request->image_path);

            if (Storage::disk('s3')->exists($request->image_path)) {
                Storage::disk('s3')->delete($request->image_path);
                return response()->json(['message' => "File deleted successfully", 'path' => $path]);
            } else {
                return response()->json(['message' => "File not found", 'path' => $path]);
            }
        } catch (\Throwable $th) {

            return response()->json(['error' => $th->getMessage()]);
        }
    }
}
