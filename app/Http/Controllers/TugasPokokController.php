<?php

namespace App\Http\Controllers;

use App\Hari;
use App\TugasPokok;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mockery\CountValidator\Exception;

class TugasPokokController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tugasPokok = TugasPokok::select('id', 'judul', 'deskripsi', 'foto', 'exp_date')
            ->get();
//        dd($tugasPokok);
        return view("layouts.TugasPokok.index")
            ->with('tugasPokok', $tugasPokok);
    }

    public function getTP()
    {
        try
        {
            $listOptions = "";
            $tugasPokok = TugasPokok::select('id', 'judul', 'deskripsi', 'foto', 'exp_date')
                ->get();
            foreach ($tugasPokok as $row)
            {
                $listOptions .= "<div class='panel panel-default'><div class='panel-heading'> ".$row->judul." </div><div class='panel-body' style='text-align:left;'>".$row->deskripsi."</div></div>";
            }
            return $listOptions;
        }
        catch (\QueryException $ex)
        {
            return false;
        }
    }

    public function create()
    {
//        $hari = Hari::select('id', 'nama_hari')->get();
//        dd($hari);
        return view("layouts.TugasPokok.create");
    }

    public function store(Request $request)
    {
//        $this->validate($request, [
//            'hari' => 'required',
//            'judul' => 'required',
//            'deskripsi' => 'required',
//            'foto' => 'required',
//        ]);
//        \DB::beginTransaction();
//        try
//        {
//            TugasPokok::create($tPokok);
//            \DB::commit();
//
//            alert()->success('Data berhasil di tambahkan', 'Tambah Data Berhasil!');
//            return redirect()->route('layouts.TugasPokok.index');
//        }
//        catch (\QueryException $ex)
//        {
//            \DB::rollback();
//            throw $ex;
//        }
//        dd($request->expdate);
        if($request->expdate == "")
        {
            $exp = null;
        }
        else
        {
            $exp = Carbon::createFromFormat('d-m-Y', $request->expdate);
        }
//        dd($exp);
        try
        {
            \DB::beginTransaction();
            $tPokok = new TugasPokok();
//            $tPokok->hari = $request->hari;
            $tPokok->judul = $request->judul;
            $tPokok->deskripsi = $request->deskripsi;
            $tPokok->foto = $request->foto;
            $tPokok->exp_date = $exp;

            $tPokok->save();
            \DB::commit();

            return redirect('/tugaspokok/all');
        }
        catch (\QueryException $ex)
        {
            \DB::rollback();
            throw $ex;
        }
    }

    public function edit($id)
    {
//        $hari = Hari::select('id', 'nama_hari')->get();
        $tPokok =  TugasPokok::find($id);
        return view('layouts.TugasPokok.edit')
            ->with('tPokok', $tPokok);
    }

    public function update(Request $request, $id)
    {
        try
        {
            \DB::beginTransaction();
            TugasPokok::find($id)->update($request->all());
            \DB::commit();
            return redirect('/tugaspokok/all');
        }
        catch(\QueryException $ex)
        {
            \DB::rollback();
            return false;
        }
    }

    public function destroy($id)
    {
        $tPokok = TugasPokok::find($id);
        if(count($tPokok)>0)
        {
            TugasPokok::where('id','=',$id)
                ->delete();
        }
        return redirect('/tugaspokok/all');
    }
}
