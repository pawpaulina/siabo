<?php

namespace App\Http\Controllers;

use App\Branch;
use App\BuktiTP;
use App\Image;
use App\Bukti;
use App\Eksekusi;
use App\Role;
use App\TugasPokok;
use App\User;
use App\ToDo;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Plan;
use App\Http\Controllers\Controller;
use Mockery\Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class APIController extends Controller
{
    /**Untuk Login**/
    public function authenticate(Request $request) {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 403);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        } catch (\Exception $e) {
            return $e;
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    public function getAuthData($json)
    {
        //Linux Server
        $response = explode("\n\n", $json, 2);
        if(count($response)<2)//Windows server
        {
            $response = explode("\r\n\r\n", $json, 2);
        }
        return json_decode($response[1]);
    }
    
    /**Untuk mendapatkan data user**/
    public function getUserDetails(){
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $user['role'] = Role::find($user['id_role'])->role;
        unset($user['id_role']);
        $user['branch_name'] = Branch::find($user['id_branch'])->branch_name;
        unset($user['id_branch']);

        return response()->json(compact('user'));
    }

    /**Untuk mendapatkan data user**/
    public function getPlan($id){
//        $plan = Plan::selectRaw("t_plan.id, false as allDay, CONCAT(CONCAT(store_name, ' '), store_code) as title,
//        CONCAT(CONCAT(tgl_plan_mulai, 'T'), jam_mulai) as start, CONCAT(CONCAT(tgl_plan_selesai, 'T'), jam_selesai) as end")
//            ->join('user','user.id_user','=','t_plan.id_user')
//            ->join('store','store.id','=','t_plan.id_store')
//            ->where ('user.id_user','=',$id)
//            ->get();
        try {
            if (!$user = JWTAuth::parseToken()->authenticate())
            {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $plan = Plan::selectRaw("t_plan.id, store.store_name, store.store_code, t_plan.tgl_plan_mulai, t_plan.jam_mulai,
        t_plan.tgl_plan_selesai, t_plan.jam_selesai")
            ->join('user','user.id_user','=','t_plan.id_user')
            ->join('store','store.id','=','t_plan.id_store')
            ->where ('user.id_user','=',$user['id_user'])
            ->orderby('t_plan.id')
            ->get();

        return response()->json(compact('plan'));
    }

    /***Update lokasi user ketika buka app***/
    public function updateloc(Request $request)
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        try {
            $user = User::find($user['id_user']);
            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;
            $user->save();
        } catch (Exception $e) {
            //return error
        }

        return response()->json(['success' => 'saved'], 200);
    }

    /****Detail Jadwal****/
    public function getDetailPlan($id)
    {
        try
        {
            if (!$user = JWTAuth::parseToken()->authenticate())
            {
                return response()->json(['user_not_found'], 404);
            }
        }
        catch (TokenExpiredException $e)
        {
            return response()->json(['token_expired'], $e->getStatusCode());
        }
        catch (TokenInvalidException $e)
        {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }
        catch (JWTException $e)
        {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        catch (\Exception $e)
        {
            return response()->json( ['error' => 'could_not_create_token'], 500);
        }
        $detailplan = Plan::selectRaw("t_plan.id, store.store_name, store.address as address_s, store.store_code, t_plan.tgl_plan_mulai, t_plan.jam_mulai,
        t_plan.tgl_plan_selesai, t_plan.jam_selesai, user.latitude as latitude_u, user.longitude as longitude_u, store.latitude as latitude_s, store.longitude as longitude_s, store.id as id_store")
            ->join('user','user.id_user','=','t_plan.id_user')
            ->join('store','store.id','=','t_plan.id_store')
            ->where ('t_plan.id','=',$id)
            ->first();
        return response()->json(['planDetail' => $detailplan]);
    }

    /****Ambil Data Tugas****/
    public function getTugas($id_user, $id_jadwal)
    {
        $today = Carbon::now();
        try
        {
            if (!$user = JWTAuth::parseToken()->authenticate())
            {
                return response()->json(['user_not_found'], 404);
            }
        }
        catch (TokenExpiredException $e)
        {
            return response()->json(['token_expired'], $e->getStatusCode());
        }
        catch (TokenInvalidException $e)
        {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }
        catch (JWTException $e)
        {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        catch (\Exception $e)
        {
            return response()->json( ['error' => 'could_not_create_token'], 500);
        }
        $todo = ToDo::select('to_do.id', 'judul_tugas', 'deskripsi_tugas', 'keterangan', 'to_do.id_bukti')//, 'tugas_pokok.id', 'tugas_pokok.judul', 'tugas_pokok.deskripsi')
            ->join('user','to_do.id_user', '=', 'user.id_user')
            ->where('user.id_user', $id_user)
            ->where('to_do.id_plan',$id_jadwal)
            ->get();
        $tugasPokok = TugasPokok::select('id', 'judul', 'deskripsi', 'foto', 'exp_date')
            ->where('exp_date', '>=', $today)
            ->orWhere('exp_date',NULL)
            ->get();
//        dd($tugasPokok);
        return response()->json(['todoDetail' => $todo, 'tugasPokok' => $tugasPokok]);
    }

    /****Ambil Detail Tugas****/
    public function getDetailTugas($id_user, $id_jadwal, $id_todo)
    {
        try
        {
            if (!$user = JWTAuth::parseToken()->authenticate())
            {
                return response()->json(['user_not_found'], 404);
            }
        }
        catch (TokenExpiredException $e)
        {
            return response()->json(['token_expired'], $e->getStatusCode());
        }
        catch (TokenInvalidException $e)
        {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }
        catch (JWTException $e)
        {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        catch (\Exception $e)
        {
            return response()->json( ['error' => 'could_not_create_token'], 500);
        }
        $todo = ToDo::select('to_do.id', 'judul_tugas', 'deskripsi_tugas', 'keterangan')
            ->join('user','to_do.id_user', '=', 'user.id_user')
            ->where('user.id_user', $id_user)
            ->where('to_do.id_plan', $id_jadwal)
            ->where('to_do.id', $id_todo)
            ->get();
        return response()->json(['todoDetail' => $todo]);
    }

    public function checkIn(Request $request, $id_user)
    {
        $tglCheckin = Carbon::now();
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        try
        {
            $eks = new Eksekusi();
            $eks->id_store = $request->id_store;
            $eks->tgl_checkin = $tglCheckin;
            $eks->id_user = $id_user;
            $eks->id_plan = $request->id_plan;
            $eks->save();
        } catch (Exception $e)
        {
            //return error
        }

        return response()->json(['success' => 'saved'], 200);
    }

    //cek udah eksekusi atau blm
    public function cekEksekusi($id_plan)
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $cek = Eksekusi::select("id_plan", "t_eks.created_at")
            ->join('t_plan','t_plan.id','=','t_eks.id_plan')
            ->where ('t_plan.id','=',$id_plan)
            ->first();
//        dd($id_plan);
        if($cek!=null)
        {
            return response()->json($cek);
        }
        else
        {
            return response()->json(['id_plan'=>0]);
        }
    }

    //untuk upload gambar + kerjain tugas biasa
    public function simpanGambar(Request $request, $id_plan)
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        try
        {
            \DB::beginTransaction();

            $photo = $this->returnBlankIfNull($request->gambar);
            $idtodo = $this->returnBlankIfNull($request->id_todo);
            $iduser = $this->returnBlankIfNull($request->id_user);
            $tgl = Carbon::now();
            if ($photo == null) {
                return response()->json(['Foto belum dipilih'], 400);
            }
            try {
                if ($photo == null) {
                    //skip
                } else {
                    $photoArray = json_decode(utf8_encode($request->gambar), TRUE);
                    if ($photoArray == null) {
                        return response()->json(['Foto belum dipilih1'], 400);
                    } else if ($photoArray != null) {
                        //upload gambar pertama
                        try {
                            $png_url = "".$iduser." eksekusi ".$idtodo."-1".".png";
                            $path = public_path() . "/images/" . $idtodo . $png_url;
                            $url = str_replace('https://', 'http://', url()) . "/images/" . $idtodo . "/" . $png_url;
                            if ($this->returnBlankIfNull($photoArray['foto0']) != "") {
                                $data = base64_decode(preg_replace('#^data:images/\w+;base64,#i', '', $photoArray['foto0']));
                                if (file_exists($path)) {
                                    Image::destroy(Image::where('url', $url)->pluck('id'));
                                    unlink($path);
                                }
                                file_put_contents($path, $data);
                                $img1 = new Image();//Image::firstOrNew(['url' => $url]);
                                $img1->url = $url;
                                $img1->save();
                            } else {
                                Image::destroy(Image::where('url', $url)->pluck('id'));
                            }
                        } catch (Exception $ex) {
                            \DB::rollBack();
                            return response()->json(['error'=>'save_failed'], 500);
                        }
                        $img = Image::where('url', 'like', '%https:%')->get();
                        foreach ($img as $row) {
                            $ig = Image::find($row->id);
                            $ig->url = str_replace('https', 'http', $ig->url);
                            $ig->save();
                        };
                    }
//                    dd($img1);
                    $bukti = new Bukti;
                    $bukti->gambar = $img1->id;

                    $bukti->keterangan = $request->keterangan;
                    $bukti->save();
                }
            }
            catch (Exception $e)
            {
                //return error
                \DB::rollBack();
                return response()->json(['error'=>'save_failed'], 500);
            }
            $todo = ToDo::findorfail($request->id_todo);
            $todo->id_bukti = $bukti->id;
            $todo->save();

            \DB::commit();
            return response()->json(['success'=>'data_saved'], 201);
        }
        catch(\QueryException $ex)
        {
            \DB::rollBack();
            return response()->json(['error'=>'save_failed'], 500);
        }
    }

    public function submitTP(Request $request, $id_plan)
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        try
        {
            \DB::beginTransaction();

            $photo = $this->returnBlankIfNull($request->gambar);
            $idtp = $this->returnBlankIfNull($request->id_tp);
            $iduser = $this->returnBlankIfNull($request->id_user);
            $iduser = $this->returnBlankIfNull($request->id_user);
            $tgl = Carbon::now();
            if ($photo == null) {
                $bukti = new BuktiTP;

                $bukti->id_plan = $request->id_plan;
                $bukti->id_tp = $request->id_tp;
                $bukti->keterangan = $request->keterangan;
                $bukti->save();
            }
            try {
                if ($photo == null) {
                    //skip
                } else {
                    $photoArray = json_decode(utf8_encode($request->gambar), TRUE);
                    if ($photoArray == null) {
                        return response()->json(['Foto belum dipilih1'], 400);
                    } else if ($photoArray != null) {
                        try {
                            $png_url = "".$iduser." eksekusi ".$idtp."-1".".png";
                            $path = public_path() . "/images/" . $idtp . $png_url;
                            $url = str_replace('https://', 'http://', url()) . "/images/" . $idtp . "/" . $png_url;
                            if ($this->returnBlankIfNull($photoArray['foto0']) != "") {
                                $data = base64_decode(preg_replace('#^data:images/\w+;base64,#i', '', $photoArray['foto0']));
                                if (file_exists($path)) {
                                    Image::destroy(Image::where('url', $url)->pluck('id'));
                                    unlink($path);
                                }
                                file_put_contents($path, $data);
                                $img1 = new Image();//Image::firstOrNew(['url' => $url]);
                                $img1->url = $url;
                                $img1->save();
                            } else {
                                Image::destroy(Image::where('url', $url)->pluck('id'));
                            }
                        } catch (Exception $ex) {
                            \DB::rollBack();
                            return response()->json(['error'=>'save_failed'], 500);
                        }
                        $img = Image::where('url', 'like', '%https:%')->get();
                        foreach ($img as $row) {
                            $ig = Image::find($row->id);
                            $ig->url = str_replace('https', 'http', $ig->url);
                            $ig->save();
                        };
                    }
                    $bukti = new BuktiTP;
                    $bukti->gambar = $img1->id;

                    $bukti->id_plan = $id_plan;
                    $bukti->id_tp = $request->id_tp;
                    $bukti->keterangan = $request->keterangan;
                    $bukti->save();
                }
            }
            catch (Exception $e)
            {
                //return error
                \DB::rollBack();
                return response()->json(['error'=>'save_failed'], 500);
            }

            \DB::commit();
            return response()->json(['success'=>'data_saved'], 201);
        }
        catch(\QueryException $ex)
        {
            \DB::rollBack();
            return response()->json(['error'=>'save_failed'], 500);
        }
    }

    public function returnBlankIfNull ($value){
        if($value != null && $value != ""){
            return $value;
        }else{
            return "";
        }
    }

    //cek udah submit blm
    public function cekSubmit($idtodo)
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $cek = ToDo::select("bukti.id", "bukti.created_at")
            ->join('bukti','bukti.id','=','to_do.id_bukti')
            ->where ('to_do.id','=',$idtodo)
            ->first();
        if($cek!=null)
        {
            return response()->json($cek);
        }
        else
        {
            return response()->json(['id'=>0]);
        }
    }

    public function cekSubmitTP($idtodo)
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $cektp = TugasPokok::select("bukti_tp.id", "bukti_tp.created_at", "tugas_pokok.foto")
            ->join('bukti_tp','bukti_tp.id_tp','=','tugas_pokok.id')
            ->where ('tugas_pokok.id','=',$idtodo)
            ->first();
        if($cektp!=null)
        {
            return response()->json($cektp);
        }
        else
        {
            return response()->json(['id'=>0]);
        }
    }
}
