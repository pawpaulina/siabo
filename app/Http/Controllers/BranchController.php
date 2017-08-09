<?php

namespace App\Http\Controllers;

use App\Store;
use Illuminate\Http\Request;
use App\Branch;
use App\Http\Requests;
class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $branches = Branch::all();
        return view('layouts.branch.index', compact('branches'));
    }

    public function create(Request $request)
    {
        $id = $request->id_role;
        return view("layouts.branch.create")->with('id',$id);
    }

    public function save(Request $request)
    {
        try
        {
            $branch = new Branch();
            $branch->branch_name = $request->branch;

            $branch->save();
            return redirect("branch");
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
        $branches = Branch::find($id);
        return view ('layouts.branch.edit')
            ->with('branch',$branches);
    }

    public function update(Request $request, $id)
    {
        try
        {
            Branch::find($id)->update($request->all());
            return redirect('/branch');
        }
        catch(\QueryException $ex)
        {
            return false;
        }
    }

    public function destroy($id_branch)
    {
        $branch = Branch::find($id_branch);
        if(count($branch)>0)
        {
//            foreach ($stores as $store)
//            {
                if (Store::where('id_branch','=',$id_branch)->count() == 0)
                {
                    Branch::where('id_branch', '=', $id_branch)
                        ->delete();
                }
//            }
        }
        return redirect('/branch');
    }
}
