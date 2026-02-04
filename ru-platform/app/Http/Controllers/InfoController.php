<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'storage_link_exists' => file_exists(public_path('storage')),
            'image_upload_endpoint' => '/api/dishes (multipart form-data with key "image")',
            'example_image_url' => '/storage/dishes/example.jpg',
        ]);
    }
}
