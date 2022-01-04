<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Example Routes
// Route::view('/', 'landing');
// Route::match(['get', 'post'], '/dashboard', function(){
//     return view('dashboard');
// });
// Route::view('/pages/slick', 'pages.slick');
// Route::view('/pages/datatables', 'pages.datatables');
// Route::view('/pages/blank', 'pages.blank');
Route::get('/','HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/password', 'HomeController@password')->name('password');
Route::post('/password/{id}', 'HomeController@passstore')->name('password.store');
Route::resource('/profil','ProfilController');
Route::resource('/usermanual','UsermanualController');
Route::resource('/signature','SignatureController');
//Route Role Admin
Route::namespace('Admin')->prefix('admin')->name('admin.')->middleware('can:manage-users')->group(function(){
    Route::get('/users/import','UsersController@importuser')->name('importuser');
    Route::post('/users/import','UsersController@importuserstore')->name('importuserstore');
    Route::get('/users/download','UsersController@download')->name('downloaduser');
    Route::resource('/users','UsersController');
    //Route Admin
    Route::resource('/akademik','AkademikController',['except' => ['create','store']]);
    //Route Admin Dosen
    Route::resource('/dosen','DosenController');
    //Route Admin Mahasiswa
    Route::get('/mahasiswa/import','MahasiswaController@importview')->name('importmhs');
    Route::post('/mahasiswa/import','MahasiswaController@importstore')->name('importmhsstore');
    Route::get('/mahasiswa/download','MahasiswaController@download')->name('downloadmhs');
    Route::resource('/mahasiswa','MahasiswaController');
    //Route Admin Mata Kuliah
    Route::resource('/matkul','MatkulController');
    //Route Admin Peminatan
    Route::resource('/peminatan','PeminatanController');
    //Route Admin Ruang
    Route::resource('/ruang','RuangController');
    //Route Admin Jabatan
    Route::resource('/jabatan','JabatanController');
});

//Route Role Mahasiswa
Route::name('kp.')->middleware('can:mahasiswa')->group(function(){
    //Route Kerja Pratek
    Route::get('kp/pendaftaran/cetak_form','KpController@cetak_form')->name('cetak.konsul');
    Route::get('kp/pelaksanaan/cetak_form_nilai','KpController@cetak_form_nilai')->name('cetak.formnilai');
    Route::get('kp/pelaksanaan/cetak_lmbr_tugas','KpController@cetak_lmbr_tugas')->name('cetak.lmbrtugas');
    Route::get('kp/pendaftaran/{file}/download', 'KpController@viewFile')->name('pendaftaran.download');
    Route::post('kp/upload/{id}', 'KpController@StoreUpload')->name('pendaftaran.upload');
    Route::resource('kp/pendaftaran','KpController',['except' => ['create','show']]);
    //Route Selesai Kp
    Route::resource('kp/selesaikp', 'SelesaikpController',['only' => ['index','update']]);

    //Route Seminar Kerja Praktek
    Route::get('kp/seminar/cetak_pengajuansemkp','SemkpController@cetak_pengajuansemkp')->name('cetak.pengajuansemkp');
    Route::get('kp/seminar/cetak_daftarhadir','SemkpController@cetak_daftarhadir')->name('cetak.daftarhadir');
    Route::get('kp/seminar/cetak_nilaipembimbing','SemkpController@cetak_nilai_pembimbing')->name('cetak.nilaipembimbing');
    Route::resource('kp/seminar','SemkpController',['except' => ['create','show']]);
    Route::resource('kp/laporan','LaporankpController',['only' => ['index','show','update']]);
});

