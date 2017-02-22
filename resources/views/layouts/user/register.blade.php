@extends('app')

@section('content')
<head>
    <title>Register User</title>
    <style type="text/css">
        body {
            text-align: left;
            font-size: 14px;
            font-family: "Lucida Grande", Helvetica, Arial, Verdana, sans-serif;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register</div>
                    <div class="panel-body">
                        @if(count($errors)>0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="form-horizontal" method="POST" action="{{url('auth/registeruser')}}">

                            <input name="_token" type="hidden" value="{{csrf_token()}}">

                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    Nama
                                </label>
                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    Username
                                </label>
                                <div class="col-md-6">
                                    <input type="text" name="username" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    Email
                                </label>
                                <div class="col-md-6">
                                    <input type="email" name="email" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    Password
                                </label>
                                <div class="col-md-6">
                                    <input type="password" name="password" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    Konfirmasi Password
                                </label>
                                <div class="col-md-6">
                                    <input type="password" name="password_conf" id="password_conf" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    Nomor handphone
                                </label>
                                <div class="col-md-6">
                                    <input type="text" name="phone" id="phone" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    Role
                                </label>
                                <div class="col-md-6">
                                    <select name="id_role" id="id_role" class="form-control">
                                        @foreach ($role as $send)
                                            <option value="{{$send->id_role}}">
                                                {{$send->role}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    Cabang
                                </label>
                                <div class="col-md-6">
                                    <select name="id_branch" id="id_branch" class="form-control">
                                        @foreach ($branch as $send)
                                            <option value="{{$send->id_branch}}">
                                                {{$send->branch_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i> Simpan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection