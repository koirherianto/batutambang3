<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Flash;
use DB;

class UserController extends AppBaseController
{
    /** @var UserReposito   ry $userRepository*/
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->paginate(10);

        return view('users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new User.
     */
    public function create()
    {
        $sRoles=Role::orderBy('name')->get();
        $roles=[];
        $isEditPage = false;
        
        return view('users.create',compact('roles','sRoles','isEditPage'));
    }

    /**
     * Store a newly created User in storage.
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();

        $roles=[];
        if($request->has('s_role_id')){
            $roles=$input['s_role_id'];
        }

        // return $input;
        DB::transaction(function () use($input,$roles) {
            $role = Auth::user()->getRoleNames()->first();
            $user = $this->userRepository->create($input);
            $user->syncRoles($roles);
            $user->password = bcrypt($input['password']);
            $user->save();
        },3);

        Flash::success('Add Acount Success.');
        return redirect(route('users.index'));
    }

    /**
     * Display the specified User.
     */
    public function show($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('users.index'));
        }

        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     */
    public function edit($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('users.index'));
        }

        $sRoles=Role::orderBy('name')->get();
        $roles=$user->roles->pluck('id')->toArray();
        $isEditPage = true; 

        return view('users.edit',compact('roles','sRoles','isEditPage'))->with('user', $user);
    }

    /**
     * Update the specified User in storage.
     */
    public function update($id, Request $request)
    {
        $role = Auth::user()->getRoleNames()->first();
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('users.index'));
        }

        $input=$request->all();

        if($input['password']==='' || $input['password']===null){
            unset($input['password']);
        }

        $roles=[];
        if($request->has('s_role_id')){
            $roles=$input['s_role_id'];
        }
        
        DB::transaction(function () use($input,$roles,$id,$request){
            $user = $this->userRepository->update($input, $id);
            $user->syncRoles($roles);

            if(isset($input['password'])){
                $user->password = bcrypt($input['password']);
            }
            $user->save();
        },3);

        Flash::success('User updated successfully');
        return redirect(route('users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');
        return redirect(route('users.index'));
    }
}