Route::name('ta.')->middleware('can:mahasiswa')->group(function(){
    //Route Tugas Akhir
    Route::get('ta/pendaftaran/cetak_pendaftaran','TaController@cetak_pendaftaran')->name('cetak_pendaftaran');
    Route::resource('ta/pengajuan/pendaftaran','TaController',['only' => ['index', 'store', 'edit','update']]);
    Route::resource('ta/logbook','LogbooktaController');
    //Route Seminar Hasil TA
    Route::resource('ta/semhas','SemhasController',['only' => ['index', 'store', 'edit','update']]);
    //Route Pendadaran
    Route::resource('ta/pendadaran','PendadaranController',['only' => ['index', 'store', 'edit','update']]);
    //Route Kelengkapan Wisuda
    Route::resource('ta/wisuda','TadraftController',['only' => ['index', 'store', 'edit','update']]);
    Route::get('ta/wisuda/bebaslab/{id}','TadraftController@bebaslab')->name('bebaslab');
    Route::get('ta/wisuda/halpengesahan/{id}','TadraftController@halpengesahan')->name('halpengesahan');
    //Route Biodata Alumni
    Route::resource('ta/wisuda/alumni','BiodataalumniController');
    //Route Biodata Alumni
    Route::resource('ta/wisuda/exitsurvey','ExitsurveyController');
    //Route Perubahan Judul
    Route::resource('ta/pengajuan/judul','TajudulController');
    //Route Perubahan Pembimbing
    Route::resource('ta/pengajuan/pembimbing','TapembimbingController');
    //Route Perpanjangan TA
    Route::resource('ta/pengajuan/perpanjangan','TaperpanjanganController');
    //Route Perpanjangan TA
    Route::resource('ta/pengajuan/pembatalan','TapembatalanController');
});

//Route Role Koordinator KP
Route::namespace('Admin')->prefix('koordinator')->name('admin.')->middleware('can:koordinatorkp')->group(function(){
    //Kerja Praktek
    Route::resource('/kp/pembimbing','Kp\PembimbingkpController',['except' => ['create','store']]);
    Route::resource('/kp/pengajuan','Kp\PengajuanController',['except' => ['create','store']]);
    Route::resource('/kp/permohonan','Kp\PermohonanController',['only' => ['index','show']]);
    Route::get('/kp/lihatproposal/{id}','Kp\PermohonanController@proposal')->name('permohonan.proposal');
    Route::resource('/kp/balasan','Kp\BalasanController',['except' => ['create','store']]);
    Route::get('/kp/lihatpermohonan/{id}','Kp\BalasanController@lihatpermohonan')->name('balasan.permohonan');
    Route::get('/kp/lihatpenugasan/{id}','Kp\BalasanController@lihatpenugasan')->name('balasan.penugasan');
    Route::resource('/kp/penugasan','Kp\PenugasanController',['except' => ['create','store']]);
    Route::resource('/kp/selesaikp','Kp\SelesaikpController',['only' => ['index','show','edit']]);
    Route::get('/kp/lihatsurattugas/{id}','Kp\SelesaikpController@lihatsurattugas')->name('selesaikp.surattugas');

    //Seminar KP
    Route::resource('/kp/listsemkp','Seminarkp\ListController',['only' => ['index','show']]);
    Route::get('kp/seminarkp/getpresensi/{id}','Seminarkp\SeminarkpController@getpresensi')->name('seminarkp.presensi');
    Route::get('kp/seminarkp/update-status','Seminarkp\SeminarkpController@updateStatus')->name('update.status');
    Route::resource('/kp/seminarkp','Seminarkp\SeminarkpController',['only' => ['index','edit','update','show']]);
    Route::resource('/kp/presensi','Seminarkp\PresensiController',['only' => ['index','show']]);
    Route::get('/kp/nilai/{id}','Seminarkp\LaporanController@nilai')->name('laporan.nilai');
    Route::get('/kp/nilaipembimbing/{id}','Seminarkp\LaporanController@cetak_nilai_pembimbing')->name('nilai.pembimbing');
    Route::resource('/kp/laporan','Seminarkp\LaporanController',['only' => ['index','show','edit','update']]);
    Route::resource('/kp/nilaikp','Seminarkp\NilaikpController',['only' => ['index','show']]);

    //Report KP
    Route::resource('/reportpenugasan','Reportkp\ReportpenugasanController');
    Route::resource('/reportpermohonan','Reportkp\ReportpermohonanController');
    Route::resource('/reportbalasan','Reportkp\ReportbalasanController');
});

