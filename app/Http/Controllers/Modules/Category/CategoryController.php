<?php

namespace App\Http\Controllers\Modules\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use App\Models\Category;
use App\Models\Subcategory;
use Response;
use Validator;
class CategoryController extends Controller
{
	/**
     *   Method      : add
     *   Description : For category add
     *   Author      : Sayan
     *   Date        : 2022-JAN-28
     **/
    public function add(Request $request)
    {
    	$response = [];
    	try{
    	
    	$validator = Validator::make($request->all(), [
                'category_name'        => 'required|string',
        ]);

        if ($validator->fails()) {
                $response['error']['validation'] = $validator->errors();
                return Response::json($response);
        }

        // check-category-exits 
        $check = Category::where('category_name',$request->category_name)->first();
        if ($check!="") {
        	$response['error'] = 'Category already added';
        	return Response::json($response);
        }


    	$category = new Category;
    	$category->category_name = $request->category_name;
    	$category->user_id = 0;
    	$category->status = 1;
    	$category->save();
    	$response['success'] =  'Category added successfully';
    	return Response::json($response);
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

   /**
     *   Method      : show
     *   Description : showing perticual category
     *   Author      : Sayan
     *   Date        : 2022-JAN-28
    **/

    public function show($id)
    {
    	$response = [];
    	try{
    	 $check = Category::where('id',$id)->first();	
    	 // check-category-exits or not
    	 if ($check=='') {
    	 	$response['error'] = 'Category does not exits';
    	 	return Response::json($response);
    	 }

    	 $data = $check;
    	 $response['data'] = $data;
    	 return Response::json($response);

    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

   /**
     *   Method      : update
     *   Description : category update
     *   Author      : Sayan
     *   Date        : 2022-JAN-28
    **/

    public function update(Request $request)
    {
    	$response = [];
    	try {

    	$validator = Validator::make($request->all(), [
                'category_name' => 'required',
                'id'=>'required',
        ]);	

        if ($validator->fails()) {
                $response['error']['validation'] = $validator->errors();
                return Response::json($response);
        }

        // checking category added or not

        $check = Category::where('category_name',$request->category_name)->where('id','!=',$request->id)->first();

        if ($check!="") {
        	$response['error'] = 'Category already added';
        	return Response::json($response);
        }

        $update = Category::where('id',$request->id)->update(['category_name'=>$request->category_name]);
        $data = Category::where('id',$request->id)->first();
        $response['message'] = 'Category updated successfully';
        $response['data'] = $data;	
        return Response::json($response);
		} catch (Exception $e) {
    		$response['error'] = $e->getMessage();
            return Response::json($response);
    	}

    }

   /**
     *   Method      : delete
     *   Description : category delete
     *   Author      : Sayan
     *   Date        : 2022-JAN-28
    **/

    public function delete($id)
    {
    	$response = [];
    	try{
    	 $check = Category::where('id',$id)->first();	
    	 // check-category-exits or not
    	 if ($check=='') {
    	 	$response['error'] = 'Category does not exits';
    	 	return Response::json($response);
    	 }

    	 $check2 = Subcategory::where('category_id',$id)->first();
    	 if ($check!='') {
    	 	$response['error'] = 'Category can not be deleted as it has subcategory';
    	 	return Response::json($response);
    	 }

    	 Category::where('id',$id)->delete();
    	 $response['message'] = 'Category deleted successfully';
    	 return Response::json($response);

    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

   /**
     *   Method      : status
     *   Description : category status change
     *   Author      : Sayan
     *   Date        : 2022-JAN-28
    **/ 

    public function status($id)
    {
    	$response = [];
    	try{
    	 $check = Category::where('id',$id)->first();	
    	 // check-category-exits or not
    	 if ($check=='') {
    	 	$response['error'] = 'Category does not exits';
    	 	return Response::json($response);
    	 }

    	 // active 
    	 if ($check->status==0) {
    	 	Category::where('id',$id)->update(['status'=>1]);
    	 	$response['message'] = 'Category activated successfully';
    	 	return Response::json($response);
    	 }
    	 // inactive 
    	 if ($check->status==1) {
    	 	Category::where('id',$id)->update(['status'=>0]);
    	 	$response['message'] = 'Category deactivated successfully';
    	 	return Response::json($response);
    	 }

    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }




    	     //////////////////////// sub category related oparations///////////////////////////////////



   /**
     *   Method      : categoryFetch
     *   Description : category fecth
     *   Author      : Sayan
     *   Date        : 2022-JAN-28
    **/

   public function categoryFetch()
   {
   	  $data = Category::where('user_id',0)->where('status',1)->get();
   	  $response['data'] = $data;
   	  return Response::json($response);
   }



   /**
     *   Method      : subcategoryadd
     *   Description : sub category add
     *   Author      : Sayan
     *   Date        : 2022-JAN-28
    **/ 

    public function subcategoryadd(Request $request)
    {
    	$response = [];
    	try{
    	
    	$validator = Validator::make($request->all(), [
                'category_id' => 'required',
                'sub_category_name'=>'required',
        ]);

        if ($validator->fails()) {
                $response['error']['validation'] = $validator->errors();
                return Response::json($response);
        }

        // check-category-exits 
        $check = Subcategory::where('sub_category_name',$request->sub_category_name)->where('category_id',$request->category_id)->first();
        if ($check!="") {
        	$response['error'] = 'Sub category already added in this category';
        	return Response::json($response);
        }


    	$category = new Subcategory;
    	$category->sub_category_name = $request->sub_category_name;
    	$category->category_id = $request->category_id;
    	$category->user_id = 0;
    	$category->status = 1;
    	$category->save();
    	$response['success'] =  'Sub category added successfully';
    	return Response::json($response);
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }



   /**
     *   Method      : subcategoryshow
     *   Description : showing perticual sub category
     *   Author      : Sayan
     *   Date        : 2022-JAN-28
    **/

    public function subcategoryshow($id)
    {
    	$response = [];
    	try{
    	 $check = Subcategory::where('id',$id)->first();	
    	 // check-category-exits or not
    	 if ($check=='') {
    	 	$response['error'] = 'Sub category does not exits';
    	 	return Response::json($response);
    	 }

    	 $data = $check;
    	 $response['data'] = $data;
    	 return Response::json($response);

    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }


   /**
     *   Method      : subcategoryupdate
     *   Description : sub category update
     *   Author      : Sayan
     *   Date        : 2022-JAN-28
    **/

    public function subcategoryupdate(Request $request)
    {
    	$response = [];
    	try {

    	$validator = Validator::make($request->all(), [
                'sub_category_name' => 'required',
                'category_id'=>'required',
                'id'=>'required',
        ]);	

        if ($validator->fails()) {
                $response['error']['validation'] = $validator->errors();
                return Response::json($response);
        }
        // checking sub category added or not
        $check = Subcategory::where('sub_category_name',$request->sub_category_name)->where('category_id',$request->category_id)->where('id','!=',$request->id)->first();
        if ($check!="") {
        	$response['error'] = 'Sub category already added in this category';
        	return Response::json($response);
        }
        // update category
        $update = Subcategory::where('id',$request->id)->update(['sub_category_name'=>$request->sub_category_name,'category_id'=>$request->category_id]);

        $data = Subcategory::where('id',$request->id)->first();
        $response['message'] = 'Sub category updated successfully';
        $response['data'] = $data;	
        return Response::json($response);
		} catch (Exception $e) {
    		$response['error'] = $e->getMessage();
            return Response::json($response);
    	}
    }


   /**
     *   Method      : subcategorystatus
     *   Description : sub category status change
     *   Author      : Sayan
     *   Date        : 2022-JAN-28
    **/

    public function subcategorystatus($id)
    {
    	$response = [];
    	try{
    	 $check = Subcategory::where('id',$id)->first();	
    	 // check-sub category-exits or not
    	 if ($check=='') {
    	 	$response['error'] = 'Sub category does not exits';
    	 	return Response::json($response);
    	 }

    	 // active 
    	 if ($check->status==0) {
    	 	Subcategory::where('id',$id)->update(['status'=>1]);
    	 	$response['message'] = 'Sub category activated successfully';
    	 	return Response::json($response);
    	 }
    	 // inactive 
    	 if ($check->status==1) {
    	 	Subcategory::where('id',$id)->update(['status'=>0]);
    	 	$response['message'] = 'Sub category deactivated successfully';
    	 	return Response::json($response);
    	 }

    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }




   /**
     *   Method      : subcategorydelete
     *   Description : sub category delete
     *   Author      : Sayan
     *   Date        : 2022-JAN-28
    **/

    public function subcategorydelete($id)
    {
    	$response = [];
    	try{
    	 $check = Subcategory::where('id',$id)->first();	
    	 // check-category-exits or not
    	 if ($check=='') {
    	 	$response['error'] = 'Sub category does not exits';
    	 	return Response::json($response);
    	 }

		
		 Subcategory::where('id',$id)->delete();
    	 $response['message'] = 'Sub category deleted successfully';
    	 return Response::json($response);

    	}catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }








}
