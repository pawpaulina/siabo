@extends('app')
@section('script')
	<script src='{{url("js/moment.js")}}'></script>
	<script src='{{url("js/fullcalendar.min.js")}}'></script>
	<script src='{{url("js/bootstrap-material-datetimepicker.js")}}'></script>
	<script type="text/javascript">

		$(document).ready(function()
		{
			$('#modaldelete').on('show.bs.modal', function(e) {
				$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
			});
		});
	</script>
@endsection
@section('content')
<h1 class="col-md-12">Data Cabang</h1>

@if ($branches->count())

	<nav id="nav">
		<h3 class="col-md-2">
			<a class="btn btn-success" href="{{asset('branch/tambah')}}">
				<i class="fa fa-plus-circle" aria-hidden="true"></i> Cabang Baru
			</a>
		</h3>
	</nav>
	<div class="col-md-12">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>ID Cabang</th>
					<th>Nama Cabang</th>
					<th>Ubah</th>
					<th>Hapus</th>
				</tr>
			</thead>

			<tbody>
				@foreach ($branches as $branch)
					<tr>
						<td>{{ $branch->id_branch }}</td>
						<td>{{ $branch->branch_name }}</td>
						<td><a class="btn btn-warning" href="{{url('branch/editbranch/'. $branch->id_branch)}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
						<td><a class="btn btn-danger" data-href="{{url('branch/deletebranch/'. $branch->id_branch)}}" href="#modaldelete" data-toggle="modal"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
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
	<div class="center">
		<ul class="pagination">
			<tr>
				<td></td>
			</tr>
		</ul>
	</div>
@else
	Belum ada data cabang.
@endif

@stop