//Route Role Koordinator TA
Route::namespace('Admin')->prefix('koordinator')->name('admin.')->middleware('can:koordinatorta')->group(function(){
    //Tugas Akhir
    Route::resource('/ta/listta','Ta\ListController');
    Route::get('ta/downloaddraft/{id}','Ta\ListController@downloaddraft')->name('downloaddraft');
    Route::get('ta/downloadsourcecode/{id}','Ta\ListController@downloadsourcecode')->name('downloadsourcecode');
    Route::get('ta/halpengesahan/{id}','Ta\ListController@halpengesahan')->name('halpengesahan');
    Route::resource('/ta/pendaftaran','Ta\PendaftaranController',['except' => ['create','store','show']]);
    Route::resource('/ta/surattugas','Ta\SurattugasController',['except' => ['create','store']]);

    //Seminar Hasil Tugas Akhir
    Route::resource('/ta/semhas','Semhas\SemhasController',['except' => ['create','store']]);
    Route::resource('/ta/listsemhas','Semhas\ListController',['except' => ['create','store']]);
    Route::resource('/ta/listsemhas/rekapsemhas','Semhas\RekapController',['except' => ['create','store']]);

    //Pendadaran Tugas Akhir
    Route::resource('/ta/pendadaran','Pendadaran\PendadaranController',['except' => ['create','store']]);
    Route::resource('/ta/listpendadaran','Pendadaran\ListController',['except' => ['create','store']]);
    Route::resource('/ta/listpendadaran/rekappendadaran','Pendadaran\RekapController',['except' => ['create','store']]);

    //Perubahan Judul TA
    Route::resource('/logbookta','Logbookta\LogbooktaController');
    //Perubahan Judul TA
    Route::resource('/perubahanjudul','Judulta\TajudulController');
    //Perubahan Pembimbing TA
    Route::resource('/pembimbingta','Pembimbingta\TapembimbingController');
    //Perpenjangan TA
    Route::resource('/perpanjanganta','Perpanjanganta\TaperpanjanganController');
    //Perpenjangan TA
    Route::resource('/pembatalanta','Pembatalanta\TapembatalanController');
    //Exit Survey Mahasiswa
    Route::resource('/halpengesahan','Ta\HalpengesahanController');
    //Exit Survey Mahasiswa
    Route::resource('/exitsurvey','Ta\ExitsurveyController');

});

