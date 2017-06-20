<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/**********************API**********************/
//Cek udah submit tugas blm
Route::GET('api/eks/ceksubmit/{idtodo}', 'APIController@cekSubmit');
Route::GET('api/eks/ceksubmitTP/{idtodo}', 'APIController@cekSubmitTP');

//Submit tugas
Route::POST('api/eks/submitTugas/{id_plan}','APIController@simpanGambar'); //tgs biasa
Route::POST('api/eks/submitTP/{id_plan}','APIController@submitTP'); //tgs pokok

//Cek udah check in blm
Route::GET('api/eks/cekcheckin/{id_plan}', 'APIController@cekEksekusi');

//Update check in
Route::POST('api/eks/checkin/{id_user}', 'APIController@checkIn');

//Update latitude dan longitude
Route::POST('api/user/updateloc','APIController@updateloc');

//Ambil Detail Jadwal
Route::GET('api/kalender/plan/detail/{id_plan}', 'APIController@getDetailPlan');

//Ambil Tugas di Recycler view
Route::GET('api/todo/detail/{id_user}/{id_plan}', 'APIController@getTugas');

//Ambil Detail per tugas
Route::GET('api/todo/detail/{id_user}/{id_plan}/{id_todo}', 'APIController@getDetailTugas');

//Login
Route::POST('api/authentikasi','APIController@authenticate');

//Tampil kalender (plan)
Route::GET('api/kalender/{id_user}','APIController@getPlan');

/**********************Login**********************/
Route::GET('/', 'KalenderController@index');

//Route::GET('/', function (){ return view('auth/login'); });

Route::controllers(['password' => 'Auth\PasswordController',]);

/**********************User**********************/
//menampilkan Data seorang User
Route::GET('user/userdetails', 'APIController@getUserDetails');

//menampilkan Data semua User
Route::resource('/user', 'UserController');
Route::GET('/user/tampilAtasan','UserController@show');

//Login User
Route::POST('auth/login', 'Auth\AuthController@getLogin');
Route::GET('auth/login', 'Auth\AuthController@postLogin');
Route::GET('auth/logout', 'Auth\AuthController@getLogout');

//Register User
Route::GET('auth/registeruser', 'Auth\AuthController@getRegister');
Route::POST('auth/registeruser', 'Auth\AuthController@postRegister');

//Edit User
Route::GET('user/edituser/{id_user}', 'UserController@edit');
Route::POST('user/edituser/{id_user}', 'UserController@update');

//Delete User
Route::GET('user/deleteuser/{id_user}', 'UserController@destroy');


/**********************Branch**********************/
//Tambah Data Cabang Baru
Route::GET('/branch/tambah','BranchController@create');
Route::POST('/branch/tambah','BranchController@save');

//menampilkan Data semua Cabang
Route::resource('/branch', 'BranchController@index');

//Edit Cabang
Route::GET('branch/editbranch/{id_branch}', 'BranchController@edit');
Route::POST('branch/editbranch/{id_branch}', 'BranchController@update');

//Delete Branch
Route::GET('branch/deletebranch/{id_branch}', 'BranchController@destroy');

/**********************Role**********************/
//Tambah Data Role Baru
Route::GET('/role/tambah','RoleController@create');
Route::POST('/role/tambah','RoleController@save');

//menampilkan Data semua Role
Route::resource('/role', 'RoleController@index');

//Edit Role
Route::GET('role/editrole/{id_role}', 'RoleController@edit');
Route::POST('role/editrole/{id_role}', 'RoleController@update');

//Delete Role
Route::GET('role/deleterole/{id_role}', 'RoleController@destroy');

/**********************Store**********************/
//Tambah Data Store Baru
Route::GET('/store/tambah','StoreController@create');
Route::POST('/store/tambah','StoreController@save');

//menampilkan Data semua Store
Route::resource('/store', 'StoreController@index');

//Edit Store
Route::GET('store/editstore/{id}', 'StoreController@edit');
Route::POST('store/editstore/{id}', 'StoreController@update');

//Delete Store
Route::GET('store/deletestore/{id}', 'StoreController@destroy');

/**********************Kalender**********************/

Route::GET('/kalender/getstore', 'KalenderController@getStore');

Route::GET('/kalender/plan/edit/drag', 'KalenderController@dragPlanDate');

Route::GET('/kalender/plan/edit/resize', 'KalenderController@resizePlanTime');

Route::GET('/kalender/plan/{id_user}', 'KalenderController@getAllPlans');

Route::GET('/kalender/plan/edit/{id_user}/{id_jadwal}', 'KalenderController@edit');
Route::POST('/kalender/plan/edit/{id_user}/{id_jadwal}', 'KalenderController@update');

Route::GET('/kalender/plan/delete/{id_user}/{id_plan}','KalenderController@destroy');

Route::GET('/kalender/{id_user}', 'KalenderController@index');
Route::POST('/kalender/{id_user}', 'KalenderController@createplan');

/**********************To Do**********************/
Route::GET('/todo/get','ToDoController@getTodo');
Route::GET('/todo/{id_user}', 'ToDoController@index');

Route::GET('/todo/delete/{id}', 'ToDoController@delTodo');

/**********************Tugas Pokok**********************/
Route::GET('/tugaspokok/all', 'TugasPokokController@index');

//Tambah Tugas Pokok
Route::GET('/tugaspokok/tambah','TugasPokokController@create');
Route::POST('/tugaspokok/tambah','TugasPokokController@store');

//Edit Tugas Pokok
Route::GET('tugaspokok/editTP/{id}', 'TugasPokokController@edit');
Route::POST('tugaspokok/editTP/{id}', 'TugasPokokController@update');

//Delete Tugas Pokok
Route::GET('tugaspokok/deleteTP/{id}', 'TugasPokokController@destroy');

Route::GET('/tugaspokok/getTP','TugasPokokController@getTP');