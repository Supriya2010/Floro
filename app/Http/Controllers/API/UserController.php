<?php
namespace App\Http\Controllers\API;
use App\Exceptions\SuperAdminException;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;

class UserController extends Controller
{
    /**
     * @var UserService $userService
     */
    private $userService;
    private $user;
    private $userRepository;

    /**
     * UserController constructor.
     * Initialize all class instances.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Method to show users list.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    // public function index()
    // {
         
    //      $user = Auth::user(); 
         
    //     try {
    //         $table = $this->userService->getAllUsers();
    //     } catch (\ErrorException $exception) {
    //         return redirect('/users')->with('errorMessage',
    //             __('frontendMessages.EXCEPTION_MESSAGES.SHOW_USERS_LIST'));
    //      }
    //     // return view('admin.users.usersList', ['table' => $table]);
    //     return response()->json(['success' => $user], $this-> successStatus);
    // }

    public function index()
    {
        $users=$this->userService->index();
       
      
        return response()->json(['success' => $users], $this-> successStatus);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * 
     */

     
    public function create()
    {
        return view('admin.users.user');
    }
    
    public function store(CreateUserRequest $request)
    {
        $result = $this->userService->createUser($request);
        return response()->json([
            'message' => 'user created successfully' ]);    
        // if (!$result) {
        //     return redirect('/users')->with('errorMessage',
        //         __('frontendMessages.EXCEPTION_MESSAGES.CREATE_USER_MESSAGE'));
                
        // }
        // return redirect('/users')->with('successMessage', __('frontendMessages.SUCCESS_MESSAGES.USER_CREATED'));
    }
    
    public function edit($id)
    {
        $user = $this->userService->getUser($id);
        if ($user == null) {
            return redirect('/users')->with('errorMessage',
                __('frontendMessages.EXCEPTION_MESSAGES.FIND_USER_MESSAGE'));
        }
        return view('admin.users.user', ['user' => $user]);
    }
    
    public function update(UpdateUserRequest $request, $id)
    {
        $result = $this->userService->updateUser($request, $id);
        return response()->json([
            'message' => 'user updated successfully' ]);   
        // if ($result == null) {
        //     return redirect('/users/edit')->with('errorMessage',
        //         config('frontendMessages.EXCEPTION_MESSAGES.UPDATE_USER_MESSAGE'));
        // }
        // return redirect('/users')->with('successMessage', __('frontendMessages.SUCCESS_MESSAGES.USER_UPDATED'));
    }
    
    public function destroy($id)
    {
        $result = $this->userService->deleteUser($id);
        if (!$result) {
            return redirect('/users')->with('errorMessage',
                __('frontendMessages.EXCEPTION_MESSAGES.DELETE_USER_MESSAGE'));
        }
        return redirect('/users')->with('successMessage', __('frontendMessages.SUCCESS_MESSAGES.USER_DELETED'));
    }

    public function delete($id) {
        $user = User::findOrFail($id);
        if($user){
           $user->delete(); 
           return response()->json(['data' => 'User deleted '], 200);
         }
        else
        {
            return response()->json(error);
        } 
    }


    public $successStatus = 200;
/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['success' => $success], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'username' => 'required', 
            'email' => 'required|email', 
            'first_name' => 'required', 
            'last_name' => 'required', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
            'address' => 'required', 
            'house_number' => 'required', 
            'postal_code' => 'required', 
            'city' => 'required', 
            'telephone_number' => 'required', 
                    ]);
            if ($validator->fails()) { 
                        return response()->json(['error'=>$validator->errors()], 401);            
                    }
            $input = $request->all(); 
                    $input['password'] = bcrypt($input['password']); 
                    $user = User::create($input); 
                    $success['token'] =  $user->createToken('MyApp')-> accessToken; 
                    $success['name'] =  $user->name;
            return response()->json(['success'=>$success], $this-> successStatus); 
    }

     public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    } 

     public function getSearchResults(Request $request) 
     {
        $data = $request->get('data');
        $search_users = User::where('username', 'like', "%{$data}%")
                            ->orWhere('first_name', 'like', "%{$data}%")
                            ->orWhere('last_name', 'like', "%{$data}%")
                            ->orWhere('email', 'like', "%{$data}%")
                            ->get();
        return response()->json(['data'=>$search_users]); 
     }

     public function logout(Request $request)
     {
        $request->user()->token()->revoke();
        return response()->json([
        'message' => 'user logged out' ]);    
     }

     public function sortUser(Request $request,User $user)
     {
        $columnName = $request->get('columnName');
        $sortby = $request->get('sortby');
        $sort_users =  User::orderBy($columnName,$sortby)    
        ->get();
        return $sort_users;
     }
}