//Route Role Dosen
Route::namespace('Dosen')->prefix('dosen')->name('dosen.')->middleware('can:dosen')->group(function(){
    //Akademik
    Route::resource('/akademik','AkademikController',['only' => ['index']]);

    //Kerja Praktek
    Route::resource('/kp','KpController',['only' => ['index','show','update']]);
    Route::get('/kp/tempatkp/status','KpController@updateTempatkp')->name('tempatkp.update');
    Route::get('/kp/proposalkp/status','KpController@updateProposalkp')->name('proposalkp.update');
    Route::patch('/kp/penugasankp/status','KpController@updatePenugasankp')->name('penugasankp.update');
    Route::get('/kp/seminarkp/status','KpController@updateSeminarkp')->name('seminarkp.update');
    Route::get('/kp/laporankp/status','KpController@updateLaporankp')->name('laporankp.update');
    Route::get('/kp/lihatproposal/{id}','KpController@lihatproposal')->name('lihatproposal');
    Route::get('/kp/lihatnilai/{id}','KpController@lihatnilai')->name('lihatnilai');
    Route::get('/kp/lihatlaporan/{id}','KpController@lihatlaporan')->name('lihatlaporan');
    Route::get('/kp/resetkp/status','KpController@kpreset')->name('kpreset.update');

    //Tugas Akhir
    Route::resource('/ta','TaController',['only' => ['index','edit','update','show']]);
    Route::get('/ta/surattugas/{id}','TaController@surattugas')->name('surattugas.ta');
    Route::get('/ta/detailsta/{id}','TaController@detailsta')->name('details.ta');

    //Seminar Hasil Tugas Akhir
    Route::resource('/semhas','SemhasController',['only' => ['index','edit','update','show']]);
    Route::get('/semhas/undangan/{id}','SemhasController@undangan')->name('undangan.semhas');
    Route::resource('/semhas/nilai_semhas','Pembimbing\NilaisemhasController');
    Route::patch('/semhas/nilai_semhas/validasi/{id}','Pembimbing\NilaisemhasController@validasi')->name('nilai_semhas.validasi');
    Route::get('/semhas/nilai_semhas/update/status','Pembimbing\NilaisemhasController@updateStatus')->name('nilai_semhas.updatestatus');
    Route::resource('/semhas/rekap_semhas','Pembimbing\RekapsemhasController');
    Route::resource('/semhas/penguji_semhas','Penguji\SemhasController');
    Route::patch('/penguji_semhas/validasi/{id}','Penguji\SemhasController@validasi')->name('penguji_semhas.validasi');
    Route::get('/penguji_semhas/update/status','Penguji\SemhasController@updateStatus')->name('penguji_semhas.updatestatus');

    //Pendadaran Tugas Akhir
    Route::resource('/pendadaran','PendadaranController',['only' => ['index','edit','update','show']]);
    Route::get('/pendadaran/undangan/{id}','PendadaranController@undangan')->name('undangan.pendadaran');
    Route::resource('/pendadaran/pembimbing_pendadaran','Pembimbing\NilaipendadaranController');
    Route::patch('/pendadaran/pembimbing_pendadaran/finalisasi/{id}','Pembimbing\NilaipendadaranController@finalisasi')->name('pembimbing_pendadaran.finalisasi');
    Route::get('/pendadaran/pembimbing_pendadaran/update/status','Pembimbing\NilaipendadaranController@updateStatus')->name('pembimbing_pendadaran.updatestatus');
    Route::resource('/pendadaran/rekap_pendadaran','Pembimbing\RekappendadaranController');
    Route::resource('/pendadaran/penguji_pendadaran','Penguji\PendadaranController');
    Route::patch('/penguji_pendadaran/finalisasi/{id}','Penguji\PendadaranController@finalisasi')->name('penguji_pendadaran.finalisasi');
    Route::get('/penguji_pendadaran/update/status','Penguji\PendadaranController@updateStatus')->name('penguji_pendadaran.updatestatus');

    //Tawaran Topik Ta
    Route::resource('/tawaran','TawaranController');

    //Logbook TA
    Route::resource('/logbookta','LogbooktaController');
    Route::get('/logbookta/{id}/details','LogbooktaController@details')->name('logbookta.details');
    Route::get('/update/statuslog','LogbooktaController@updateStatus')->name('update.statuslog');
    Route::get('/update/statuslog2','LogbooktaController@updateStatus2')->name('update.statuslog2');

    //Ganti Judul TA
    Route::resource('/judulta','TajudulController');
    //Ganti Pembimbing TA
    Route::resource('/pembimbingta','TapembimbingController');
    Route::patch('/pembimbingta/updatelama/{id}','TapembimbingController@updatelama')->name('pembimbingta.updatelama');
    //Ganti Perpanjangan
    Route::resource('/perpanjanganta','TaperpanjanganController');
});
//Route Role Dosen
Route::namespace('Dosen')->name('dosen.')->middleware('can:dosen')->group(function(){
    //Persetujuan PA
    Route::resource('kelengkapanta/persetujuanpa','PersetujuanpaController');
    Route::get('kelengkapanta/persetujuanpa/biodata/{id}','PersetujuanpaController@lihatbiodata')->name('biodata.show');
    Route::get('kelengkapanta/persetujuanpa/bebaslab/{id}','PersetujuanpaController@lihatbebaslab')->name('bebaslab.show');
    //Persetujuan Pembimbing
    Route::resource('kelengkapanta/persetujuandraft','Pembimbing\DraftController');
    Route::get('kelengkapanta/downloaddraft/{id}','Pembimbing\DraftController@downloaddraft')->name('downloaddraft');
    //Persetujuan Penguji
    Route::resource('kelengkapanta/draftpenguji','Penguji\DraftController');
});

