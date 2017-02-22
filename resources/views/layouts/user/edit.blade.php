@extends('app')

@section('content')
    <head>
        <title>Edit Data User</title>
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
                        <div class="panel-heading">
                            Ubah Data User
                        </div>
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

                            <form method="POST" class="form-horizontal" action="{{url('user/edituser/'.$user->id_user)}}">
                                <input name="_token" type="hidden" value="{{csrf_token()}}">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        Nama
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="name" id="name" value="{{$user->name}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        Nomor Handphone
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="phone" id="phone" value="{{$user->phone}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        Email
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="email" id="email" value="{{$user->email}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        Cabang
                                    </label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="id_branch" id="id_branch" >
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
                                            <i class="fa fa-check-circle" aria-hidden="true"></i> Simpan
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