@extends('app')
@section('content')
    <h1 class="col-md-12">Data Toko</h1>

    @if ($store->count())
        <nav id="nav">
            <h3 class="col-md-2">
                <a class="btn btn-success" href="{{asset('store/tambah')}}">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Toko Baru
                </a>
            </h3>
        </nav>
        <div class="col-md-12">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nama Cabang</th>
                        <th>Kode Toko</th>
                        <th>Nama Toko</th>
                        <th>Alamat</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Ubah</th>
                        <th>Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($store as $stores)
                        <tr>
                            <td>{{ $stores->branch_name }}</td>
                            <td>{{ $stores->store_code }}</td>
                            <td>{{ $stores->store_name }}</td>
                            <td>{{ $stores->address }}</td>
                            <td>{{ $stores->latitude }}</td>
                            <td>{{ $stores->longitude }}</td>
                            <td> <a class="btn btn-warning" href="{{url('store/editstore/'. $stores->id)}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> </td>
                            <td> <a class="btn btn-danger" data-href="{{url('store/deletestore/'. $stores->id)}}" href="#modaldelete" data-toggle="modal"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </td>
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
                    <a class="btn btn-danger btn-ok" href="{{url('store/deletestore/'. $stores->id)}}" type="submit" id="yes">
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
        Belum ada data toko.
    @endif
@stop
