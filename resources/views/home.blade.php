@extends('layouts.backend')

@section('content')
<!-- Page Content -->
<div class="content">
    @if(session()->get('message'))
    <div class="alert alert-danger alert-dismissable mt-20" role="alert">
        <strong> {{ session()->get('message') }} </strong>
    </div>
    @endif
    @if(session()->get('berhasil'))
    <div class="alert alert-info alert-dismissable mt-20" role="alert">
        <strong> {{ session()->get('berhasil') }} </strong>
    </div>
    @endif
    <div class="row">
        <div class="col-sm-4">
            <!-- User Info -->
            <div class="row">
                <div class="col-md-12">
                    @can('mahasiswa')
                    <div class="block block-rounded text-center bg-primary-dark">
                        <div class="block-content block-content-full">
                            <img class="img-avatar img-avatar-thumb" src="{{asset('/media/avatars/avatar8.jpg')}}"
                                alt="">
                        </div>
                        <div class="block-content block-content-full block-content-sm bg-black-op">
                            <div class="font-w600 text-white mb-5">{{$mhs->nama_mhs}}</div>
                            <div class="font-size-sm text-white-op">{{$mhs->nim}}</div>
                            <div class="font-size-sm text-white-op">Angkatan {{$mhs->angkatan}}</div>
                        </div>
                    </div>
                    @endcan
                    @if(Auth::user()->can('manage-users') || Auth::user()->can('koordinatorta')
                    || Auth::user()->can('koordinatorkp') || Auth::user()->can('dosen'))
                    <div class="block block-rounded text-center">
                        <div class="block-content block-content-full bg-primary-dark block-sticky-option pt-30">
                            <img class="img-avatar img-avatar-thumb" src="{{asset('/media/avatars/avatar8.jpg')}}"
                                alt="">
                        </div>
                        <div class="block-content block-content-full block-content-sm bg-body-light">
                            <div class="font-w600 mb-5">{{ $dosen->nama_dosen ?? '' }}</div>
                            <div class="font-size-sm text-muted">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="block-content">
                            <div class="row items-push">
                                <div class="col-md-12">
                                    <div class="font-size-h5 text-black"><strong>Jabatan : </strong></div>
                                    <div class="font-size-sm text-black">
                                        {{implode(", \n" ,$user->roles()->get()->pluck('name')->toArray())}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                @can('manage-users')
                <div class="col-md-12">
                    <div class="block block-rounded">
                        <div class="block-content block-content-full">
                            <div class="row pt-10 pb-10 text-center h-50">
                                <div class="col-12 ">
                                    <div class=" font-size-sm font-w600 text-uppercase text-muted">Form Pengisian DATA
                                        RPL
                                    </div>
                                    <br>
                                    @if($submitan->status == 1)
                                    <div class="font-size-h5 font-w600 text-success">Dibuka</div>
                                    <br>
                                    <a href="{{url('tutuprpl')}}" data-userid="" data-toggle="" data-target=""
                                        class="btn btn-rounded btn-hero btn-sm btn-alt-danger mb-5">Tutup Pengisian
                                        Data</a>
                                    @elseif($submitan->status == 0)
                                    <div class="font-size-h5 font-w600 text-danger">Ditutup</div>
                                    <br>
                                    <a href="{{url('bukarpl')}}" data-userid="" data-toggle="" data-target=""
                                        class="btn btn-rounded btn-hero btn-sm btn-alt-success mb-5">Buka Pengisian
                                        Data</a>
                                    @endif
                                </div>
                                <br><br><br>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan

                @can('mahasiswa')
                <div class="col-md-12">
                    <div class="block block-rounded">
                        <div class="block-content block-content-full">
                            <div class="row pt-10 pb-10 text-center h-50">
                                <div class="col-12 ">
                                    <div class=" font-size-sm font-w600 text-uppercase text-muted">Form Pengisian DATA
                                        RPL
                                    </div>
                                    <br>
                                    @if($submitan->status == 1)
                                    <div class="font-size-h5 font-w600 text-success">Dibuka</div>
                                    <br>
                                    <!-- <a href="{{url('tutuprpl')}}" data-userid="" data-toggle="" data-target=""
                                        class="btn btn-rounded btn-hero btn-sm btn-alt-danger mb-5">Tutup Pengisian
                                        Data</a> -->
                                    @elseif($submitan->status == 0)
                                    <div class="font-size-h5 font-w600 text-danger">Ditutup</div>
                                    <br>
                                    <!-- <a href="{{url('bukarpl')}}" data-userid="" data-toggle="" data-target=""
                                        class="btn btn-rounded btn-hero btn-sm btn-alt-success mb-5">Buka Pengisian
                                        Data</a> -->
                                    @endif
                                </div>
                                <br><br><br>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Data Diri -->
                <!-- <div class="col-md-12">
                    <div class="block block-rounded">
                        <div class="block-content block-content-full">
                            <div class="row pt-10 pb-10 text-center h-50">
                                <div class="col-6 border-r">
                                    <div class="font-size-h3 font-w600 text-primary">{{$mhs->sks}}</div>
                                    <div class="font-size-sm font-w600 text-uppercase text-muted">SKS</div>
                                </div>
                                <div class="col-6">
                                    <div class="font-size-h3 font-w600 text-earth">{{$mhs->ipk}}</div>
                                    <div class="font-size-sm font-w600 text-uppercase text-muted">IPK</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="col-md-12">
                    <div class="block block-rounded">
                        <div class="block-content block-content-full">
                            <div class="row pt-10 pb-10 text-center h-50">
                                <div class="col-12 border-b">
                                    <div class=" font-size-sm font-w600 text-uppercase text-muted">Pembimbing Akademik
                                    </div>
                                    <br>
                                    <div class="font-w600 text-elegance">{{$mhs->nama_dosen}}</div>
                                    <br>
                                    @if($checkingan->status == 1)
                                    <div class="font-size-h5 font-w600 text-success">Sudah Submit RPL</div>
                                    <br>
                                    @else
                                    <div class="font-size-h5 font-w600 text-pulse">Belum Submit RPL</div>
                                    <br>
                                    @endif
                                </div>
                                <!-- <div class="col-12">
                                    <div class="font-size-h5 font-w600 text-pulse">{{$mhs->status_mhs}}</div>
                                    <div class="font-size-sm font-w600 text-uppercase text-muted">Status Mahasiswa</div>
                                </div> -->
                                <br><br><br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="block block-rounded">
                        <div class="block-content block-content-full">
                            <div class="row pt-10 pb-10 text-center h-50">
                                <div class="col-6 border-r">
                                    <div class="font-size-h4 font-w600 text-primary">
                                        <a href="{{url('ta/wisuda/cetakprofil/'.Auth::user()->id)}}"
                                            class="btn btn-rounded btn-hero btn-sm btn-alt-primary mb-5">
                                            Cetak RPL
                                        </a> </div>
                                    <!-- <div class="font-size-sm font-w600 text-uppercase text-muted">SKS</div> -->
                                </div>
                                <div class="col-6">
                                    <div class="font-size-h4 font-w600 text-earth">
                                        <!-- <a href="{{url('submitrpl/'.Auth::user()->nim)}}"
                                            class="btn btn-rounded btn-hero btn-sm btn-alt-success mb-5">
                                            Submit RPL
                                        </a> -->
                                        @if($checkingan == null || $checkingan->status == 0)
                                        <a href="javascript:;" data-userid="{{$user->id}}" data-toggle="modal"
                                            data-target="#delete"
                                            class="btn btn-rounded btn-hero btn-sm btn-alt-success mb-5">Submit RPL</a>
                                        @elseif($checkingan->status == 1)
                                        <button data-userid="" data-toggle="" data-target=""
                                            class="btn btn-rounded btn-hero btn-sm btn-alt-secondary mb-5">RPL
                                            Submited</button>
                                        @endif
                                    </div>
                                    <!-- <div class="font-size-sm font-w600 text-uppercase text-muted">IPK</div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Data Diri -->
                @endcan
            </div>
            <!-- END User Info -->
        </div>
        <div class="col-sm-8 clearfix">
            <!-- Open Mahasiswa -->
            @can('mahasiswa')
            <div class="row invisible" data-toggle="appear">
                <div class="col-md-6">
                    <a class="block block-rounded" href="{{url('PresensiSeminarKP')}}">
                        <div class="block-content block-content-full ribbon ribbon-modern ribbon-primary">
                            <div class="ribbon-box">
                                {{$duaA}}
                            </div>
                            <div class="text-center py-30">
                                <i class="fa fa-book fa-3x text-info mb-15 float-left"></i>
                                <h5 class="mb-0">Pendidikan Strata satu dan strata lanjut</h5>
                                <!-- <div class="text-muted">Membutuhkan Konfirmasi Dosen</div> -->
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="block block-rounded" href="{{url('dua/b')}}">
                        <div class="block-content block-content-full ribbon ribbon-modern ribbon-primary">
                            <div class="ribbon-box">
                                {{$duaB}}
                            </div>
                            <div class="text-center py-30">
                                <i class="fa fa-book fa-3x text-info mb-15 float-left"></i>
                                <h5 class="mb-0">Pendidikan Singkat</h5>
                                <!-- <div class="text-muted">Membutuhkan Konfirmasi Dosen</div> -->
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row invisible" data-toggle="appear">
                <div class="col-md-6">
                    <a class="block block-rounded" href="{{url('dua/c')}}">
                        <div class="block-content block-content-full ribbon ribbon-modern ribbon-primary">
                            <div class="ribbon-box">
                                {{$duaC}}
                            </div>
                            <div class="text-center py-30">
                                <i class="fa fa-book fa-3x text-info mb-15 float-left"></i>
                                <h5 class="mb-0"> Pendidikan / Pelatihan Kerja Formal</h5>
                                <!-- <div class="text-muted">Membutuhkan Konfirmasi Dosen</div> -->
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="block block-rounded" href="{{url('tiga/a')}}">
                        <div class="block-content block-content-full ribbon ribbon-modern ribbon-primary">
                            <div class="ribbon-box">
                                {{$tigaA}}
                            </div>
                            <div class="text-center py-30">
                                <i class="fa fa-book fa-3x text-info mb-15 float-left"></i>
                                <h5 class="mb-0">Pembelajaran Mandiri</h5>
                                <!-- <div class="text-muted">Membutuhkan Konfirmasi Dosen</div> -->
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row invisible" data-toggle="appear">
                <div class="col-md-6">
                    <a class="block block-rounded" href="{{url('tiga/b')}}">
                        <div class="block-content block-content-full ribbon ribbon-modern ribbon-primary">
                            <div class="ribbon-box">
                                {{$tigaB}}
                            </div>
                            <div class="text-center py-30">
                                <i class="fa fa-book fa-3x text-info mb-15 float-left"></i>
                                <h5 class="mb-0">Pembelajaran Sehubungan Dengan Penugasan Kerja</h5>
                                <!-- <div class="text-muted">Membutuhkan Konfirmasi Dosen</div> -->
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="block block-rounded" href="{{url('empat/a')}}">
                        <div class="block-content block-content-full ribbon ribbon-modern ribbon-primary">
                            <div class="ribbon-box">
                                {{$empatA}}
                            </div>
                            <div class="text-center py-30">
                                <i class="fa fa-book fa-3x text-info mb-15 float-left"></i>
                                <h5 class="mb-0">Pengalaman Praktik Keinsinyuran pada lembaga / institusi formal
                                    (memiliki badan hukum)</h5>
                                <!-- <div class="text-muted">Membutuhkan Konfirmasi Dosen</div> -->
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row invisible" data-toggle="appear">
                <div class="col-md-6">
                    <a class="block block-rounded" href="{{url('empat/b')}}">
                        <div class="block-content block-content-full ribbon ribbon-modern ribbon-primary">
                            <div class="ribbon-box">
                                {{$empatB}}
                            </div>
                            <div class="text-center py-30">
                                <i class="fa fa-book fa-3x text-info mb-15 float-left"></i>
                                <h5 class="mb-0">Pengalaman Praktik Keinsinyuran pada lembaga / institusi non-formal
                                    (tidak ada badan hukum)</h5>
                                <!-- <div class="text-muted">Membutuhkan Konfirmasi Dosen</div> -->
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="block block-rounded" href="{{url('lima/a')}}">
                        <div class="block-content block-content-full ribbon ribbon-modern ribbon-primary">
                            <div class="ribbon-box">
                                {{$limaA}}
                            </div>
                            <div class="text-center py-30">
                                <i class="fa fa-book fa-3x text-info mb-15 float-left"></i>
                                <h5 class="mb-0">Paparan dan Laporan Internal, Konsultasi, dan Inspeksi pada praktik
                                    keinsinyuran</h5>
                                <!-- <div class="text-muted">Membutuhkan Konfirmasi Dosen</div> -->
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row invisible" data-toggle="appear">
                <div class="col-md-6">
                    <a class="block block-rounded" href="{{url('lima/b')}}">
                        <div class="block-content block-content-full ribbon ribbon-modern ribbon-primary">
                            <div class="ribbon-box">
                                {{$limaB}}
                            </div>
                            <div class="text-center py-30">
                                <i class="fa fa-book fa-3x text-info mb-15 float-left"></i>
                                <h5 class="mb-0">Paparan Pada Pertemuan Teknis (Pemateri dalam pertemuan profesi
                                    keinsinyuran)</h5>
                                <!-- <div class="text-muted">Membutuhkan Konfirmasi Dosen</div> -->
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="block block-rounded" href="{{url('lima/c')}}">
                        <div class="block-content block-content-full ribbon ribbon-modern ribbon-primary">
                            <div class="ribbon-box">
                                {{$limaC}}
                            </div>
                            <div class="text-center py-30">
                                <i class="fa fa-book fa-3x text-info mb-15 float-left"></i>
                                <h5 class="mb-0">Penulisan Makalah Untuk Pertemuan Profesi</h5>
                                <!-- <div class="text-muted">Membutuhkan Konfirmasi Dosen</div> -->
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row invisible" data-toggle="appear">
                <div class="col-md-6">
                    <a class="block block-rounded" href="{{url('lima/d')}}">
                        <div class="block-content block-content-full ribbon ribbon-modern ribbon-primary">
                            <div class="ribbon-box">
                                {{$limaD}}
                            </div>
                            <div class="text-center py-30">
                                <i class="fa fa-book fa-3x text-info mb-15 float-left"></i>
                                <h5 class="mb-0">Pengajar / Pelatih / Instruktur / Dosen Tamu pada pembelajaran atau
                                    pelatihan keinsinyuran</h5>
                                <!-- <div class="text-muted">Membutuhkan Konfirmasi Dosen</div> -->
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="block block-rounded" href="{{url('lima/e')}}">
                        <div class="block-content block-content-full ribbon ribbon-modern ribbon-primary">
                            <div class="ribbon-box">
                                {{$limaE}}
                            </div>
                            <div class="text-center py-30">
                                <i class="fa fa-book fa-3x text-info mb-15 float-left"></i>
                                <h5 class="mb-0">Karya tulis pada penerbitan berkala (jurnal / majalah / surat kabar
                                    dlsb)</h5>
                                <!-- <div class="text-muted">Membutuhkan Konfirmasi Dosen</div> -->
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row invisible" data-toggle="appear">
                <div class="col-md-6">
                    <a class="block block-rounded" href="{{url('lima/f')}}">
                        <div class="block-content block-content-full ribbon ribbon-modern ribbon-primary">
                            <div class="ribbon-box">
                                {{$limaF}}
                            </div>
                            <div class="text-center py-30">
                                <i class="fa fa-book fa-3x text-info mb-15 float-left"></i>
                                <h5 class="mb-0">Penulisan Buku Keinsinyuran</h5>
                                <!-- <div class="text-muted">Membutuhkan Konfirmasi Dosen</div> -->
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="block block-rounded" href="{{url('enam/a')}}">
                        <div class="block-content block-content-full ribbon ribbon-modern ribbon-primary">
                            <div class="ribbon-box">
                                {{$enamA}}
                            </div>
                            <div class="text-center py-30">
                                <i class="fa fa-book fa-3x text-info mb-15 float-left"></i>
                                <h5 class="mb-0">Sebagai Pakar atau Nara sumber pada bidang keinsinyuran (saksi ahli,
                                    tim ahli, dewan pakar dst)</h5>
                                <!-- <div class="text-muted">Membutuhkan Konfirmasi Dosen</div> -->
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row invisible" data-toggle="appear">
                <div class="col-md-6">
                    <a class="block block-rounded" href="{{url('enam/b')}}">
                        <div class="block-content block-content-full ribbon ribbon-modern ribbon-primary">
                            <div class="ribbon-box">
                                {{$enamB}}
                            </div>
                            <div class="text-center py-30">
                                <i class="fa fa-book fa-3x text-info mb-15 float-left"></i>
                                <h5 class="mb-0">Pengurus organisasi profesi atau pimpinan lembaga / institusi</h5>
                                <!-- <div class="text-muted">Membutuhkan Konfirmasi Dosen</div> -->
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="block block-rounded" href="{{url('enam/c')}}">
                        <div class="block-content block-content-full ribbon ribbon-modern ribbon-primary">
                            <div class="ribbon-box">
                                {{$enamC}}
                            </div>
                            <div class="text-center py-30">
                                <i class="fa fa-book fa-3x text-info mb-15 float-left"></i>
                                <h5 class="mb-0">Penerima tanda jasa, award, tanda penghargaan dan sejenisnya</h5>
                                <!-- <div class="text-muted">Membutuhkan Konfirmasi Dosen</div> -->
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        </div>
        @endcan
        <!-- Close Mahasiswa  -->
        <!-- Open Dosen  -->
        @can('dosen')
        <div class="col-md-6">
            <a class="block block-rounded" href="{{route('dosen.semhas.index')}}">
                <div class="block-content block-content-full ribbon ribbon-modern ribbon-warning">
                    <div class="ribbon-box">
                        {{$bimbingansemhas}}
                    </div>
                    <div class="text-center py-30">
                        <i class="fa fa-comments fa-3x text-warning mb-15 float-left"></i>
                        <h5 class="mb-0">Penguji Peserta RPL</h5>
                        <div class="text-muted">Daftar Mahasiswa Yang Siap Diuji</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row invisible" data-toggle="appear">
    </div>
    @endcan
    <!-- Close Dosen -->
    <!-- Open Koordinator Kp  -->
    @can('koordinatorkp')
    @endcan
    <!-- Close Koordinator Kp  -->
    <!-- Open Koordinator Ta -->
    @can('koordinatorta')
    @endcan
    <!-- Close Koordinator Ta  -->
    <!-- Open Operator Ta -->
    @can('operatorta')
    @endcan
    <!-- Close Operator Ta -->
    <!-- Open Koorditanor KBK -->
    @can('koordinatorsel')
    @endcan
    @can('koordinatorsm')
    @endcan
    @can('koordinatorict')
    @endcan
    <!-- Close Koorditanor KBK -->
</div>
</div>
</div>
<!-- END Page Content -->@can('mahasiswa')
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="modal-top" aria-hidden="true">
    <div class="modal-dialog modal-dialog-top" role="document">
        <form action="" method="post">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Konfirmasi Submit</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        @csrf
                        @method('DELETE')
                        <p class="text-center">Anda sudah yakin mengisi dengan baik ? Setelah submit data tidak dapat
                            diubah kembali </p>
                        <input type="hidden" name="user_id" id="user_id" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Tidak</button>
                    <!-- <button type="submit" class="btn btn-alt-danger">
                        <i class="fa fa-check"></i> Yes, Delete
                    </button> -->
                    <a href="{{url('submitrpl/'.$mhs->nim)}}" type="submit" class="btn btn-alt-success">
                        <i class="fa fa-check"></i> Ya, Submit
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan
@endsection