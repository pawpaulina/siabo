@extends('app')
@section('content')
    <h1 class="col-md-2">Tugas</h1>

    @if ($todo->count())
        <nav id="nav">
            <h3>
            </h3>
        </nav>
        <div class="col-md-12">
            Nama : {{$user->name}}
        </div>
        <div class="col-md-12">
            <table class="table table-striped table-bordered" width="100%">
                <thead>
                <tr>
                    <th>Judul Tugas</th>
                    <th>Deskripsi Tugas</th>
                    <th>Keterangan</th>
                    <th>Bukti</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($todo as $td)
                    <tr>
                        <td>{{ $td->judul_tugas }}</td>
                        <td>{{ $td->deskripsi_tugas }}</td>
                        <td>{{ $td->keterangan }}</td>
                        <td>{{ $td->gambar }}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
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
            Belum ada tugas untuk user ini.
        </div>
    @endif

@stop