//Route Kaprodi
Route::namespace('Kaprodi')->prefix('kaprodi')->name('kaprodi.')->middleware('can:kaprodi')->group(function(){
    //Mahasiswa
    Route::resource('listmahasiswa','MahasiswaController',['except' => ['create','store']]);
    //Kerja Praktek
    Route::resource('kerjapraktek','KpController',['except' => ['create','store']]);
    //Tugas Akhir
    Route::resource('tugasakhir','TaController',['except' => ['create','store']]);
    //Hal Pengesahan
    Route::resource('halpengesahan','HalpengesahanController',['except' => ['create','store']]);

});

//Route Koordinator KBK Sel
Route::namespace('Admin')->name('admin.')->middleware('can:koordinatorsel')->group(function(){
    //Tugas Akhir
    Route::resource('sel','Sel\SelController',['except' => ['create','store']]);

});

//Route Koordinator KBK Meka
Route::namespace('Admin')->name('admin.')->middleware('can:koordinatorsm')->group(function(){
    //Tugas Akhir
    Route::resource('meka','Meka\MekaController',['except' => ['create','store']]);

});

//Route Koordinator KBK ICT
Route::namespace('Admin')->name('admin.')->middleware('can:koordinatorict')->group(function(){
    //Tugas Akhir
    Route::resource('ict','Ict\IctController',['except' => ['create','store']]);

});

//Route Operator TA
Route::namespace('Admin')->name('opta.')->middleware('can:operatorta')->group(function(){
    //Tugas Akhir
    Route::resource('opta/ta','Opta\TaController',['except' => ['create','store']]);
    //Seminar Hasil Tugas Akhir
    Route::resource('opta/semhas','Opta\SemhasController',['except' => ['create','store']]);
    Route::get('opta/semhas/rekap/{id}','Opta\SemhasController@showRekapsemhas')->name('semhas.rekap');
    //Pendadaran Tugas Akhir
    Route::resource('opta/pendadaran','Opta\PendadaranController',['except' => ['create','store']]);
    Route::get('opta/pendadaran/rekap/{id}','Opta\PendadaranController@showRekappendadaran')->name('pendadaran.rekap');

});

//Route Kalab
Route::namespace('Admin')->name('kalab.')->middleware('can:kalab')->group(function(){
    //Bebas Lab
    Route::resource('bebaslab','Kalab\KalabController');
});



/// [] =================================== [] 
/// [] == <----- List New Route ------> == [] 
/// [] =================================== [] 

// -> Untuk Presensi Seminar Tambahan Pada Mahasiswa
Route::resource('PresensiSeminarKP','KehadiranSeminarKPController');
Route::resource('PresensiSeminarHasil','KehadiranSeminarHasilController');

// -> Untuk Lembar Pengesahan KP pada Mahasiswa
Route::resource('LembarPengesahanKP','LembarPengesahanKPController');
Route::get('LembarPengesahanKP/bagian/Proposal','LembarPengesahanKPController@lemperProposal')->name('lemperProposal');
Route::get('LembarPengesahanKP/bagian/Laporan','LembarPengesahanKPController@lemperLaporan')->name('lemperLaporan');

