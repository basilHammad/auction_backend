<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;




class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

        $result = [];
    
        foreach ($products as $product) {
            // Construct the image URL based on your application's structure
            $imageUrl = $product->img ? url('/storage/images/' . $product->img) : null;

            // Add the product data with the image URL to the result array
            $result[] = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'start_price' => $product->start_price,
                'current_price' => $product->current_price,
                'final_price' => $product->final_price,
                'img' => $imageUrl,
                'college_id' => $product->college_id,
                'start_date' => $product->start_date,
                'end_date' => $product->end_date,
                'status' => $product->status,
            ];
        }

        return response()->json($result);
    }

       /**
     * Get products by college ID.
     *
     * @param  int  $collegeId
     * @return \Illuminate\Http\Response
     */
    public function getByCollege($collegeId)
    {
        // Retrieve the college
        $college = College::find($collegeId);

        if (!$college) {
            return response()->json(['error' => 'College not found.'], 404);
        }

        // Retrieve products belonging to the college
        $products = $college->products;
           $result = [];
    
        foreach ($products as $product) {
            // Construct the image URL based on your application's structure
            $imageUrl = $product->img ? url('/storage/images/' . $product->img) : null;

            // Add the product data with the image URL to the result array
            $result[] = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'start_price' => $product->start_price,
                'current_price' => $product->current_price,
                'final_price' => $product->final_price,
                'img' => $imageUrl,
                'college_id' => $product->college_id,
                'start_date' => $product->start_date,
                'end_date' => $product->end_date,
                'status' => $product->status,
            ];
        }

        return response()->json(['data' => $result]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store(Request $request)
   {
  

    try {
          $request->validate([
        'name' => 'required',
        'start_price' => 'required',
        'college_id' => 'required',
        'start_date' => 'required',
        'end_date' => 'required',
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

    // Parse the end_date string into a Carbon instance
    $endDate = Carbon::createFromFormat('m/d/Y', $data['end_date']);

    // Determine status based on end_date
    $data['status'] = $endDate->isPast() ? 'completed' : 'pending';

    // Create the product with the additional status column
    return Product::create($data);
    } catch (ValidationException $e) {
        // Return validation errors in JSON format with a 422 status code
        return response()->json(['errors' => $e->errors()], 203);
    }
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
           $imageUrl = $product->img ? url('/storage/images/' . $product->img) : null;

            // Add the product data with the image URL to the result array
            $result = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'start_price' => $product->start_price,
                'current_price' => $product->current_price,
                'final_price' => $product->final_price,
                'img' => $imageUrl,
                'college_id' => $product->college_id,
                'start_date' => $product->start_date,
                'end_date' => $product->end_date,
                'status' => $product->status,
            ];

        return response()->json(['data' => $result]);

        // return Product::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $product = Product::find($id);
        $product->update($request->all());
        return $request->all();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Product::destroy($id);
    }

     /**
     * Search for a name
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        return Product::where('name', 'like', '%'.$name.'%')->get();
    }
}