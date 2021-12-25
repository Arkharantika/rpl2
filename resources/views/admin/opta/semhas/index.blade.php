@extends('layouts.backend')

@section('title','List Seminar Hasil TA')

@section('content')
<div class="content">
<!-- Dynamic Table with Export Buttons -->
    @if(session()->get('message'))
        <div class="alert alert-info alert-dismissable mt-20" role="alert">
            <strong> {{ session()->get('message') }}  </strong> 
        </div>
    @endif
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Daftar Seminar Hasil <small>Teknik Elektro</small></h3>
        </div>
        <div class="block-content block-content-full">
            <!-- DataTables functionality is initialized with .js-dataTable-full class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
            <thead>
                <tr>
                    <th class="d-none d-sm-table-cell text-center" style="width: 3%">No</th>
                    <th class="d-none d-sm-table-cell text-center" style="width: 7%">NIM</th>
                    <th class="text-center" style="width: 30%;">Nama</th>
                    <th class="d-none d-sm-table-cell text-center" style="width: 30%;">Judul</th>
                    <!-- <th class="text-center" style="width: 10%">Berkas</th> -->
                    <th class="text-center" style="width: 20%;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1?>
                @foreach ($data as $row)
                <tr>
                    <td class="d-none d-sm-table-cell text-center font-size-sm text-center">{{ $no++}}</td>
                    <td class="d-none d-sm-table-cell text-center font-size-sm text-center">{{ $row->nim}}</td>
                    <td class="font-w600 font-size-sm text-center">
                        <a href="#">{{ $row->nama_mhs}}</a>
                    </td>
                    <td class="d-none d-sm-table-cell font-size-sm text-center">
                        {{ $row->judul}}
                    </td>
                    <!-- <td class="font-size-sm text-center">
                        <span class="badge {{$row->cetak_semhas == 1 ? 'badge-primary' : 'badge-danger'}}">
                            {{$row->cetak_semhas == 1 ? 'Sudah Cetak' : 'Belum Cetak'}}
                        </span>
                    </td> -->
                    <td width="250" style="text-align: center;">
                        <a href="{{route('opta.semhas.edit', $row->id)}}" class="btn btn-sm btn-alt-primary mr-5 mb-5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Perbarui"><i class="fa fa-eye"></i></a>
                        <a href="{{route('opta.semhas.show', $row->ta_id)}}" class="btn btn-sm btn-alt-secondary mr-5 mb-5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Berkas Seminar Hasil" target="_blank"><i class="fa fa-print"></i></a>
                        @if($row->pernyataan == 1 || $row->pernyataan == 0)
                        <a href="{{route('opta.semhas.rekap', $row->ta_id)}}" class="btn btn-sm btn-alt-warning mr-5 mb-5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Berita Acara Seminar Hasil" target="_blank"><i class="fa fa-file"></i></a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
<!-- END Dynamic Table with Export Buttons -->
</div>
@endsection
