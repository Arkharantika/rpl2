@extends('layouts.backend')

@section('title','RPL')

@section('content')
<div class="content">
@if(session()->get('message'))
    <div class="alert alert-success alert-dismissable mt-20" role="alert">
        <strong> {{ session()->get('message') }} </strong>
    </div>
    @endif
    <h2 class="content-heading">Penilaian RPL</h2>
    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Mahasiswa</h3>
                </div>
                <div class="block-content">
                    <div class="form-group row">
                        <label class="col-2" for="nama">Nama</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="{{$data->nama_mhs }}" placeholder="masukkan nama" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2" for="nim">NIM</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" id="nim" name="nim" value="{{$data->nim }}" placeholder="masukkan nim" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2" for="sks">Total SKS</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" id="sks" name="sks" value="{{$data->sks }}" placeholder="Total SKS yang dicapai" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2" for="ipk">Indeks Prestasi Kumulatif</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" id="ipk" name="ipk" value="{{$data->ipk }}" placeholder="IPK terakhir" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Nilai RPL</h3>
                </div>
                <div class="block-content">
                <form action="{{url('setornilaipembimbing/'.$data->nim)}}" method="post">
                    @csrf
                    <h5>A. KODE ETIK DAN ETIKA  PROFESI  INSINYUR</h5>
                    <h6>Pengalaman Organisasi Profesi</h6>
                    <input type="hidden" name="id_pembimbing" value="{{$data->id}}">
                    <div class="form-group row">
                        <label class="col-md-4" for="a1">1. Ketua/Wkl Ketua </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no1" value="" placeholder="{{$pengecheck->no_1}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="a2">2. Pengurus Inti  </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no2" value="" placeholder="{{$pengecheck->no_2}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="a3">3. Pengurus bidang</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no3" value="" placeholder="{{$pengecheck->no_3}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="a4">4. Anggota</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no4" value="" placeholder="{{$pengecheck->no_4}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 text-muted" for="a4">Total </label>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="no4" value="" placeholder="{{$jumlah_step_1}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <h6>Pengalaman mengikuti pertemuan etika keprofesian</h6>
                    <input type="hidden" name="id_pembimbing" value="{{$data->id}}">
                    <div class="form-group row">
                        <label class="col-md-4" for="a1">1. pembicara </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no5" value="" placeholder="{{$pengecheck->no_5}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="a2">2. panitia  </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no6" value="" placeholder="{{$pengecheck->no_6}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="a3">3. peserta</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no7" value="" placeholder="{{$pengecheck->no_7}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 text-muted" for="a4">Total </label>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="no4" value="" placeholder="{{$jumlah_step_2}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <h6>Penerapan etika profesi keinsinyuran</h6>
                    <input type="hidden" name="id_pembimbing" value="{{$data->id}}">
                    <div class="form-group row">
                        <label class="col-md-4" for="a1">1. Sertifikat Pendidik bidang keinsinyuran </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no8" value="" placeholder="{{$pengecheck->no_8}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="a2">2. Sertifikat ahli profesi (pengalaman kerja)  </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no9" value="" placeholder="{{$pengecheck->no_9}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="a3">3. surat keterangan kerja bid etika keinsinyuran</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no10" value="" placeholder="{{$pengecheck->no_10}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 text-muted" for="a4">Total </label>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="no4" value="" placeholder="{{$jumlah_step_3}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <h6>Lain - Lain</h6>
                    <input type="hidden" name="id_pembimbing" value="{{$data->id}}">
                    <div class="form-group row">
                        <label class="col-md-4" for="a1">1. Claim CV (1 item jenis pek, claim 0,25)</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no11" value="" placeholder="{{$pengecheck->no_11}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 text-muted" for="a4">Total </label>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="no4" value="" placeholder="{{$jumlah_step_4}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <h6 class="col-md-4" for="a4"> JUMLAH TOTAL BAB A</h6>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="" value="" placeholder="{{$jumlah_step_A}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <br>
                    <h5>B. PROFESIONALISME KEINSINYURAN </h5>
                    <h6>Praktik profesi keinsinyuran</h6>
                    <div class="form-group row">
                        <label class="col-md-4" for="b1">1. lembaga formal</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no12" value="" placeholder="{{$pengecheck->no_12}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="b2">2. lembaga non formal</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no13" value="" placeholder="{{$pengecheck->no_13}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 text-muted" for="a4">Total </label>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="no4" value="" placeholder="{{$jumlah_step_5}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <h6>Pendidikan</h6>
                    <div class="form-group row">
                        <label class="col-md-4" for="b3">1. strata lanjut</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no14" value="" placeholder="{{$pengecheck->no_14}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="b4">2. singkat/course</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no15" value="" placeholder="{{$pengecheck->no_15}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 text-muted" for="a4">Total </label>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="" value="" placeholder="{{$jumlah_step_6}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <h6>Pelatihan</h6>
                    <div class="form-group row">
                        <label class="col-md-4" for="b5">1. kerja formal</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no16" value="" placeholder="{{$pengecheck->no_16}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="b5">2. kerja non formal</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no17" value="" placeholder="{{$pengecheck->no_17}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 text-muted" for="a4">Total </label>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="" value="" placeholder="{{$jumlah_step_7}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <h6>Penugasan kerja </h6>
                    <div class="form-group row">
                        <label class="col-md-4" for="b5">1. setingkat leader/pimpinan/ketua</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no18" value="" placeholder="{{$pengecheck->no_18}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="b5">2. setingkat pelaksana</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no19" value="" placeholder="{{$pengecheck->no_19}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="b5">3. setingkat operator</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no20" value="" placeholder="{{$pengecheck->no_20}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 text-muted" for="a4">Total </label>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="" value="" placeholder="{{$jumlah_step_8}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <h6 class="col-md-4" for="a4"> JUMLAH TOTAL BAB B</h6>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="" value="" placeholder="{{$jumlah_step_B}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <br>
                    <h5>C. KESELAMATAN, KESEHATAN, KEAMANAN KERJA DAN LINGKUNGAN</h5>
                    <h6>Bekerja Bidang K3</h6>
                    <div class="form-group row">
                        <label class="col-md-4" for="c1">1. setingkat representative/kebijakan</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no21" value="" placeholder="{{$pengecheck->no_21}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="c2">2. setingkat manager</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no22" value="" placeholder="{{$pengecheck->no_22}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="c3">3. setingkat pelaksana</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no23" value="" placeholder="{{$pengecheck->no_23}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">4. setingkat operator</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no24" value="" placeholder="{{$pengecheck->no_24}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 text-muted" for="a4">Total </label>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="" value="" placeholder="{{$jumlah_step_9}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <h6>Pelatihan / Workshop / Seminar</h6>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">1. pemateri/pemakalah</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no25" value="" placeholder="{{$pengecheck->no_25}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">2. peserta</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no26" value="" placeholder="{{$pengecheck->no_26}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">3. panitia</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no27" value="" placeholder="{{$pengecheck->no_27}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 text-muted" for="a4">Total </label>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="" value="" placeholder="{{$jumlah_step_10}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <h6 class="col-md-4" for="a4"> JUMLAH TOTAL BAB C</h6>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="" value="" placeholder="{{$jumlah_step_C}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <br>
                    <h5>D. PRAKTIK KEINSINYURAN</h5>
                    <h6>Pendidikan</h6>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">1. Melaksanakan pengembangan hasil pendidikan </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no28" value="" placeholder="{{$pengecheck->no_28}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">2. Melaksanakan pengembangan hasil  penelitian </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no29" value="" placeholder="{{$pengecheck->no_29}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 text-muted" for="a4">Total </label>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="" value="" placeholder="{{$jumlah_step_11}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <h6>Pekerjaan / Proyek Keinsinyuran</h6>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">1. Direksi/GM</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no30" value="" placeholder="{{$pengecheck->no_30}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">2. Leader/manager</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no31" value="" placeholder="{{$pengecheck->no_31}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">3. Engineer </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no32" value="" placeholder="{{$pengecheck->no_32}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">4. Operator </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no33" value="" placeholder="{{$pengecheck->no_33}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 text-muted" for="a4">Total </label>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="" value="" placeholder="{{$jumlah_step_12}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <h6>Paparan Dan Laporan Teknis Internal Paparan Pada Pertemuan Keinsinyuran</h6>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">1. Paparan Dan Laporan Teknis Internal </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no34" value="" placeholder="{{$pengecheck->no_34}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 text-muted" for="a4">Total </label>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="" value="" placeholder="{{$jumlah_step_13}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">2. Paparan Pada Pertemuan Keinsinyuran </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no35" value="" placeholder="{{$pengecheck->no_35}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 text-muted" for="a4">Total </label>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="" value="" placeholder="{{$jumlah_step_14}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <h6 class="col-md-4" for="a4"> JUMLAH TOTAL BAB D</h6>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="" value="" placeholder="{{$jumlah_step_D}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <br>
                    <h5>E. STUDI KASUS </h5>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">1. Studi kasus keinsinyuran </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no36" value="" placeholder="{{$pengecheck->no_36}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">2. Memberi pelayanan kepada masyarakat atau kegiatan lain bidang keinsinyuran </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no37" value="" placeholder="{{$pengecheck->no_37}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">3. Menunjang pelaksanaan tugas umum pemerintah dan pembangunan </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no38" value="" placeholder="{{$pengecheck->no_38}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">4. Pengajaran Sebagai Pengajar/Instruktur </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no39" value="" placeholder="{{$pengecheck->no_39}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 text-muted" for="a4">Total </label>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="" value="" placeholder="{{$jumlah_step_15}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <h6 class="col-md-4" for="a4"> JUMLAH TOTAL BAB E</h6>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="" value="" placeholder="{{$jumlah_step_15}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <br>
                    <h5>F. SEMINAR, WORKSHOP, DISKUSI</h5>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">1. Sebagai Pakar atau Narasumber</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no40" value="" placeholder="{{$pengecheck->no_40}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">2. Sebagai Penerima Tanda Jasa, Award, dan sejenisnya</label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no41" value="" placeholder="{{$pengecheck->no_41}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">3. Melaksanakan pengembangan hasil pendidikan dan penelitian </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no42" value="" placeholder="{{$pengecheck->no_42}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">4. Memberi latihan/penyuluhan/penataran/ceramah pada masyarakat </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no43" value="" placeholder="{{$pengecheck->no_43}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">5.  Berperan serta aktif dalam pertemuan ilmiah </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no44" value="" placeholder="{{$pengecheck->no_44}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">6. Berperan serta aktif dalam pengelolaan jurnal ilmiah </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no45" value="" placeholder="{{$pengecheck->no_45}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">7. Penulisan Makalah Untuk Pertemuan Profesi </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no46" value="" placeholder="{{$pengecheck->no_46}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">8. Penulisan Untuk Majalah </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no47" value="" placeholder="{{$pengecheck->no_47}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4" for="revisi">9. Penulisan Buku </label>
                        <div class="col-md-8">
                            <input readonly type="number"step=".01" min="0" max="1" class="form-control" name="no48" value="" placeholder="{{$pengecheck->no_48}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 text-muted" for="a4">Total </label>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="" value="" placeholder="{{$jumlah_step_16}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <h6 class="col-md-4" for="a4"> JUMLAH TOTAL BAB F</h6>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="" value="" placeholder="{{$jumlah_step_16}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <h5 class="col-md-4" for="a4"> RATA - RATA NILAI KESELURUHAN </h5>
                        <div class="col-md-2">
                            <input readonly class="form-control" name="" value="" placeholder="{{$jumlah_step_total}}">
                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-8">
                            <!-- <button class="btn btn-primary mr-5 mb-5">Submit Nilai</button> -->
                            <!-- <button class="btn btn-primary mr-5 mb-5">Simpan</button> -->
                            <!-- <a href="{{route('dosen.semhas.index')}}" class="btn btn-secondary mr-5 mb-5">Kembali</a> -->
                            <!-- <a href="" class="btn btn-primary mr-5 mb-5">Simpan</a> -->
                            <a href="{{route('dosen.semhas.index')}}" class="btn btn-secondary mr-5 mb-5">Kembali</a>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
