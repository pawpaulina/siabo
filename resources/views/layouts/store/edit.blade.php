@extends('app')

@section('content')
    <head>
        <title>Edit Data Toko</title>
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
                            Ubah Data Toko
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

                            <form method="POST" class="form-horizontal" action="{{url('store/editstore/'.$stores->id)}}">
                                <input name="_token" type="hidden" value="{{csrf_token()}}">
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
                                    <label class="col-md-4 control-label">
                                        Nama Toko
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="store_name" id="store_name" value="{{$stores->store_name}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        Alamat
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="address" id="address" value="{{$stores->address}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                    Latitude
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="latitude" id="latitude" value="{{$stores->latitude}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        Longitude
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="longitude" id="longitude" value="{{$stores->longitude}}">
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