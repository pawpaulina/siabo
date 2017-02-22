<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Eksekusi;
use App\Role;
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
            ->where('to_do.id_plan',$id_jadwal)
            ->get();
        return response()->json(['todoDetail' => $todo]);
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

    public function checkIn(Request $request)
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
            $eks->id_user = $request->id_user;
            $eks->id_todo = $request->id_todo;
            $eks->save();
        } catch (Exception $e)
        {
            //return error
        }

        return response()->json(['success' => 'saved'], 200);
    }
}
