<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movies;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Validator, File;

class MoviesController extends Controller
{
    public function findAll(Request $request) {
        $sortBy = "id";
        if ($request->has('sortBy')) {
            $sortBy = $request->get('sortBy');
        }
        
        $sortDir = "desc";
        if ($request->has('sortDir')) {
            $sortDir = $request->get('sortDir');
        }

        $items = Movies::orderBy($sortBy, $sortDir)->get();
        $size =10;
        $totalPages =1;
        $totalItems =count($items);

        $page =1;
        if ($request->has('page')) {
            $page = $request->get('page');
        }

        if($request->has('size')) {
            $size = $request->get('size');
            $totalPages = ceil(count($items) / $size);                    
            $items = $this->paginate($items, $page, $size);
            $items= $items->toArray();
            $items= $items["data"];

            $items = array_values($items);
        } else {
            $totalPages = 1; 
            $size = count($items);                    
            $items = $items;
        }


        $data = array(
            "data" => array(
                "items" => $items, 
                "page" =>$page, 
                "size" => $size, 
                "total_pages" => $totalPages, 
                "total_items" => $totalItems
            ),
            "error_message" => null,
            "status" => 200
        );

        return response()->json($data, 200);
    }

    public function paginate($items, $page, $size, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $size), $items->count(), $size, $page, $options);
    }

    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|unique:movies|max:255',
                'description' => 'required',
                'rating' => 'required|numeric',
                'image' => 'mimes:jpeg,png,jpg,gif',
            ]);

            if ($validator->fails()) {
                return response()->json(['data' => null, 'error_message' => $validator->errors(), 'status' => 400], 400);
            }

            $input = $request->all();
  
            if ($image = $request->file('image')) {
                $destinationPath = public_path().'/images/';
                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);
                $input['image'] = "$profileImage";
            }
        
            $movie=Movies::create($input);
            

            $data = array(
                "data" => $movie,
                "error_message" => null,
                "status" => 200
            );

            return response()->json($data, 200);

        } catch (Exception $ex) {
            return response()->json(
                [
                    "data" => $ex,
                    "error_message" => "Error response retrive",
                    "status" => 501
                ]
            );
        }
    }

    public function detail($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['data' => null, 'error_message' => "The id must be a number.", 'status' => 400], 400);
        }

        $item = Movies::find($id);

        if(is_null($item)){
            $data = array(
                "data" => null,
                "error_message" => "Data not found",
                "status" => 404
            );

            return response()->json($data, 404);
        }

        $data = array(
            "data" => $item,
            "error_message" => null,
            "status" => 200
        );

        return response()->json($data, 200);
    }

    public function update(Request $request, $id) {
        $request["id"] =$id;
        $validator = Validator::make($request->all(), [
            'id' => 'numeric',
            'title' => 'required|max:255|unique:movies,title,'.$id,
            'description' => 'required',
            'rating' => 'required|numeric',
            'image' => 'mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json(['data' => null, 'error_message' => $validator->errors(), 'status' => 400], 400);
        }

        $item = Movies::find($id);

        if(is_null($item)){
            $data = array(
                "data" => null,
                "error_message" => "Data not found",
                "status" => 404
            );

            return response()->json($data, 404);
        }

        if($item->image != null) {
            $image_path = public_path("images/{$item->image}");

            if (File::exists($image_path)) {
                unlink($image_path);
            }
        }

        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = public_path().'/images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }
        $input['updated_at'] = date("Y-m-d H:i:s");
    
        $item->update($input);
        

        $data = array(
            "data" => $item,
            "error_message" => null,
            "status" => 200
        );

        return response()->json($data, 200);
    }

    public function delete($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => 'numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['data' => null, 'error_message' => "The id must be a number.", 'status' => 400], 400);
        }

        $item = Movies::find($id);

        if(is_null($item)){
            $data = array(
                "data" => null,
                "error_message" => "Data not found",
                "status" => 404
            );

            return response()->json($data, 404);
        }

        if($item->image != null) {
            $image_path = public_path("images/{$item->image}");

            if (File::exists($image_path)) {
                unlink($image_path);
            }
        }

        $item->delete();

        $data = array(
            "data" => $item,
            "error_message" => null,
            "status" => 200
        );

        return response()->json($data, 200);
    }
}
