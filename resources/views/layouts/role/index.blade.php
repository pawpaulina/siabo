@extends('app')
@section('content')
    <h1 class="col-md-12">Data Role</h1>

    @if ($roles->count())
        <nav id="nav">
            <h3 class="col-md-2">
                <a class="btn btn-success" href="{{asset('role/tambah')}}">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Role
                </a>
            </h3>
        </nav>
        <div class="col-md-12">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID Role</th>
                        <th>Role</th>
                        <th>Ubah</th>
                        <th>Hapus</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->id_role }}</td>
                            <td>{{ $role->role }}</td>
                            <td><a class="btn btn-warning" href="{{url('role/editrole/'. $role->id_role)}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                            <td><a class="btn btn-danger" data-href="{{url('role/deleterole/'. $role->id_role)}}" href="#modaldelete" data-toggle="modal"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
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
                    <a class="btn btn-danger btn-ok" href="{{url('role/deleterole/'. $role->id_role)}}" type="submit" id="yes">
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
        Belum ada data role.
    @endif

@stop