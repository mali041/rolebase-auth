<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Handle image upload for a specific post.
     */
    public function upload(Request $request, Post $post)
    {
        $request->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('images')) {
            $files = $request->file('images');
            $destinationPath = public_path('/posts/images');

            foreach ($files as $file) {
                if ($file->isValid()) {
                    $originalName = $file->getClientOriginalName();
                    $shortName = substr($originalName, 0, 8) >= 8 ? substr($originalName, 0, 8) : $originalName;
                    $extension = $file->getClientOriginalExtension();
                    $name = time().'-'.$shortName.'.'.$extension;

                    $file->move($destinationPath, $name);

                    $post->images()->create(['name' => $name]);
                }
            }
        }
    }

    public function removeImage(Request $request, Post $post)
    {
        // Handle image deletion
        if ($request->has('remove_images')) {
            // Ensure $request->remove_images is an array
            $imageIds = is_array($request->remove_images) ? $request->remove_images : explode(',', $request->remove_images);

            foreach ($imageIds as $imageId) {
                $image = $post->images()->find($imageId);
                if ($image) {
                    $filePath = public_path('/posts/images/' . $image->name);
                    if (file_exists($filePath)) {
                        unlink($filePath);  // Delete the file from the disk
                    }
                    $image->delete();  // Delete the image record from the database
                }
            }
        }
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle new image uploads
        if ($request->hasFile('new_images')) {
            $destinationPath = public_path('/posts/images');

            foreach ($request->file('new_images') as $file) {
                if ($file->isValid()) {
                    $originalName = $file->getClientOriginalName();
                    $shortName = substr($originalName, 0, 8) >= 8 ? substr($originalName, 0, 8) : $originalName;
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '-' . $shortName . '.' . $extension;

                    $file->move($destinationPath, $filename);

                    $post->images()->create(['name' => $filename]);
                }
            }
        }
    }

    /**
     * Delete images associated with a post.
     */
    public function delete(Post $post)
    {
        foreach ($post->images as $image) {
            $filePath = public_path('posts/images/' . $image->name);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $image->delete();
        }
    }
}
