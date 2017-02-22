<?php

namespace App\Http\Controllers;

use App\ToDo;
use App\Plan;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ToDoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id_user)
    {
        $users = user::find($id_user);
        if ($users!=null){
            $todo = ToDo::select('to_do.judul_tugas', 'to_do.deskripsi_tugas', 'to_do.keterangan', 'bukti.gambar', 'user.name')
                ->leftjoin('bukti', 'to_do.id_bukti', '=', 'bukti.id')
                ->where('to_do.id_user', '=', $users->id_user)
                ->get();

            
        }else{
            $todo = null;
        }
        return view('layouts.ToDo.index')
            ->with('user',$users)
            ->with('todo',$todo);
    }

    //buat ambil data tugas
    public function getTodo(Request $request)
    {
        $id_jadwal = $request->eventid;
        $id_user = $request->userid;
        try
        {
            $listOptions = "";
            $todo = ToDo::select('judul_tugas', 'deskripsi_tugas', 'keterangan')
                ->join('user','to_do.id_user','=','user.id_user')
                ->where('user.id_user',$id_user)
                ->where('to_do.id_plan',$id_jadwal)
                ->get();
            foreach ($todo as $row)
            {
                $listOptions .= "<div class='panel panel-default'><div class='panel-heading'> ".$row->judul_tugas." </div><div class='panel-body' style='text-align:left;'>".$row->deskripsi_tugas."</div></div>";
            }
            return $listOptions;
        }
        catch (\QueryException $ex)
        {
            return false;
        }
    }

    public function delTodo($id)
    {
        $todo = ToDo::find($id);
        if(count($todo)>0)
        {
            ToDo::where('id','=',$id)
                ->delete();
        }
        return \Redirect::back();
    }
}