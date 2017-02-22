<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Store;
use App\Branch;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mockery\CountValidator\Exception;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $stores = Store::join('branch', 'store.id_branch', '=', 'branch.id_branch')
            ->get();
        return view('layouts.store.index')
            ->with('store',$stores);
    }

    public function create(Request $request)
    {
        $id = $request->id_stores;
        $branches = Branch::select('branch.id_branch', 'branch.branch_name')
            ->get();
        return view("layouts.store.create")
            ->with('id',$id)
            ->with('branch',$branches);
    }

    public function save(Request $request)
    {
        try
        {
            $stores = new Store();
            $stores->id_branch = $request->id_branch;
            $stores->store_code = $request->str_code;
            $stores->store_name = $request->store_name;
            $stores->address = $request->alamat;
            $stores->latitude = $request->latitude;
            $stores->longitude = $request->longitude;

            $stores->save();

            return redirect("store");
        }
        catch(\QueryException $ex)
        {
            return false;
        }
    }

    public function edit($id)
    {
        $branches = Branch::select('branch.id_branch','branch.branch_name')
            ->get();
        $stores = Store::find($id);
        return view ('layouts.store.edit')
            ->with('stores',$stores)
            ->with('branch',$branches); //yg dibawa ke edit.blade branch nya
    }

    public function update(Request $request, $id)
    {
        try
        {
            Store::find($id)->update($request->all());
            return redirect('/store');
        }
        catch(\QueryException $ex)
        {
            return false;
        }
    }

    public function destroy($id)
    {
        $store = Store::find($id);
        if(count($store)>0)
        {
            Store::where('id','=',$id)
                ->delete();
        }
        return redirect('/store');
    }
}
