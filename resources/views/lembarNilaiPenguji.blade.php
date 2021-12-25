@extends('layouts.backend')

@section('title','RPL')

@section('content')
<div class="content">
    <h2 class="content-heading">Penilaian RPL Penguji Wawancara</h2>
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
                            <input type="text" class="form-control" id="nama" name="nama" value="{{$data->nama_mhs }}"
                                placeholder="masukkan nama" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2" for="nim">NIM</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" id="nim" name="nim" value="{{$data->nim }}"
                                placeholder="masukkan nim" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2" for="sks">Total SKS</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" id="sks" name="sks" value="{{$data->sks }}"
                                placeholder="Total SKS yang dicapai" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2" for="ipk">Indeks Prestasi Kumulatif</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" id="ipk" name="ipk" value="{{$data->ipk }}"
                                placeholder="IPK terakhir" readonly>
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
                    <form action="{{url('setornilaipenguji/'.$data->nim)}}" method="post">
                        @csrf
                        <div class="form-group row">
                            <h5 class="col-md-4 ">KODE ETIK DAN ETIKA PROFESI INSINYUR</h5>
                            <h5 class="col-md-2 text-center"></h5>
                            <h5 class="col-md-2 text-center text-muted">Penguji Online 1</h5>
                            <!-- <h5 class="col-md-2 text-center">Pembimbing 2</h5>
                        <h5 class="col-md-2 text-center">Penguji 1</h5>
                        <h5 class="col-md-2 text-center">Penguji 2</h5> -->
                        </div>
                        <div class="form-group row">
                            <h6 class="col-md-4 ">Pengalaman Organisasi Profesi</h6>
                            <h6 class="col-md-2 text-center text-muted"></h6>
                            <h6 class="col-md-2 text-center text-muted"></h6>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="a1">1. Ketua/Wkl Ketua </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no1"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_1}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="a2">2. Pengurus Inti </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no2"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_2}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="a3">3. Pengurus bidang</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no3"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_3}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="a4">4. Anggota</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no4"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_4}}" readonly>
                            </div>
                        </div>
                        <h6>Pengalaman mengikuti pertemuan etika keprofesian</h6>
                        <div class="form-group row">
                            <label class="col-md-4" for="a1">1. pembicara </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no5"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_5}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="a2">2. panitia </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no6"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_6}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="a3">3. peserta</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no7"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_7}}" readonly>
                            </div>
                        </div>
                        <h6>Penerapan etika profesi keinsinyuran</h6>
                        <div class="form-group row">
                            <label class="col-md-4" for="a1">1. Sertifikat Pendidik bidang keinsinyuran </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no8"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_8}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="a2">2. Sertifikat ahli profesi (pengalaman kerja) </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no9"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_9}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="a3">3. surat keterangan kerja bid etika keinsinyuran</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no10"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_10}}" readonly>
                            </div>
                        </div>
                        <h6>Lain - Lain</h6>
                        <div class="form-group row">
                            <label class="col-md-4" for="a1">1. Claim CV (1 item jenis pek, claim 0,25)</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no11"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_11}}" readonly>
                            </div>
                        </div>
                        <br>
                        <h5>PROFESIONALISME KEINSINYURAN </h5>
                        <h6>Praktik profesi keinsinyuran</h6>
                        <div class="form-group row">
                            <label class="col-md-4" for="b1">1. lembaga formal</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no12"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_12}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="b2">2. lembaga non formal</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no13"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_13}}" readonly>
                            </div>
                        </div>
                        <h6>Pendidikan</h6>
                        <div class="form-group row">
                            <label class="col-md-4" for="b3">1. strata lanjut</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no14"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_14}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="b4">2. singkat/course</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no15"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_15}}" readonly>
                            </div>
                        </div>
                        <h6>Pelatihan</h6>
                        <div class="form-group row">
                            <label class="col-md-4" for="b5">1. kerja formal</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no16"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_16}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="b5">2. kerja non formal</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no17"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_17}}" readonly>
                            </div>
                        </div>
                        <h6>Penugasan kerja </h6>
                        <div class="form-group row">
                            <label class="col-md-4" for="b5">1. setingkat leader/pimpinan/ketua</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no18"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_18}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="b5">2. setingkat pelaksana</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no19"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_19}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="b5">3. setingkat operator</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no20"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_20}}" readonly>
                            </div>
                        </div>
                        <br>
                        <h5>KESELAMATAN, KESEHATAN, KEAMANAN KERJA DAN LINGKUNGAN</h5>
                        <h6>Bekerja Bidang K3</h6>
                        <div class="form-group row">
                            <label class="col-md-4" for="c1">1. setingkat representative/kebijakan</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no21"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_21}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="c2">2. setingkat manager</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no22"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_22}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="c3">3. setingkat pelaksana</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no23"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_23}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">4. setingkat operator</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no24"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_24}}" readonly>
                            </div>
                        </div>
                        <h6>Pelatihan / Workshop / Seminar</h6>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">1. pemateri/pemakalah</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no25"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_25}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">2. peserta</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no26"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_26}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">3. panitia</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no27"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_27}}" readonly>
                            </div>
                        </div>
                        <br>
                        <h5>PRAKTIK KEINSINYURAN</h5>
                        <h6>Pendidikan</h6>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">1. Melaksanakan pengembangan hasil pendidikan </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no28"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_28}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">2. Melaksanakan pengembangan hasil penelitian </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no29"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_29}}" readonly>
                            </div>
                        </div>
                        <h6>Pekerjaan / Proyek Keinsinyuran</h6>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">1. Direksi / GM</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no30"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_30}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">2. Leader / manager</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no31"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_31}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">3. Engineer </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no32"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_32}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">4. Operator </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no33"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_33}}" readonly>
                            </div>
                        </div>
                        <h6>Paparan Dan Laporan Teknis Internal Paparan Pada Pertemuan Keinsinyuran</h6>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">1. Paparan Dan Laporan Teknis Internal </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no34"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_34}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">2. Paparan Pada Pertemuan Keinsinyuran </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no35"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_35}}" readonly>
                            </div>
                        </div>
                        <br>
                        <h5>STUDI KASUS </h5>

                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">1. Studi kasus keinsinyuran </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no36"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_36}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">2. Memberi pelayanan kepada masyarakat atau kegiatan
                                lain bidang keinsinyuran </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no37"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_37}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">3. Menunjang pelaksanaan tugas umum pemerintah dan
                                pembangunan </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no38"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_38}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">4. Pengajaran Sebagai Pengajar/Instruktur </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no39"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_39}}" readonly>
                            </div>
                        </div>
                        <br>
                        <h5>SEMINAR, WORKSHOP, DISKUSI</h5>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">1. Sebagai Pakar atau Narasumber</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no40"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_40}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">2. Sebagai Penerima Tanda Jasa, Award, dan
                                sejenisnya</label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no41"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_41}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">3. Melaksanakan pengembangan hasil pendidikan dan
                                penelitian </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no42"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_42}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">4. Memberi latihan/penyuluhan/penataran/ceramah pada
                                masyarakat </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no43"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_43}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">5. Berperan serta aktif dalam pertemuan ilmiah </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no44"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_44}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">6. Berperan serta aktif dalam pengelolaan jurnal ilmiah
                            </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no45"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_45}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">7. Penulisan Makalah Untuk Pertemuan Profesi </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no46"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_46}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">8. Penulisan Untuk Majalah </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no47"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_47}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4" for="revisi">9. Penulisan Buku </label>
                            <div class="col-md-2">
                                <input required type="number" step=".01" min="0" max="1" class="form-control" name="no48"
                                    value="" placeholder="0 - 1">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="{{$yangke1->no_48}}" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8">

                                <button class="btn btn-primary mr-5 mb-5">Submit</button>

                                <a href="" class="btn btn-secondary mr-5 mb-5">Kembali</a>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js_after')
<!-- <script>jQuery(function(){ Codebase.helpers(['select2']); });</script> -->

@endsection