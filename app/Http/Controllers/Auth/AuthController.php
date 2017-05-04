<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Role;
use App\Branch;
use App\UserManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    public function __construct() //Fungsi untuk pengecekan login user, jika belum login maka akan redirect ke halaman login
    {
        $this->middleware('guest', ['except' => ['getLogout', 'getRegister', 'postRegister']]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function getRegister()
    {
        $branches = Branch::select('branch.branch_name', 'branch.id_branch')
            ->get();
        $roles = Role::select('role.role', 'role.id_role')
            ->get();
        return view("layouts.User.register")
            ->with('branch',$branches)
            ->with('role',$roles);
    }

    public function postRegister(Request $request)
    {
        if(User::where('email',$request->email)->count()>0)
        {
            return redirect('user')->with('error message','Email already registered');
        }
        else
        {
//            DB::beginTransaction();
            if(User::where('email',$request->email)->withTrashed()->count()>0 )
            {
                $user = User::where('email',$request->email)->withTrashed()->first();
                $user->flag_del = null;
            }
            else
            {
                $user = new User;
                $user_manager = new UserManager;
                if($request->password == $request->password_conf)
                {
                    $user->name = $request->name;
                    $user->username = $request->username;
                    $user->email = $request->email;
                    $user->password = \Hash::make($request->password);
                    $user->phone = $request->phone;
                    $user->id_role = $request->id_role;
                    $user->id_branch = Auth::User()->id_branch;
                    $user->save();

                    $user_manager->id_manager = Auth::User()->id_user;
                    $user_manager->id_user = $user->id_user;
                    $user_manager->save();

                    return redirect("/kalender/".$user->id_user);
                }
            }
        }
    }
}
