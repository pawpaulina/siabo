@extends('app')

@section('content')
    <head>
        <title>Ubah Tugas Pokok</title>
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
                            Ubah Tugas Pokok
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

                            <form method="POST" class="form-horizontal" action="{{url('tugaspokok/editTP/'.$tPokok->id)}}">
                                <input name="_token" type="hidden" value="{{csrf_token()}}">
                                {{--<div class="form-group">--}}
                                    {{--<label class="col-md-4 control-label">--}}
                                        {{--Pilih Hari--}}
                                    {{--</label>--}}
                                    {{--<div class="col-md-6">--}}
                                        {{--<select name="hari" id="hari" class="form-control" value="{{$tPokok->hari}}">--}}
                                            {{--@foreach ($hari as $sendRow)--}}
                                                {{--<option value="{{$sendRow->id}}">--}}
                                                    {{--{{$sendRow->nama_hari}}--}}
                                                {{--</option>--}}
                                            {{--@endforeach--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        Judul
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="judul" id="judul" value="{{$tPokok->judul}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        Deskripsi
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="deskripsi" id="deskripsi" value="{{$tPokok->deskripsi}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        Perlu Foto?
                                    </label>
                                    <div class="col-md-6">
                                        <div class="radio" value="{{$tPokok->foto}}">
                                            <label>
                                                <input type="radio" class="flat" name="foto" value="1" required=""> Ya
                                            </label>
                                            <label>
                                                <input type="radio" class="flat" name="foto" value="0" required=""> Tidak
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        Tugas Dapat Tidak Berlaku?
                                    </label>
                                    <div class="col-md-6">
                                        <div class="radio" >
                                            <label>
                                                <input type="radio" class="flat" name="exp" value="1" required="" id="expyes"> Ya
                                            </label>
                                            <label>
                                                <input type="radio" class="flat" name="exp" value="0" required="" id="expno"> Tidak
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" name="date" style="display: none">
                                    <label class="col-md-4 control-label">
                                        Tanggal Kadaluarsa
                                    </label>
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