// -> Persetujuan dari koor KP
Route::get('LembarPengesahanKP/bagian/Setuju/{setuju}','LembarPengesahanKPController@setuju')->name('setuju');
Route::get('LembarPengesahanKP/bagian/SetujuProposal/{setuju}','LembarPengesahanKPController@setujuProposal')->name('setujuProposal');

// -> Persetujuan dari Kaprodi
Route::get('LembarPengesahanKP/Kaprodi/Setuju/{setuju}','LembarPengesahanKPController@setujuKaprodi')->name('setujuKaprodi');
Route::get('LembarPengesahanKP/Kaprodi/SetujuProposal/{setuju}','LembarPengesahanKPController@setujuKaprodiProposal')
    ->name('setujuKaprodiProposal');

// -> Redirect Dari Portal WA
Route::get('pendingKP','HomeController@pendingKP')->name('pendingKP');

// -> Untuk Batasan Jumlah Bimbingan
// Route::get('batasan','TaController@batasan_bimbingan')->name('batasan_bimbingan');
Route::resource('batasan','batasanController');
Route::resource('penguji','pengujianController');




/// [] =================================== [] 
/// [] == <----- RPL New Routes ------> == [] 
/// [] =================================== [] 
Route::get('nole','TadraftController@wordExport')->name('wordExport');
Route::get('lihat','TadraftController@lihat')->name('lihat');

Route::get('submitrpl/{id}','TaController@submitrpl')->name('submitrpl');

Route::get('ta/wisuda/cetakprofil/{id}','TadraftController@cetakProfil')->name('cetakProfil');
Route::get('ta/wisuda/dataprofil/{id}','TadraftController@dataProfil')->name('dataProfil');

// Route::get('pengujian/cetakprofil/{id}','TadraftController@pembimbingcetakProfil')->name('pembimbingcetakProfil');
// Route::get('pengujian/pengujian/dataprofilpenguji/{id}','TadraftController@pembimbingcetakProfil')->name('pembimbingcetakProfil');

Route::get('pembimbing/dataprofilpenguji/{id}','TadraftController@pembimbingdataProfilpenguji')->name('pembimbingdataProfilpenguji');
Route::get('pembimbing/cetakprofilpenguji/{id}','TadraftController@pembimbingcetakProfilpenguji')->name('pembimbingcetakProfilpenguji');

Route::get('pembimbing/cetakprofil/{id}','TadraftController@pembimbingcetakProfil')->name('pembimbingcetakProfil');
Route::get('pembimbing/dataprofil/{id}','TadraftController@pembimbingdataProfil')->name('pembimbingdataProfil');

Route::post('dua/a','TadraftController@duaA')->name('duaA');

Route::get('dua/b','TadraftController@duaBshow')->name('duaBshow');
Route::post('dua/b/kirim','TadraftController@duaB')->name('duaB');

Route::get('dua/c','TadraftController@duaCshow')->name('duaCshow');
Route::post('dua/c/kirim','TadraftController@duaC')->name('duaC');

Route::get('tiga/a','TadraftController@tigaAshow')->name('tigaAshow');
Route::post('tiga/a/kirim','TadraftController@tigaA')->name('tigaA');

Route::get('tiga/b','TadraftController@tigaBshow')->name('tigaBshow');
Route::post('tiga/b/kirim','TadraftController@tigaB')->name('tigaB');

Route::get('empat/a','TadraftController@empatAshow')->name('empatAshow');
Route::post('empat/a/kirim','TadraftController@empatA')->name('empatA');

Route::get('empat/b','TadraftController@empatBshow')->name('empatBshow');
Route::post('empat/b/kirim','TadraftController@empatB')->name('empatB');

Route::get('lima/a','TadraftController@limaAshow')->name('limaAshow');
Route::post('lima/a/kirim','TadraftController@limaA')->name('limaA');

Route::get('lima/b','TadraftController@limaBshow')->name('limaBshow');
Route::post('lima/b/kirim','TadraftController@limaB')->name('limaB');

