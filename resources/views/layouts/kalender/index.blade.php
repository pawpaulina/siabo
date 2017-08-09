@extends('app')

@section('content')
<head>
  <title>Timeline Absensi</title>
	<!--FullCalendar Dependencies-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href='{{url("css/fullcalendar.css")}}' rel='stylesheet' />
	<link href='{{url("css/fullcalendar.print.css")}}' rel='stylesheet' media='print' />
	<link href='{{url("css/bootstrap-material-datetimepicker.css")}}' rel='stylesheet' />
	<style type="text/css">
		body
		{
			text-align: center;
			font-size: 14px;
			font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		}
		#calendar
		{
			width: 900px;
			margin: 0 auto;
		}
		.forceLeft {
			text-align: left;
		}
		.nopad-r {
			padding-right: 0px;
		}
		.nopad-l {
			padding-left: 0px;
		}
		.margin-r {
			margin-right: 5px;
		}
		.margin-l {
			margin-left: 5px;
		}
		.nopad {
			padding: 0px;
		}
		.topbot10{
			padding-top: 10px;
			padding-bottom: 10px;
		}
	</style>
</head>
<body>
	<header id="header">
		<div class="inner">
			<h1>Absensi</h1>
			
			{{--<nav id="nav">--}}
				{{--<h3>--}}
					{{--<a href="{{asset('/kalender/'.$user->id_user)}}">Kalender</a> |--}}
					{{--<a href="{{asset('/user')}}">User</a> |--}}
					{{--<a href="{{asset('/branch')}}">Cabang</a>--}}
				{{--</h3>--}}
			{{--</nav>--}}
		</div>
	</header>
	</br>
	<div id='calendar'>
		{{--Tampil kalender--}}
	</div>
	<div id="modaljadwal" class="modal" style="text-align: left;">
		<form action="{{url('/kalender/'.$user->id_user) }}" method="post">
			<input name="_token" type="hidden" value="{{csrf_token()}}">
			<input id="tgl_mulai" name="tgl_mulai" type="hidden">
			<input id="tgl_selesai" name="tgl_selesai" type="hidden">
			<div class="modal-content col-md-6 col-md-offset-3 ">
				<span data-dismiss="modal" class="close">&times; </span>
				<div class="form-group">
					<div class="col-md-12 topbot10">
						<label for="store_code">
							Pilih User :
						</label>
						<select name="id_user" id="id_user" class="form-control onclick" onchange="loadStore(this.value)">
							@foreach ($alluser as $sendRow)
								<option value="{{$sendRow->id_user}}">
									{{$sendRow->name}}
								</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-12">
						<label id="datestart">
						</label>
						<br/>
						<label id="dateend">
						</label>
					</div>
					<div class="col-md-12">
						<div class="form-group forceLeft nopad-l col-md-2">
							<label for="timestart">
								Jam Mulai :
							</label>
							<input type="text" id="timestart" name="timestart" class="form-control" placeholder="Time Start">
						</div>
						<div class="form-group forceLeft nopad col-md-2">
							<label for="timeend">
								Jam Selesai :
							</label>
							<input type="text" id="timeend" name="timeend" class="form-control floating-label" placeholder="Time End">
						</div>
						<div class="form-group forceLeft nopad-r col-md-8" name="store_list" id="store_list" style="display: none">
							<label for="store_code">
								Pilih Toko :
							</label>
							<select name="store_code" id="store_code" class="form-control">
								{{--Tampil data toko sesuai user--}}
							</select>
						</div>
					</div>
					<div class="col-md-12 nopad">
						<hr style="margin-top:0px"/>
						<p>
							<div class="field">
							{{--untuk button 'beri tugas' tetap dibawah input field--}}
							</div>
						</p>
						<hr style="margin-bottom:0px"/>
					</div>
					<div class="col-md-12 topbot10">
						<a class="col-md-3 col-md-offset-3 margin-r btn btn-success add_field">
							<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Beri Tugas
						</a>
						<button type="submit" class="ol-md-3 margin-l btn btn-primary" id="buat">
							<i class="fa fa-calendar-check-o" aria-hidden="true"></i> Buat Jadwal
						</button>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div id = "modalshowtodo" class = "modal col-md-6 col-md-offset-3">
		<form action = "{{url('todo/'.$user->id_user)}}" method = "post">
			<div class = "modal-content col-md-8 col-md-offset-2">
				<span data-dismiss = "modal" class = "close">&times;</span>
				<p id = "bawahan" ></p>
				<p id = "date" ></p>
				<p id = "time" ></p>
                <label for="store_code">
                    Tugas :
                </label>
				<div class = 'todolistmodal'>
					{{--menampilkan data to do dari modal--}}
				</div>
                <label for="store_code">
                   Tugas Pokok :
                </label>
                <div class = 'listtugaspokok'>
                    {{--menampilkan data to do dari modal--}}
                </div>
				<a class = "btn btn-warning link-ubah" href="#">
					<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Ubah Jadwal
				</a>
			</div>
		</form>
	</div>
