<?php

namespace App\Http\Controllers;

use App\Libur;
use App\TugasPokok;
use Illuminate\Http\Request;
use App\User;
use App\Store;
use App\Branch;
use App\Plan;
use App\ToDo;
use App\Eksekusi;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KalenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function index($id_user)
//    {
//		$users = user::find($id_user);
//        if ($users!=null){
//            // $branch = user::select('user.name','branch.branch_name') // query buat select nama user, sama nama cabang (user itu tabel 1), send itu variabel buat nampung output dari querynya
//            // ->join ('branch','branch.id_branch','=','user.id_branch') // bacanya select u.name,b.branch_name from branch (parameter pertama), parameter ke 2 sm ke 3 itu yg dibandingin (kaya on/using)
//            // ->where ('user.id_user','=',$id_user) //where nya dari id_user tabel user dibandingin sm inputannya
//            // ->first(); // buat ambil datanya yg diambil yg pertama, gak pake ->get(); karna dia masih di dlm array
//
//            $branch = branch::where('branch.id_branch', $users->id_branch)->first();
//
//            $stores=store::select('store.store_code', 'store.store_name', 'store.id')
//            ->join('branch', 'store.id_branch', '=', 'branch.id_branch')
//            ->where('store.id_branch', '=', $users->id_branch)
//            ->get();
//        }
//
//       // dd($stores); //buat debug output dari variabelnya
//        return view('layouts.kalender.index')
//                ->with('branch',$branch)
//                ->with('stores',$stores)
//                ->with('user', $users); //yg dibawa ke index.blade output dari branch,stores, sama usernya
//    }
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllSub(&$bawahan, $id){
        $bawahanLangsung = User::find($id)->subordinates()->getRelatedIds()->toArray();
        foreach ($bawahanLangsung as $bL){
            $this->getAllSub($bawahan, $bL);
            array_push($bawahan, $bL);
        }
    }

    public function index(Request $request, $id_user = null)
    {
        if($id_user == null)
        {
            $id_user = Auth::user()->id_user;
        }
        $users = user::find($id_user);
        $branch = branch::where('branch.id_branch', $users->id_branch)->first();
        $stores = store::select('store.store_code', 'store.store_name', 'store.id')
            ->join('branch', 'store.id_branch', '=', 'branch.id_branch')
            ->where('store.id_branch', '=', $users->id_branch)
            ->get();
        $bawahan = Array();
        $this->getAllSub($bawahan, $id_user);
        return view('layouts.kalender.index')
            ->with('alluser', User::WhereIn('id_user', $bawahan)->get())
            ->with('branch', $branch)
            ->with('stores', $stores)
            ->with('user', $users);
    }
    
    public function createplan(Request $request)
    {
        $tanggal_mulai = Carbon::createFromFormat('d/m/Y', $request->tgl_mulai);
        $tanggal_selesai = Carbon::createFromFormat('d/m/Y', $request->tgl_selesai);

        $durasi = $tanggal_selesai->diffInDays($tanggal_mulai);

        for( $i =0 ; $i <= $durasi ; $i++ )
        {
            $plan = new Plan;
            $plan->id_store = $request->store_code; //plan->nama kolom, request->value dri index.blade
            $plan->id_user = $request->id_user;
            $plan->tgl_plan_mulai = $tanggal_mulai;
            $plan->tgl_plan_selesai = $tanggal_mulai;
            $plan->jam_mulai = $request->timestart;
            $plan->jam_selesai= $request->timeend;

            $plan->save();

            $idplan = $plan->id;
            //loop tiap todo
            foreach($request->judul_tugas as $index => $row)
            {
                if($row!=null)
                {
                    //validasi jgn ada yg kosong
                    //insert
                    $todo = new ToDo;
                    $todo->id_plan = $idplan;
                    $todo->judul_tugas = $row;
                    $todo->deskripsi_tugas = $request->deskripsi_tugas[$index];
                    $todo->keterangan = $request->keterangan;
                    $todo->id_user = $request->id_user;

                    $todo->save();

                    $tanggal_mulai = $tanggal_mulai->addDay(1);
                }
            }
        }
        return \Redirect::back();
    }

    /*public function getPlans($id)
    {
        return Plan::selectRaw("t_plan.id, false as allDay, CONCAT(CONCAT(store_name, ' '), store_code) as title, 
        CONCAT(CONCAT(tgl_plan_mulai, 'T'), jam_mulai) as start, CONCAT(CONCAT(tgl_plan_selesai, 'T'), jam_selesai) as end")
        ->join('user','user.id_user','=','t_plan.id_user')
        ->join('store','store.id','=','t_plan.id_store')
        ->where ('user.id_user','=',$id)
        ->get();
    }*/

    public function generateColor($id)
    {
        return "#1" . substr(md5($id), 0, 1) . substr(md5($id), 13, 2) . substr(md5($id), 26, 2);
    }

    public function getAllPlans($id)
    {
        $bawahan = Array();
        $this->getAllSub($bawahan, Auth::user()->id_user);
        $plan = Plan::Distinct()
            ->SelectRaw("t_plan.id, false as allDay, CONCAT(CONCAT(store_name, ' - '), name) as title,
        CONCAT(CONCAT(tgl_plan_mulai, 'T'), jam_mulai) as start, CONCAT(CONCAT(tgl_plan_selesai, 'T'), jam_selesai) as end, user.name, user.id_user")
            ->join('user','user.id_user','=','t_plan.id_user')
            ->join('store','store.id','=','t_plan.id_store')
            ->whereIn('user.id_user', $bawahan)
            ->get();

        foreach($plan as $row)
        {
            $row->color = $this->generateColor($row->id_user);
        }

        return $plan;
    }

    public function getLibur(Request $request)
    {
        $libur = Libur::SelectRaw("id, true as allDay, keterangan_Libur as title, tgl_Libur as start, tgl_Libur as end")
        ->get();
        return $libur;
    }

    public function getStore(Request $request)
    {
        $user = $request->userid;
        $store = Store::select('id' , 'store_code', 'store_name')
        ->join('branch','store.id_branch','=','branch.id_branch')
        ->join('user','branch.id_branch','=','user.id_branch')
        ->where('user.id_user','=',$user)
        ->get();

        $storelist = "";
        foreach ($store as $row)
        {
            $storelist .="<option value='". $row->id ."'>". $row->store_name ."</option>";
        }
        return $storelist;
    }

    public function dragPlanDate(Request $request)
    {
        $tanggalBaruMulai = Carbon::createFromFormat('d/m/Y', $request->tgl_mul);
        $tanggalBaruSelesai = Carbon::createFromFormat('d/m/Y', $request->tgl_sel);
        try
        {
            $plan = Plan::find($request->plan_id);
            $plan->tgl_plan_mulai = $tanggalBaruMulai;
            $plan->tgl_plan_selesai = $tanggalBaruSelesai;
            $plan->jam_mulai = $request->start;
            $plan->jam_selesai = $request->end;
            $plan->save();
            return 1;
        }
        catch(\QueryException $ex)
        {
            return 0;
        }
    }
    
    public function resizePlanTime(Request $request)
    {
        $tanggalBaruMulai = Carbon::createFromFormat('d/m/Y', $request->tgl_mul);
        $tanggalBaruSelesai = Carbon::createFromFormat('d/m/Y', $request->tgl_sel);
        try
        {
            $plan = Plan::find($request->plan_id);
            $plan->tgl_plan_mulai = $tanggalBaruMulai;
            $plan->tgl_plan_selesai = $tanggalBaruSelesai;
            $plan->jam_mulai = $request->start;
            $plan->jam_selesai = $request->end;
            $plan->save();
            return 1;
        }
        catch(\QueryException $ex)
        {
            return 0;
        }
    }

    public function createeksekusi(Request $request)
    {
        $tanggal = Carbon::createFromFormat('d/m/Y', $request->tgl);

        $eks = new Eksekusi;
        $eks->id_store = $request->store_code; //eks->nama kolom, request->value dri index.blade
        $eks->id_user = $request->id_user;
        $eks->tgl_eks = $tanggal;
        $eks->jam_mulai = $request->timestart;
        $eks->jam_selesai= $request->timeend;

        $eks->save();
        return redirect("eks");
    }

    public function getEks($id)
    {
        return Eksekusi::selectRaw("CONCAT(CONCAT(store_name, ' '), store_code) as title, CONCAT(CONCAT(tgl_eks, ' '), jam_mulai) as start, CONCAT(CONCAT(tgl_eks, ' '), jam_selesai) as end")
        ->join('user','user.id_user','=','t_eks.id_user')
        ->join('store','store.id','=','t_eks.id_store')
        ->where ('user.id_user','=',$id)
        ->get();
    }


    public function show($id)
    {
        //
    }

    public function edit($id, $id_jadwal)
    {
        try
        {
            $users = User::find($id);
            if ($users!=null){
                $branch = branch::where('branch.id_branch', $users->id_branch)->first();
                $stores = store::select('store.store_code', 'store.store_name', 'store.id')
                    ->join('branch', 'store.id_branch', '=', 'branch.id_branch')
                    ->where('store.id_branch', '=', $users->id_branch)
                    ->get();
                $plan = Plan::find($id_jadwal);
                $todo = ToDo::select('to_do.id', 'judul_tugas', 'deskripsi_tugas', 'keterangan')
                    ->join('user','to_do.id_user', '=', 'user.id_user')
                    ->where('user.id_user',$id)
                    ->where('to_do.id_plan',$id_jadwal)
                    ->get();
            }
            return view ('layouts.kalender.edit')
                ->with('stores',$stores)
                ->with('branch',$branch)
                ->with('user',$users)
                ->with('plan', $plan)
                ->with('todo',$todo);
        }
        catch (\QueryException $ex)
        {
            return false;
        }

    }

    public function update(Request $request, $id_user, $idjadwal)
    {
        try
        {
            \DB::beginTransaction(); //buat kalau gagal save
            $plan = Plan::findorfail($idjadwal);
            $plan->tgl_plan_mulai = $request->tgl_mulai;
            $plan->tgl_plan_selesai = $request->tgl_mulai;
            $plan->jam_mulai = $request->timestart;
            $plan->jam_selesai = $request->timeend;
            $plan->save();

            //cek usernya
            if($id_user == $plan->id_user)
            {
                foreach ($request->id as $index => $id)
                {
                    if($id == 0)
                    {
                        $todo = new ToDo;
                    }
                    else
                    {
                        $todo = ToDo::findorfail($id);
                    }
                    $todo->id_plan = $idjadwal;
                    $todo->judul_tugas = $request->judul[$index];
                    $todo->deskripsi_tugas = $request->deskripsi[$index];
                    $todo->id_user = $id_user;

                    $todo->save();
                }
            }
        }
        catch(ModelNotFoundException $e)
        {
            \DB::rollBack();
        }
        catch(\QueryException $ex)
        {
            \DB::rollBack();
        }
        \DB::commit();
        return \Redirect::back();
    }

    public function destroy($id_user, $id_jadwal)
    {
        try
        {
            if($plan = Plan::find($id_jadwal))
            {
                $plan->delete();
            }
            return redirect('/kalender/'.$id_user);
        }
        catch (\QueryException $ex)
        {
            return false;
        }
    }
}
