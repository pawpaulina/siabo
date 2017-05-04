<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Branch;
use App\Http\Requests;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = user::all();
        return view('layouts.User.index', compact('users'));
    }

    protected function validator(array $user)
    {
    }

    public function create(array $user)
    {
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Request $request)
    {
        $user = User::select('id_user', 'name')
            ->where('id_branch','=',$request->cabangid)
            ->get();
        $userlist = "";
        foreach ($user as $row)
        {
            $userlist .="<option value='". $row->id_user ."'>". $row->name ."</option>";
        }
        return $userlist;
    }

    public function edit($id)
    {
        $edituser = user::find($id);
        $branches = Branch::select('branch.branch_name', 'branch.id_branch')
            ->get();
        return view ('layouts.User.edit')
            ->with('user',$edituser)
            ->with('branch',$branches);
    }

    public function update(Request $request, $id)
    {
        try
        {
            User::find($id)->update($request->all());
            return redirect('/user');
        }
        catch(\QueryException $ex)
        {
            return false;
        }
        
    }

    public function destroy($id_user)
    {
        $user = User::find($id_user);
        if(count($user)>0)
        {
            User::where('id_user','=',$id_user)
            ->delete();
        }
        return redirect('/user');
    }
}
