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
        $isUserExist= User::find($post['user_id']);

        if($isUserExist == NULL){
            return response()->json(['Error'=>'This user is not exist']);
        }else{
            $category = ProductCategories::create($post);
            return response()->json(['category' => $category]);
        }
        
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

        if(isset($post['id']) && !empty($post['id'])){
            $category = ProductCategories::find($post['id']);
            if($category == NULL){
                return response()->json(['success'=>false,'message'=>'Category id is not exist!']);
            }else{
                if($post['name'] != $category->name){
                    $rules['name'] = 'required|unique:product_categories';
                }else{
                    $rules['name'] = 'required';
                }
    
                $validator = Validator::make($post,$rules,$customMessages);
                if($validator->fails()){
                    return response()->json(['errors'=>$validator->errors()->all()]);
                }

                $isUserExist= User::find($post['user_id']);
                if($isUserExist == NULL){
                    return response()->json(['success'=>false,'message'=>'User is not existed!']);
                }else{
                    $updateCategory = ProductCategories::where('id',$post['id'])->update($post);
                    return response()->json(['success'=>true,'message'=>'Category updated successfully']);
                }
            }
        }else{
            return response()->json(['success'=>false,'message'=>'Category id is empty']);
        }
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

        $isUserExist = User::find($post['user_id']);
        $isCategoryExist = ProductCategories::find($post['category_id']);
        
        if($isUserExist != NULL && $isCategoryExist != NULL ){
            $product = Product::create($post);
            return response()->json(['success'=>true,'message'=>'Product added successfully','product'=>$product]);
        }else{
            if($isUserExist == NULL){
                $productMessage[] = "User";
            }
            if($isCategoryExist == NULL){
                $productMessage[] = "Category id";
            }
            return response()->json(['success'=>false,'message' => implode(', ', $productMessage) . ' not exist!']);
        }
    }

    // public function updateProduct()
    // {
    //     $post = Request::all();

    //     $customMessages = [
    //         'product_name.required' => 'Product name is required.',
    //     ];
    //     $rules = [
    //         'product_description' => 'required',
    //         'product_price' => 'required',
    //         'product_discount' => 'required',
    //         'product_image' => 'required',
    //         'user_id' => 'required',
    //         'category_id' => 'required',
    //     ];
    //     // return ['product'=>$post];
    //     if(isset($post['id']) && !empty($post['id'])){

    //         $product = Product::find($post['id']);

    //         if(isset($post['product_name']) && !empty($post['product_name'])){

    //             if ($post['product_name'] != $product->product_name){
    //                 $rules['product_name'] = 'required|unique:products,product_name';
    //             }else{
    //                 $rules['product_name'] = 'required';
    //             }
    //             $validator = Validator::make($post,$rules,$customMessages);
        
    //             if ($validator->fails()){
    //                 return response()->json(['errors'=>$validator->errors()->all()]);
    //             }
    //             $img = $post['product_image'];
    //             $ext = $img->getClientOriginalExtension();
        
    //             $imageName = time().'.'.$ext;
    //             $img->move(public_path().'/uploads/',$imageName);
    //             $post['product_image'] = $imageName;
    //             $updateProduct = Product::where('id',$post['id'])->update($post);
    //             return response()->json(['success'=>true,'message'=>'Product updated successfully']);

    //         }else{
    //             return response()->json(['success'=>false,'message'=>'Product name is not existed!']);
    //         }

    //     }else{

    //         return response()->json(['success'=>false,'message'=>'Product id does not exist']);

    //     }
       
    // }

    public function updateProduct()
    {
        $postData = request()->all();

        $validator = Validator::make($postData, [
            'id' => 'required|exists:products,id',
            'product_name' => 'required|unique:products,product_name,' . $postData['id'],
            'product_description' => 'required',
            'product_price' => 'required',
            'product_discount' => 'required',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max file size as needed
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
        ], [
            'product_name.required' => 'Product name is required.',
            'product_name.unique' => 'Product name must be unique.',
            'product_image.required' => 'Product image is required.',
            'product_image.image' => 'Product image must be a valid image file.',
            'product_image.mimes' => 'Product image must be in jpeg, png, jpg, or gif format.',
            'product_image.max' => 'Product image size must be less than 2MB.',
            'user_id.exists' => 'User does not exist.',
            'category_id.exists' => 'Category does not exist.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $product = Product::findOrFail($postData['id']);
        $product->product_name = $postData['product_name'];
        $product->product_description = $postData['product_description'];
        $product->product_price = $postData['product_price'];
        $product->product_discount = $postData['product_discount'];
        $product->user_id = $postData['user_id'];
        $product->category_id = $postData['category_id'];

        // Handle image upload
        $image = $postData['product_image'];
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('uploads'), $imageName);
        $product->product_image = $imageName;

        $product->save();

        return response()->json(['success' => true, 'message' => 'Product updated successfully']);
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
        $produtWithCategory = ProductCategories::with('products')->get();

        return ['check'=>$produtWithCategory];
        
    }

    public function getCategoryByProduct()
    {
        $product = Product::find(5);
        $category = $product->category;

        return ['check'=>$category];
    }

    public function getAllProducts()
    {
        $products = Product::paginate(2);
        return ['products'=>$products];
    }

    public function productLoadMore()
    {
        $data = Product::skip(3)->take(2)->get();
        return response()->json($data);
    }

    
    // name route

    public function checkNameRoute()
    {
        // return ['return' => 'check name route'];
        return redirect()->route('testNameRoute');
    }

    public function testNameRoute()
    {
        // return redirect()->route('testNameRoute');
        return ['return' => 'check name route'];
    }



}
