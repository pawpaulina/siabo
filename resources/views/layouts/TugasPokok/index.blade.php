@extends('app')
@section('content')
    <h1 class="col-md-12">Tugas Pokok</h1>
    <nav id="nav">
        <h3 class="col-md-2">
            <a class="btn btn-success" href="{{asset('tugaspokok/tambah')}}">
                <i class="fa fa-plus-circle" aria-hidden="true"></i> Tugas Pokok Baru
            </a>
        </h3>
    </nav>
    @if ($tugasPokok->count())
        <div class="col-md-12">
            <table class="table table-striped table-bordered" width="100%">
                <thead>
                <tr>
                    {{--<th>Hari</th>--}}
                    <th>Judul Tugas</th>
                    <th>Deskripsi Tugas</th>
                    <th>Tanggal Tugas Expired</th>
                    <th>Ubah</th>
                    <th>Hapus</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($tugasPokok as $td)
                    <tr>
                        {{--<td>{{ $td->nama_hari }}</td>--}}
                        <td>{{ $td->judul }}</td>
                        <td>{{ $td->deskripsi }}</td>
                        <td>{{ $td->exp_date }}</td>
                        <td> <a class="btn btn-warning" href="{{url('tugaspokok/editTP/'. $td->id)}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> </td>
                        <td> <a class="btn btn-danger" data-href="{{url('tugaspokok/deleteTP/'. $td->id)}}" href="#modaldelete" data-toggle="modal"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </td>
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
                    <a class="btn btn-danger btn-ok" href="{{url('tugaspokok/deleteTP/'. $td->id)}}" type="submit" id="yes">
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
        <div class="col-md-12">
            Belum ada tugas.
        </div>
    @endif

@stop