Route::get('lima/c','TadraftController@limaCshow')->name('limaCshow');
Route::post('lima/c/kirim','TadraftController@limaC')->name('limaC');

Route::get('lima/d','TadraftController@limaDshow')->name('limaDshow');
Route::post('lima/d/kirim','TadraftController@limaD')->name('limaD');

Route::get('lima/e','TadraftController@limaEshow')->name('limaEshow');
Route::post('lima/e/kirim','TadraftController@limaE')->name('limaE');

Route::get('lima/f','TadraftController@limaFshow')->name('limaFshow');
Route::post('lima/f/kirim','TadraftController@limaF')->name('limaF');

Route::get('enam/a','TadraftController@enamAshow')->name('enamAshow');
Route::post('enam/a/kirim','TadraftController@enamA')->name('enamA');

Route::get('enam/b','TadraftController@enamBshow')->name('enamBshow');
Route::post('enam/b/kirim','TadraftController@enamB')->name('enamB');

Route::get('enam/c','TadraftController@enamCshow')->name('enamCshow');
Route::post('enam/c/kirim','TadraftController@enamC')->name('enamC');

// untuk menghapus data


Route::post('hapus2a/{id}','TadraftController@hapus2a')->name('hapus2a');
Route::post('hapus2b/{id}','TadraftController@hapus2b')->name('hapus2b');
Route::post('hapus2c/{id}','TadraftController@hapus2c')->name('hapus2c');

Route::post('hapus3a/{id}','TadraftController@hapus3a')->name('hapus3a');
Route::post('hapus3b/{id}','TadraftController@hapus3b')->name('hapus3b');

Route::post('hapus4a/{id}','TadraftController@hapus4a')->name('hapus4a');
Route::post('hapus4b/{id}','TadraftController@hapus4b')->name('hapus4b');

Route::post('hapus5a/{id}','TadraftController@hapus5a')->name('hapus5a');
Route::post('hapus5b/{id}','TadraftController@hapus5b')->name('hapus5b');
Route::post('hapus5c/{id}','TadraftController@hapus5c')->name('hapus5c');
Route::post('hapus5d/{id}','TadraftController@hapus5d')->name('hapus5d');
Route::post('hapus5e/{id}','TadraftController@hapus5e')->name('hapus5e');
Route::post('hapus5f/{id}','TadraftController@hapus5f')->name('hapus5f');

Route::post('hapus6a/{id}','TadraftController@hapus6a')->name('hapus6a');
Route::post('hapus6b/{id}','TadraftController@hapus6b')->name('hapus6b');
Route::post('hapus6c/{id}','TadraftController@hapus6c')->name('hapus6c');

// untuk mengedit data
Route::post('edit2a/{id}','TadraftController@edit2a')->name('edit2a');
Route::post('edit2b/{id}','TadraftController@edit2b')->name('edit2b');
Route::post('edit2c/{id}','TadraftController@edit2c')->name('edit2c');

Route::post('edit3a/{id}','TadraftController@edit3a')->name('edit3a');
Route::post('edit3b/{id}','TadraftController@edit3b')->name('edit3b');

Route::post('edit4a/{id}','TadraftController@edit4a')->name('edit4a');
Route::post('edit4b/{id}','TadraftController@edit4b')->name('edit4b');

Route::post('edit5a/{id}','TadraftController@edit5a')->name('edit5a');
Route::post('edit5b/{id}','TadraftController@edit5b')->name('edit5b');
Route::post('edit5c/{id}','TadraftController@edit5c')->name('edit5c');
Route::post('edit5d/{id}','TadraftController@edit5d')->name('edit5d');
Route::post('edit5e/{id}','TadraftController@edit5e')->name('edit5e');
Route::post('edit5f/{id}','TadraftController@edit5f')->name('edit5f');