</body>
@endsection


@section('script')
	<script src='{{url("js/moment.js")}}'></script>
	<script src='{{url("js/fullcalendar.min.js")}}'></script>
    <script src='{{url ("js/locale-all.js") }}'></script>
	<script src='{{url("js/bootstrap-material-datetimepicker.js")}}'></script>
	<script type="text/javascript">

        $(document).ready(function()
        {
            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();
            var wrapper = $(".field");
            var add_todo = $(".add_field");
            var edit_field = $(".edit_field");
            var max_todo = 10; //maksimal to do user
            var x = 1; //inisial dari to do

            var calendar =
            //buat kalender
            $('#calendar')
                .fullCalendar({
                    header :
                        {
                            left : 'prev,next today',
                            center : 'title',
                            right : 'month,agendaDay'
                        },
                    defaultView : 'month',
                    selectable : true,
                    selectHelper : true,
                    editable : true,
                    allday : false,
                    eventSources: [
                        {
                            url: '{{url("/kalender/plan/".$user->id_user)}}'
                        }
                        ,{
                            url: '{{url("/kalender/libur")}}',
                            color : 'red',
                            eventOverlap : false,
                            draggable: false,
                            editable: false,
                        }
                    ],
                    eventDrop: function(event, delta, revertFunc)
                    {
                        var datestart = moment(event.start).format('DD/MM/YYYY');
                        var dateend = moment(event.end).format('DD/MM/YYYY');
                        var jammulai = moment(event.start).format('HH:mm');
                        var jamselesai = moment(event.end).format('HH:mm');
                        $.ajax({
                            method : 'GET',
                            url : '{{url('kalender/plan/edit/drag')}}',
                            data : { tgl_mul : datestart, tgl_sel : dateend, plan_id : event.id, start : jammulai, end : jamselesai}
                        });
                    },
                    eventResize : function(event, delta, revertFunc)
                    {
                        var datestart = moment(event.start).format('DD/MM/YYYY');
                        var dateend = moment(event.end).format('DD/MM/YYYY');
                        var jammulai = moment(event.start).format('HH:mm');
                        var jamselesai = moment(event.end).format('HH:mm');
                        $.ajax({
                            method : 'GET',
                            url : '{{url("kalender/plan/edit/resize")}}',
                            data : { tgl_mul : datestart, tgl_sel : dateend, plan_id : event.id, start : jammulai, end : jamselesai }
                        });
                    },
                    eventClick : function (calEvent, jsEvent, view)
                    {
                        var datestart = moment(calEvent.start).format('DD/MM/YYYY');
                        var startTime = moment(calEvent.start).format('HH:mm');
                        var endTime = moment(calEvent.end).format('HH:mm');
                        $('.todolistmodal').html("");
                        $('.listtugaspokok').html("");
                        $('#date').html(datestart);
                        $('#time').html(startTime+"-"+endTime);
                        $('#bawahan').html("Nama Bawahan : " + calEvent.name );
                        $('.link-ubah').attr("href", "{{url('/kalender/plan/edit/')}}" + "/" + calEvent.id_user + "/" + calEvent.id);
                        {{--$('.link-ubah').attr("href", "{{url('/kalender/plan/edit/'.$user->id_user)}}" + "/" + calEvent.id);--}}
                        $.ajax
                        ({
                            method : 'GET',
                            async : false,
                            url : '{{url("todo/get")}}',
                            data : { eventid : calEvent.id, userid : calEvent.id_user },
                            success : function(data)
                            {
                                $('.todolistmodal').html(data);
                            },
                        });
                        $.ajax
                        ({
                            method : 'GET',
                            async : false,
                            url : '{{url("tugaspokok/getTP")}}',
                            data : { eventid : calEvent.id, userid : calEvent.id_user },
                            success : function(data)
                            {
                                $('.listtugaspokok').html(data);
                            },
                        });
                        $('#modalshowtodo').modal('show');
                    },
                    select : function(start, end, jsEvent, view) {
                        var startTime = moment(start).format('HH:mm');
                        var endTime = moment(end).format('HH:mm');
                        var datestart = moment(start).format('DD/MM/YYYY');
                        var dateend = moment(end).format('DD/MM/YYYY');
                        //Pengecekan multiple date select
                        if (datestart == dateend) {
                            //Single date
                            if ($('#calendar').fullCalendar('getView').name != 'month') {
                                $('#modaljadwal').modal('show');
                                $('#tgl_mulai').val(start.getDate() + "/" + (start.getMonth() + 1) + "/" + start.getFullYear());
                                $('#tgl_selesai').val(start.getDate() + "/" + (start.getMonth() + 1) + "/" + start.getFullYear());
                                $('#datestart').html("Tanggal Mulai: " + datestart);//start.getDate() + "/" + (start.getMonth() + 1) + "/" + start.getFullYear());
                                $('#dateend').html("Tanggal Selesai: " + datestart);//end.getDate() + "/" + (end.getMonth() + 1) + "/" + end.getFullYear());
                                $('#timestart').val(startTime);
                                $('#timeend').val(endTime);
                                $('#calendar').fullCalendar('changeView', 'agendaDay');
                            }
                            else {
                                $('#calendar').fullCalendar('changeView', 'agendaDay');
                                $('#calendar').fullCalendar('gotoDate', start);
                            }
                        }
                        else {
                            //Multiple date
                            $('#modaljadwal').modal('show');
                            $('#tgl_mulai').val(moment(event.start).format('DD/MM/YYYY'));//start.getDate() + "/" + (start.getMonth() + 1) + "/" + start.getFullYear());
                            $('#tgl_selesai').val(moment(event.end).format('DD/MM/YYYY'));//end.getDate() + "/" + (end.getMonth() + 1) + "/" + end.getFullYear());
                            $('#datestart').html("Tanggal Mulai: " + datestart);//start.getDate() + "/" + (start.getMonth() + 1) + "/" + start.getFullYear());
                            $('#dateend').html("Tanggal Selesai: " + dateend);//end.getDate() + "/" + (end.getMonth() + 1) + "/" + end.getFullYear());
                            $('#timestart').val(startTime);
                            $('#timeend').val(endTime);
                        }
                    }
                });
            //buat jam
            $('#timestart')
                .bootstrapMaterialDatePicker({ date : false, format: 'HH:mm' })
                .on('change', function(e, date)
                {
                    $('#timeend')
                        .bootstrapMaterialDatePicker('setMinDate', date);
                });
            $('#timeend').bootstrapMaterialDatePicker({ date: false, format: 'HH:mm'});
            //tambah tugas per user
            $(add_todo).click(function(e)
            {
                e.preventDefault();
                if(x < max_todo)
                {
                    $(wrapper).append(' <div class="panel panel-default"> <div class="panel-body"><div class="col-md-12"><span  style="float:left">Tugas ke ' + x + ' </span></div><div class="col-md-12"><div class="form-control-wrapper"><div class="form-group"><label for="judul_tugas' + x + '"> Judul Tugas : </label><input type="text" class="judul_tugas form-control" id="judul_tugas' + x + '" name="judul_tugas[]"></div><div class="form-group"><label for="deskripsi_tugas' + x + '">Deskripsi Tugas : </label><input type="text" class="deskripsi_tugas form-control" id="deskripsi_tugas' + x + '" name="deskripsi_tugas[]"></div></div></div><a class="btn btn-danger remove_field" style="float:right"><i class="fa fa-trash" aria-hidden="true"></i> Hapus Tugas</a></div></div> ')
                    x++;
                }
            });
            $(wrapper).on("click",".remove_field", function(e) //user click on remove field
            {
                e.preventDefault();
                $(this).parent('div').parent('div').remove();
                x--;
            })
        });

        function loadStore(id)
        {
//		var temp = $('#id_user').val();
            $('#store_list').hide();
            $.ajax
            ({
                type : 'GET',
                url : '{{url("/kalender/getstore")}}',
                data : { userid : id },
                success : function(data)
                {
                    document.getElementById("store_code").innerHTML = data;
                    $('#store_code').html(data);
                    $('#store_list').show();
                },
                error : function(xhr, ajaxOptions, thrownError)
                {
                    alert(xhr);
                    alert(thrownError);
                }
            });
        }
	</script>
@endsection