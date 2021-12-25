@extends('layouts.backend')

@section('title','RPL')

@section('content')
<div class="content">
    <!-- Bootstrap Design -->
    <h2 class="content-heading">Pengajuan Penguji Peserta RPL</h2>
    <div class="block">
        <div class="block-header block-header-default">
            <h1 class="block-title" style="text-align: center; color:green;">
             Pengajuan Penguji Online dan Wawancara <b>{{$data->nama_mhs}}<b></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="block-header block-header-default">
                <h3 class="block-title">Dosen Penguji ONLINE</h3>
            </div>
            <div class="block">
                <div class="block-content">
                    <div class="row">
                        <div class="col-md-6">
                        @foreach($pembimbing as $key=>$pembimbings)
                            <div class="form-group">
                                <input type="text" class="form-control" name="idpem{{$key+1}}" value="{{$pembimbings->id}}" hidden>
                                <label for="sks">Penguji Online {{$key+1}}</label>
                                <input type="text" class="form-control "  name="pembimbing{{$key+1}}" Value="{{$pembimbings->nama_dosen}}" readonly>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="block-header block-header-default">
                <h3 class="block-title">Dosen Penguji Wawancara</h3>
            </div>
            <div class="block">
                <div class="block-content">
                    <div class="row">
                        <div class="col-md-6">
                            <?php $yong = 0;?>
                        @foreach($penguji as $key)
                        <?php $yong++;?>
                            <div class="form-group">
                                <input type="text" class="form-control" name="" value="{{$key->id}}" hidden>
                                <label for="sks">Penguji Wawancara <?=$yong;?></label>
                                <input type="text" class="form-control "  name="" Value="{{$key->nama_dosen}}" readonly>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_after')
<script>jQuery(function(){ Codebase.helpers(['select2','flatpickr']); });</script>
@endsection