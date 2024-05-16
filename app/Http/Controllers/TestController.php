<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\CreateServiceInterface;
use App\Repositories\UserRepository;

use App\Events\PostCreated;
use App\Models\Post;
class TestController extends Controller
{
    //
    // public function doService(CreateServiceInterface $CreateServiceInterface)
    // {
    //     $CreateServiceInterface->doCreateServiceThing();
    //     // return ['return' => 'check name route'];
    //     // return redirect()->route('testNameRoute');
    // }

    // public function index()
    // {
    //     $text = app('text');
    //     return $text;
    // }

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function getUserDetails($userId)
    {
        $user = $this->userRepository->getUserById($userId);
        return response()->json($user);
    }

    public function store()
    {
        // $post = Post::create($request->all());
        // $post = new Post([
        //     'title' => 'Introduction to Laravel Events',
        //     'body' => 'Laravel provides a simple and elegant way to handle events and listeners...',
        //     'user_id' => 1,
        // ]);

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
}
