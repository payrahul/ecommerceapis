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
use App\Models\UserSetting;
use App\Service\MyService;

use App\Events\GetUsers;

use App\jobs\HelloWorldJob;

use App\Models\Country;

use App\Models\Video;

use App\Models\Blog;

use App\Models\Tag;


use Illuminate\Support\Facades\DB;

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


    public function getUsersEvents()
    {
        event(new GetUsers('rahul v'));
    }

    public function dispatchJob()
    {
        // Dispatch the job
        HelloWorldJob::dispatch();
        return response()->json(['status' => 'Job dispatched']);
    }

    public function testTransactionTwo()
    {

        try {

            DB::transaction(function () {
                // DB::update('update users set votes = 1');
             
                // DB::delete('delete from posts');
                $proCat = ProductCategories::create([
                    'name'=>'test user 1',
                    'user_id'=>'1',
                    'category_isActive'=>'2'
                ]);
    
                $proCat->update([
                    'user_id' => '3',
                ]);
            });
          return "Done";
          } catch (\Exception $e) {
          
              return $e->getMessage();
          }
    }

    public function productAccessor()
    {
        $product = ProductCategories::find(35);
         $product->name = "Sally";
        $product->save();
        return $product->name;
    }

    public function getUserSetting()
    {
        return User::with('getUserSettingsData')->get();


    }

    public function userData()
    {
        return UserSetting::with('usersData')->get();
    }

    public function getProductData()
    {
        return User::with('productData')->get();
    }

    public function getUserDataInverse()
    {
        return Product::with('userDataOnetomanyinverse')->get();
    }

    public function getUserProductManytomany()
    {
        // $user =  User::find(1);

        // return $user->userProducts;

        return User::with('userProducts')->get();
    }

    public function hasOneThrough()
    {
        $user = User::with('companyName')->with('phoneNumber')->get();

        return $user;
    }

    public function getUserLatestProduct()
    {
        $user = User::with('productData')->with('smallestOrder')->get();
        return $user;
    }

    public function getCountryData()
    {
        // $country = Country::find(1);
        // return $country->posts;

        return Country::with('users')->with('posts')->get();
        // return $country->posts;
    }


    public function createUserImage()
    {
        $user = User::find(2);

        $user->image()->create([
            'url'=>'secondd.png'
        ]);
    }

    public function createPostImage()
    {
        $post = Post::find(2);

        $post->image()->create([
            'url'=>'secondd.png'
        ]);
    }

    public function getPostImage()
    {
        $post = Post::with('image')->get();

        return $post;
    }

    public function creatVideoComment()
    {
        $video = Video::find(2);

        $video->comments()->create([
            'detail'=>'need effort in Video'
        ]);
        return $video;
    }

    public function getComment()
    {
        $video = Video::with('comments')->get();

        // $video->comments()->create([
        //     'detail'=>'need effort in Video'
        // ]);
        return $video;
    }

    public function getVideoComment()
    {
        $video = Video::with('oldestComment')->get();
        return $video;
    }

    public function creatBlogTag()
    {
        // $blog = Blog::create([
        //     'title'=>'blog title for tag used attach for it',
        //     'description'=>'blog desc for tag',
        // ]);

        // $blog->tags()->create([
        //     'tag_name'=>'first tag by blog'
        // ]);

        $blog = Blog::find(5);

        $blog->tags()->attach([2,6]);
        return $blog;
    }

    public function getBlogTag()
    {
        $blog = Tag::with(['blogs','videos'])->get();
        return $blog;
    }
}
