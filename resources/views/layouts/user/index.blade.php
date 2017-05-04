@extends('app')
@section('content')
<h1 class="col-md-12">Data User</h1>

@if ($users->count())
	<nav id="nav">
		<h3 class="col-md-2">
			<a class="btn btn-success" href="{{asset('auth/registeruser')}}">
				<i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah User
			</a>
		</h3>
	</nav>
	<div class="col-xs-12">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>ID User</th>
					<th>Username</th>
					<th>Nama</th>
					<th>Nomor HP</th>
					<th>Email</th>
					<th>Cabang</th>
					<th>Jadwal</th>
					<th>Tugas</th>
					<th>Ubah</th>
					<th>Hapus</th>
				</tr>
			</thead>

			<tbody>
				@foreach ($users as $user)
					<tr>
						<td>{{ $user->id_user }}</td>
						<td>{{ $user->username }}</td>
						<td>{{ $user->name }}</td>
						<td>{{ $user->phone }}</td>
						<td>{{ $user->email }}</td>
						<td>{{ $user->id_branch}}</td>
						<td><a class="btn btn-info" href="{{url('kalender/'. $user->id_user)}}"><i class="fa fa-calendar-check-o" aria-hidden="true"></i></a></td>
						<td><a class="btn btn-primary" href="{{url('todo/'.$user->id_user)}}"><i class="fa fa-th-list" aria-hidden="true"></i></a></td>
						<td><a class="btn btn-warning" href="{{url('user/edituser/'. $user->id_user)}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
						<td><a class="btn btn-danger" data-href="{{url('user/deleteuser/'. $user->id_user)}}" href="#modaldelete" data-toggle="modal"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div id="modaldelete" class="modal col-md-6 col-md-offset-3" role="dialog" style="text-align:center">
		<div class="modal-content">
			<span data-dismiss="modal" class="close">&times; </span>
			Anda yakin ingin menghapus data ini?
			<p>
				<a class="btn btn-danger btn-ok" href="{{url('user/deleteuser/'. $user->id_user)}}" type="submit" id="yes">
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
	Belum ada data user.
@endif

@stop