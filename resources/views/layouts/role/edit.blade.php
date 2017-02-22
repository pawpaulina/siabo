@extends('app')

@section('content')
    <head>
        <title>Edit Data Role</title>
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
                            Ubah Data Role
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

                            <form method="POST" class="form-horizontal" action="{{url('role/editrole/'.$role->id_role)}}">
                                <input name="_token" type="hidden" value="{{csrf_token()}}">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        Nama Role
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="role" id="role" value="{{$role->role}}">
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