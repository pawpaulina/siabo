@extends('app')

@section('script')
    <script src='{{url("js/moment.js")}}'></script>
    <script src='{{url("js/bootstrap-material-datetimepicker.js")}}'></script>
    <script type="text/javascript">
        $(document).ready(function()
        {
            var add_todo = $(".add_field");
            var wrapper = $(".field");

            $('#date').bootstrapMaterialDatePicker({ date: true, time: false, format: 'DD-MM-YYYY'});
            $('#timestart')
                    .bootstrapMaterialDatePicker({ date : false, format: 'HH:mm' })
                    .on('change', function(e, date)
                    {
                        $('#timeend')
                                .bootstrapMaterialDatePicker('setMinDate', date);
                    });
            $('#timeend').bootstrapMaterialDatePicker({ date: false, format: 'HH:mm'});
            $(add_todo).click(function(e)
            {
                e.preventDefault();
                $(wrapper).append('<div class = "panel panel-default" style="text-align:center"> <div class="panel-body"><input type="hidden" name="id[]" value="0"><div class="form-group"> <label class="col-md-4 control-label"> Judul Tugas : </label><div class="col-md-6"><input type="text" class="form-control" name="judul[]"></div></div> <div class="form-group"> <label class="col-md-4 control-label"> Deskripsi Tugas : </label><div class="col-md-6"> <input type="text" class="form-control" name="deskripsi[]"></div></div><a class="btn btn-danger remove_field" href="#modaldelete" data-toogle="modal"><i class="fa fa-trash" aria-hidden="true"></i> Hapus Tugas </a> </div> ')
            });
            $(wrapper).on("click",".remove_field", function(e) //user click on remove field
            {
                e.preventDefault();
                $(this).parent('div').parent('div').remove();
            });
            $('#modaldelete').on('show.bs.modal', function(e) {
                $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            });
        });
    </script>
@endsection
@section('content')
    <head>
        <title>Edit Tugas</title>
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
                        <div class="panel-heading fixed-panel" style="min-height:55px ; max-height:80px;">
                            <i class="fa fa-th-list" aria-hidden="true"></i> &nbsp; Edit Jadwal dan Tugas
                            <a class="btn btn-danger remove_field" data-href="{{url('/kalender/plan/delete/'.$user->id_user.'/'.$plan->id)}}" href="#modaldelete" data-toggle="modal" data-target="#modaldelete" style=" float: right;">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                                Hapus Jadwal
                            </a>
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

                            <form method="POST" class="form-horizontal" action="{{url('kalender/plan/edit/'.$user->id_user.'/'.$plan->id)}}">
                                <input name="_token" type="hidden" value="{{csrf_token()}}">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        Pilih Toko :
                                    </label>
                                    <div class="col-md-6">
                                        <select name="store_code" id="store_code" class="form-control">
                                            @foreach ($stores as $send)
                                                <option value="{{$send->id}}">
                                                    {{$send->store_name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        Tanggal :
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="tgl_mulai" id="tgl_mulai"  placeholder="Tanggal" value="{{$plan->tgl_plan_mulai}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        Jam mulai :
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="timestart" id="timestart" placeholder="Jam mulai" value="{{$plan->jam_mulai}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        Jam selesai :
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="timeend" id="timeend" placeholder="Jam selesai" value="{{$plan->jam_selesai}}">
                                    </div>
                                </div>
                                @foreach ($todo as $row)
                                    <div class='panel panel-default' style="text-align:center">
                                        <div class='panel-body'>
                                            <input value = "{{ $row->id }}" type="hidden" name="id[]">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">
                                                    Judul Tugas :
                                                </label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="judul[]" value="{{ $row->judul_tugas }}" >
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">
                                                    Deskripsi Tugas :
                                                </label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="deskripsi[]" value="{{ $row->deskripsi_tugas  }}">
                                                </div>
                                            </div>
                                            <a class="btn btn-danger remove_field" data-href="{{url('/todo/delete/'. $row->id)}}" href="#modaldelete" data-toggle="modal" data-target="#modaldelete">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                Hapus Tugas
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                                <div id="modaldelete" class="modal col-md-6 col-md-offset-3" role="dialog" style="text-align:center" >
                                    <div class="modal-content">
                                        <span data-dismiss="modal" class="close">&times; </span>
                                        Anda yakin ingin menghapus data ini?
                                        <p>
                                            <a class="btn btn-danger btn-ok" href="#" type="submit" id="yes">
                                                Yes
                                            </a>
                                            <button class="btn btn-default" type="submit" id="no" data-dismiss="modal">
                                                No
                                            </button>
                                        </p>
                                    </div>
                                </div>
                                <p>
                                    <div class="field">
                                    </div>
                                </p>
                                <div class="form-group" style="text-align:center">
                                    <div style="text-align: center" >
                                        <a type="submit" class="btn btn-primary add_field">
                                            <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Tugas
                                        </a>
                                        <button type="submit" class="btn btn-success" id="simpan">
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