<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\College;
use Illuminate\Validation\ValidationException;


class CollegeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $colleges = College::all();

        $result = [];
    
        foreach ($colleges as $college) {
            // Construct the image URL based on your application's structure
            $imageUrl = $college->img ? url('/storage/images/' . $college->img) : null;

            // Add the product data with the image URL to the result array
            $result[] = [
                'id' => $college->id,
                'name' => $college->name,
                'img' => $imageUrl,
            ];
        }

        return response()->json($result);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
             'name' => 'required',
             'img' => 'required',
            ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $imageName); // Store the image in the 'public/images' directory
            $data['img'] = $imageName; // Save the image path in the database
        }

        return College::create($data);
        } catch (ValidationException $e) {
            // Return validation errors in JSON format with a 422 status code
            return response()->json(['errors' => $e->errors()], 203);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
