<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Role;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $roles = Role::all();
        return view('layouts.role.index', compact('roles'));
    }

    public function create(Request $request)
    {
        $id = $request->id_role;
        return view("layouts.role.create")->with('id',$id);
    }

    public function save(Request $request)
    {
        try
        {
            $roles = new Role;
            $roles->role = $request->role;

            $roles->save();
            return redirect("/role");
        }
        catch(\QueryException $ex)
        {
            return false;
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $roles = Role::find($id);
        return view ('layouts.role.edit')
            ->with('role',$roles);
    }

    public function update(Request $request, $id)
    {
        try
        {
            Role::find($id)->update($request->all());
            return redirect('/role');
        }
        catch(\QueryException $ex)
        {
            return false;
        }
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        if(count($role)>0)
        {
            Role::where('id_role','=',$id)
                ->delete();
        }
        return redirect('/role');
    }
}
