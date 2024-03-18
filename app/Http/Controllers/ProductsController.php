<?php

namespace App\Http\Controllers;

use App\Models\ProductCategories;
use App\Models\Product;
use Validator;
use Request;
use App\Models\WishCartOrder;
use App\Models\User;
class ProductsController extends Controller
{
    public function createCategory()
    {
        $post = Request::all();

        $customMessages = [
            'name.required' => 'Category name is required.',
        ];
        $rules = [
            'name' => 'required|unique:product_categories',
            'user_id' => 'required'
        ];
        $validator = Validator::make($post, $rules, $customMessages);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $post['category_isActive'] = 1;
        $category = ProductCategories::create($post);
        return response()->json(['category' => $category]);
    }

    public function updateCategory()
    {
        $post = Request::all();

        $customMessages=[
            'name.required' => 'Category name is required.'
        ];
        $rules = [
            'user_id' => 'required'
        ];
        $category = ProductCategories::find($post['id']);

        if($post['name'] != $category->name){
            $rules['name'] = 'required|unique:product_categories';
        }else{
            $rules['name'] = 'required';
        }

        $validator = Validator::make($post,$rules,$customMessages);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        $updateCategory = ProductCategories::where('id',$post['id'])->update($post);
        return response()->json(['success'=>true,'message'=>'Category updated successfully']);
    }

    public function createProduct()
    {
        $post = Request::all();
        $customMessages = [
            'product_name.required' => 'Product name is required.',
        ];
        $rules = [
            'product_name' => 'required|unique:products',
            'product_description' => 'required',
            'product_price' => 'required',
            'product_discount' => 'required',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required',
            'category_id' => 'required',
        ];
        $validator = Validator::make($post,$rules,$customMessages);

        if ($validator->fails()){
            return response()->json(['error'=>$validator->errors()->all()]);
        }
        $post['product_isActive'] = "1";

        $img = $post['product_image'];
        $ext = $img->getClientOriginalExtension();

        $imageName = time().'.'.$ext;
        $img->move(public_path().'/uploads/',$imageName);
        $post['product_image'] = $imageName;
        $product = Product::create($post);

        return response()->json(['success'=>true,'message'=>'Product added successfully','product'=>$product]);
    }

    public function updateProduct()
    {
        $post = Request::all();

        $customMessages = [
            'product_name.required' => 'Product name is required.',
        ];
        $rules = [
            'product_description' => 'required',
            'product_price' => 'required',
            'product_discount' => 'required',
            'product_image' => 'required',
            'user_id' => 'required',
            'category_id' => 'required',
        ];

        $product = Product::find($post['id']);

        if ($post['product_name'] != $product->product_name){
            $rules['product_name'] = 'required|unique:products,product_name';
        }else{
            $rules['product_name'] = 'required';
        }
        $validator = Validator::make($post,$rules,$customMessages);

        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        $img = $post['product_image'];
        $ext = $img->getClientOriginalExtension();

        $imageName = time().'.'.$ext;
        $img->move(public_path().'/uploads/',$imageName);
        $post['product_image'] = $imageName;
        $updateProduct = Product::where('id',$post['id'])->update($post);
        return response()->json(['success'=>true,'message'=>'Product updated successfully']);
    }

    public function createRequestType()
    {
        $post = Request::all();
        $rules = [
            'wco_type' => 'required|in:wish,cart,order',
            'user_id' => 'required|integer',
        ];

        if (!empty($post['wco_type'])) {
            if ($post['wco_type'] == 'wish') {
                $rules['wish_id'] = 'required|integer|unique:wish_cart_orders';

                $post['cart_id'] = null;
                $post['order_id'] = null;
            } elseif ($post['wco_type'] == 'cart') {
                $rules['cart_id'] = 'required|integer|unique:wish_cart_orders';

                $post['wish_id'] = null;
                $post['order_id'] = null;
            } elseif ($post['wco_type'] == 'order') {
                $rules['order_id'] = 'required|integer|unique:wish_cart_orders';

                $post['wish_id'] = null;
                $post['cart_id'] = null;
            }
        }

        $validator = Validator::make($post,$rules);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        $post['wco_isActive'] = '1';
        $wishCartOrder = WishCartOrder::create($post);

        if(!empty($wishCartOrder['wish_id'])){
            $requestDone['wish'] = ['sucess'=>true,'message'=>'your request done for wish!'];
        }else if(!empty($wishCartOrder['cart_id'])){
            $requestDone['cart'] = ['sucess'=>true,'message'=>'your request done for wish!'];
        }else if(!empty($wishCartOrder['order_id'])){
            $requestDone['order'] = ['sucess'=>true,'message'=>'your request done for wish!'];
        }

        return response()->json($requestDone);
    }

    public function wishCart()
    {
        $post = Request::all();

        $rules = [
            'id' => 'required'
        ];

        $validator = Validator::make($post,$rules);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $wishCartOrder = WishCartOrder::find($post['id']);
        if(!empty($wishCartOrder) && !empty($wishCartOrder['wish_id'])){

            $wishCartOrder->cart_id = $wishCartOrder->wish_id;
            $wishCartOrder->wish_id = null;

            $wishCartOrder->save();
            $requestDone = ['success'=>true,'message'=>'product added to cart successfully'];
        }else{
            $requestDone = ['success'=>false,'message'=>'product was not found'];
        }
        return response()->json($requestDone);
    }

    public function cartOrder()
    {
        $post = Request::all();

        $rules = [
            'user_id'=>'required|integer'
        ];

        $validator = Validator::make($post,$rules);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        $WishCartOrder = WishCartOrder::whereNotNull('cart_id')->where('user_id',$post['user_id'])->get();
        if(!$WishCartOrder->isEmpty()){
            foreach($WishCartOrder as $wish){
                $wish->order_id = $wish->cart_id;
                $wish->cart_id = null;
                $wish->save();
            }
            $requestDone = ['success'=>true,'message'=>'order placed successfully1'];
        }else{
            $requestDone = ['success'=>false,'message'=>'order not found'];
        }
        
        return response()->json($requestDone);
    }

    public function getProducts()
    {
        $post = Request::all();

        // $pc = ProductCategories::find(1)->products;
        // return ['check'=>$pc];

        // $category = ProductCategories::find(5)->products;
        // $product = $category->products;

        // $product = Product::find(1);
        // $category = $product->category;

        // $productCategories = ProductCategories::all()->products;

        // $data = user::find(1)->products;

        // $data = Product::find(1)->user;

        $user = ProductCategories::with('products')->find(5);

        return ['check'=>$user];
    }



}
