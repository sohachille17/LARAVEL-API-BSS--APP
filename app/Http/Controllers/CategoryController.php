<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Category;

class CategoryController extends Controller
{
    public function createCategory(Request $request){

        $rules = array(

            'categoryName' => 'required',
            'categoryDescription' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return $validator->errors();
        }else{

            $category = Category::create([

            'categoryName' => $request->categoryName,
            'categoryDescription' => $request->categoryDescription,


            ]);

            if($category){
                return response()->json([
                    "message"=>"Category created successfully",
                    "data"=> $category
                ], 201);
            }

        }



        

    }
    public function getCategoryId($id){

        $category = Category::find($id);
        if(!empty($category)){
            return response()->json(
                $category
            );
        }
    }
    public function updateCategory(Request $request, $id){
        if(Category::where('id', $id)->exists()){
            $category = Category::find($id);

            $category->categoryName = is_null($request->categoryName) ? $category->categoryName : $request->categoryName;
            $category->categoryDescription = is_null($request->categoryDescription) ? $category->categoryDescription : $request->categoryDescription;

            $category->save();
            return response()->json([
                "Success"=> true,
                "Message" => "Category updated",
                "data" => $category
            ]);
        }                   
    }
    public function deleteCategory($id){
        $category = Category::findOrFail($id);
        $category->delete();
    }
    public function getAllCategory(){
        $category = Category::all();
        return response()->json($category);
    }
}