Route::post('edit6a/{id}','TadraftController@edit6a')->name('edit6a');
Route::post('edit6b/{id}','TadraftController@edit6b')->name('edit6b');
Route::post('edit6c/{id}','TadraftController@edit6c')->name('edit6c');

Route::post('ubah2a/{id}','TadraftController@ubah2a')->name('ubah2a');
Route::post('ubah2b/{id}','TadraftController@ubah2b')->name('ubah2b');
Route::post('ubah2c/{id}','TadraftController@ubah2c')->name('ubah2c');

Route::post('ubah3a/{id}','TadraftController@ubah3a')->name('ubah3a');
Route::post('ubah3b/{id}','TadraftController@ubah3b')->name('ubah3b');

Route::post('ubah4a/{id}','TadraftController@ubah4a')->name('ubah4a');
Route::post('ubah4b/{id}','TadraftController@ubah4b')->name('ubah4b');

Route::post('ubah5a/{id}','TadraftController@ubah5a')->name('ubah5a');
Route::post('ubah5b/{id}','TadraftController@ubah5b')->name('ubah5b');
Route::post('ubah5c/{id}','TadraftController@ubah5c')->name('ubah5c');
Route::post('ubah5d/{id}','TadraftController@ubah5d')->name('ubah5d');
Route::post('ubah5e/{id}','TadraftController@ubah5e')->name('ubah5e');
Route::post('ubah5f/{id}','TadraftController@ubah5f')->name('ubah5f');

Route::post('ubah6a/{id}','TadraftController@ubah6a')->name('ubah6a');
Route::post('ubah6b/{id}','TadraftController@ubah6b')->name('ubah6b');
Route::post('ubah6c/{id}','TadraftController@ubah6c')->name('ubah6c');



// untuk Komentar
Route::post('komentar2a/{id}','TadraftController@komentar2a')->name('komentar2a');
Route::post('komentar2b/{id}','TadraftController@komentar2b')->name('komentar2b');
Route::post('komentar2c/{id}','TadraftController@komentar2c')->name('komentar2c');

Route::post('komentar3a/{id}','TadraftController@komentar3a')->name('komentar3a');
Route::post('komentar3b/{id}','TadraftController@komentar3b')->name('komentar3b');

Route::post('komentar4a/{id}','TadraftController@komentar4a')->name('komentar4a');
Route::post('komentar4b/{id}','TadraftController@komentar4b')->name('komentar4b');

Route::post('komentar5a/{id}','TadraftController@komentar5a')->name('komentar5a');
Route::post('komentar5b/{id}','TadraftController@komentar5b')->name('komentar5b');
Route::post('komentar5c/{id}','TadraftController@komentar5c')->name('komentar5c');
Route::post('komentar5d/{id}','TadraftController@komentar5d')->name('komentar5d');
Route::post('komentar5e/{id}','TadraftController@komentar5e')->name('komentar5e');
Route::post('komentar5f/{id}','TadraftController@komentar5f')->name('komentar5f');

Route::post('komentar6a/{id}','TadraftController@komentar6a')->name('komentar6a');
Route::post('komentar6b/{id}','TadraftController@komentar6b')->name('komentar6b');
Route::post('komentar6c/{id}','TadraftController@komentar6c')->name('komentar6c');

// penguji

Route::get('ta/pengajuan/pendaftaran/{id}','TaController@index')->name('index');
Route::get('pengujianwawancara/{id}','TadraftController@showNilaiPenguji')->name('showNilaiPenguji');
Route::post('paraPenguji','TaController@paraPenguji')->name('paraPenguji');

Route::post('setornilaipembimbing/{id}','TaController@nilaiPembimbing')->name('nilaiPembimbing');
Route::post('setornilaipenguji/{id}','TaController@nilaiPenguji')->name('nilaiPenguji');

// Admin
Route::get('tutuprpl','TaController@tutuprpl')->name('tutuprpl');
Route::get('bukarpl','TaController@bukarpl')->name('bukarpl');



