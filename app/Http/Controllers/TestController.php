<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\CreateServiceInterface;
use App\Repositories\UserRepository;

use App\Events\PostCreated;
use App\Models\Post;

use App\Models\ProductCategories;
use App\Models\Product;
use App\Models\User;
use App\Service\MyService;
class TestController extends Controller
{
    

    // protected $userRepository;

    // public function __construct(UserRepository $userRepository)
    // {
    //     $this->userRepository = $userRepository;
    // }
    
    public function getUserDetails($userId)
    {
        $user = $this->userRepository->getUserById($userId);
        return response()->json($user);
    }

    public function store()
    {
        $post = Post::create([
            'title' => 'Introduction to Laravel Events',
            'body' => 'Laravel provides a simple and elegant way to handle events and listeners...',
            'user_id' => 1,
        ]);
        // Dispatch the event
        event(new PostCreated($post));
        return response()->json(['message' => 'PostCreated event dispatched with mock data.']);
        // return redirect()->route('posts.index');
    }

    public function productCategoriesProducts()
    {
        $productCategories = ProductCategories::withCount('products')->get();   

        return $productCategories;
    }

    public function productsWithCategories()
    {
        $productsWithCategories = Product::with('productCategories')->get();   

        return $productsWithCategories;
    }

    public function saveManyProduct()
    {
        $productCategories = ProductCategories::find(1);   
        // return $productCategories;

        $productCategories->products()->createMany([
            ['product_name'=>'tourister bag',
            'product_description'=>'worldwide product',
            'product_price'=>'1100',
            'product_discount'=>'10',
            'product_image'=>'1234.jpg',
            'user_id'=>'1',
            'product_isActive'=>1,
            // 'product_discount'=>'',
        ],
            ['product_name'=>'safari bag',
            'product_description'=>'nation product',
            'product_price'=>'1100',
            'product_discount'=>'10',
            'product_image'=>'1234.jpg',
            'user_id'=>'1',
            'product_isActive'=>1,
            // 'product_discount'=>'',
            ]
        ]);

        return "Done";
    }

    public function usersProducts()
    {
        $users = User::get();

        foreach($users as $user){
            echo $user;
            foreach($user->userProduct as $pro){
                echo $pro->product_name;
            }
        }
    }



    public function productsUsers()
    {
        $products = Product::get();
        $finalData = []; 
        foreach($products as $product){
            $productData = ['product' => $product,'user' => $product->users];

           
            $finalData[] = $productData;
        }
        
        return $finalData; 
    }

    public function createProductManyToManyAttach()
    {
        $user = User::find(3);

        $check = $user->userProduct()->attach(1);
        echo $check;
    }

    public function testNamedRoute()
    {
        echo "working";
    }

    public function getDataNamedRoute()
    {
        return redirect()->route('testnamedroute');
    }

    public function showProfile(Request $request)
    {
        // Assume user data is passed here
        $user = [
            'name' => 'John Doe',
            'age' => $request->age,
        ];
        return response()->json($user);
        // return $user;
    }

    protected $myService;

    public function __construct(MyService $myService)
    {
        $this->myService = $myService;
    }

    public function getProvider(Request $request)
    {
        // Use the service to perform some action
        $result = $this->myService->performAction();

        // Return a response
        return response()->json(['result' => $result]);
    }



}
