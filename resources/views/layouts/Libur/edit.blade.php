@extends('app')

@section('content')
    <head>
        <title>Edit Data Hari Libur</title>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href='{{url("css/bootstrap-material-datetimepicker.css")}}' rel='stylesheet' />
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
                    <div class="panel-heading">Edit Data Hari Libur</div>
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

                        <form method="POST" action="{{url('libur/edit/'.$libur->id)}}" class="form-horizontal">
                            <input name="_token" type="hidden" value="{{csrf_token()}}">
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="tgl_Libur">
                                    Tanggal Libur
                                </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="tgl_Libur" id="tgl_Libur" value="{{$libur->tgl_Libur}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    Keterangan
                                </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="keterangan_Libur" id="tgl_Libur">
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
@section('script')
    <script src='{{url("js/moment.js")}}'></script>
    <script src='{{url("js/bootstrap-material-datetimepicker.js")}}'></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#tgl_Libur').bootstrapMaterialDatePicker({ time : false, format: 'DD-MM-YYYY'});
        });
    </script>
@endsection