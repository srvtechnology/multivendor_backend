<?php

namespace App\Http\Controllers\Modules\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Products;
use Response;
use Validator;
class ProductController extends Controller
{

   /**
     *   Method      : productCategory
     *   Description : For get product category
     *   Author      : Sayan
     *   Date        : 2022-JAN-31
     **/
	public function productCategory(Request $request)
	{
		$response = [];
		try{
	     $data = Category::where('user_id',0)->where('status',1)->get();
	   	 $response['data'] = $data;
	   	 return Response::json($response);		

		}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
	}

   /**
     *   Method      : add
     *   Description : For get subcategory
     *   Author      : Sayan
     *   Date        : 2022-JAN-31
     **/
    public function getsubCategory(Request $request)
    {
    	$response = [];
    	try{

    	$validator = Validator::make($request->all(), [
                'category_id'        => 'required',
        ]);

        if ($validator->fails()) {
                $response['error']['validation'] = $validator->errors();
                return Response::json($response);
        }

        $category = $request->category_id;
        $subcategory = Subcategory::where('category_id',$category)->where('status','1')->get();
        if (count(@$subcategory)>0) {
        	$response['data'] = $subcategory;
   	  	    return Response::json($response);
        }else{
        	$response['message'] = 'No sub category found';
    	 	return Response::json($response);
        }
        

		}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

   /**
     *   Method      : addProducts
     *   Description : For add products
     *   Author      : Sayan
     *   Date        : 2022-JAN-31
     **/
    public function addProducts(Request $request)
    {
    	$response = [];
    	try{

    	 $validator = Validator::make($request->all(), [
    	 		'product_name' => 'required',
                'category_id'  => 'required',
                'description'  => 'required',
                'price' => 'required',
                'availability' => 'required',
                'product_image'=>'required'
        ]);

        if ($validator->fails()) {
                $response['error']['validation'] = $validator->errors();
                return Response::json($response);
        }
        $product = new Products;
        // product-image
        if ($request->hasFile('product_image'))
	    {
	         $image = $request->product_image;
	         $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/product_image",$filename);
        }
        // other-fields
        $product->product_name = $request->product_name;
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discount_price = $request->discount_price; 
        $product->availability = $request->availability; 
        $product->product_image = $filename;   
        $product->status = 'A'; 
        $product->user_id = auth()->user()->id;
        $product->save();
        $response['success'] =  'Product added successfully';
    	return Response::json($response);


		}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }		

    }


    /**
     *   Method      : allProducts
     *   Description : Shwoing all products
     *   Author      : Sayan
     *   Date        : 2022-JAN-31
     **/
    public function allProducts(Request $request)
    {
    	$response = [];
    	try{
    	 $products = Products::with(['category','subcategory'])->where('status','!=','D')->where('user_id',auth()->user()->id)->get();
    	 $response['data'] = $products;
    	 return Response::json($response); 	
    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }		
    }

  
}
