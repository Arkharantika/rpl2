<?php

namespace App\Http\Controllers;

use App\Models\Ta;
use App\Models\Mahasiswa;
use App\Models\Pendadaran;
use App\Models\Biodataalumni;
use App\Models\Pembimbing;
use App\Models\Penguji;
use App\Models\Jabatan;
use App\Models\Halpengesahan;
use App\Models\Exitsurvey;
use App\Models\Bebaslab;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use PDF;
use PhpOffice\PhpWord\TemplateProcessor;

// Tambahan
use App\Models\dua_a;
use App\Models\dua_b;
use App\Models\dua_c;
use App\Models\tiga_a;
use App\Models\tiga_b;
use App\Models\empat_a;
use App\Models\empat_b;
use App\Models\lima_a;
use App\Models\lima_b;
use App\Models\lima_c;
use App\Models\lima_d;
use App\Models\lima_e;
use App\Models\lima_f;
use App\Models\enam_a;
use App\Models\enam_b;
use App\Models\enam_c;
use App\Models\tabelnilai;
use App\Models\tabelsubmitrpl;

class TadraftController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nim = Auth::user()->nim;
        $data = Pendadaran::setuju($nim)->first();
        // dd($halpengesahan);
        if($data != null){
            $bio = Biodataalumni::where('mahasiswa_id',$data->mahasiswa_id)->first();
            $exitsurvey = Exitsurvey::where('mahasiswa_id',$data->mahasiswa_id)->first();
            $halpengesahan = Halpengesahan::where('mahasiswa_id',$data->mahasiswa_id)->first();
            $bebaslab = Bebaslab::where('mahasiswa_id',$data->mahasiswa_id)->first();
            return view('ta.draft.index',compact('data','bio','exitsurvey','halpengesahan','bebaslab'));
        }
        return view('errors.pendadaran');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'file_draftta' => 'required|file|mimes:pdf|max:20480',
            'file_sourcecode' => 'required|file|mimes:zip|max:307200',
		]);
        // dd($data);
		// menyimpan data file yang diupload ke variabel $file
        $file = $request->file('file_draftta');
        $sourcecode = $request->file('file_sourcecode');
        //$id = $request->id;
		$nama_file = $request->nim."_DraftTA".".".$file->getClientOriginalExtension();
		$nama_sourcecode = $request->nim."_SourcecodeTA".".".$sourcecode->getClientOriginalExtension();
 
      	// isi dengan nama folder tempat kemana file diupload
		$tujuan_upload = 'file_draftta';
		$sourcecode_upload = 'file_sourcecode';
        $file->move($tujuan_upload,$nama_file);
        $sourcecode->move($sourcecode_upload,$nama_sourcecode);

        Ta::where('id', $id)->update([
            'doc_ta' => $nama_file,
            'sourcecode_ta' => $nama_sourcecode,
        ]);

        Flight::update(['delayed' => 1]);
 
		return redirect(route('ta.wisuda.index'))->with('message','Dokumen TA Berhasil diupload!');
    }

    public function bebaslab($id){
        $data = Mahasiswa::find($id);
        $pembimbing = Dosen::find($data->pem_akademik);
        $kalabsel = Jabatan::kalabsel();
        $kalabik = Jabatan::kalabik();
        $kalabele = Jabatan::kalabele();
        $kalabtele = Jabatan::kalabtele();
        $laboranele = Jabatan::laboranele();
        $kalabkj = Jabatan::kalabkj();
        $bebaslab = Bebaslab::where('mahasiswa_id',$id)->first();
        // dd($bebaslab);
        $config = [
            'format' => 'A4-P', // Portrait
             'margin_left'          => 15,
             'margin_right'         => 15,
             'margin_top'           => 35,
            // 'margin_bottom'        => 25,
          ];

          $monthList = array(
              'Jan' => 'Januari',
              'Feb' => 'Februari',
              'Mar' => 'Maret',
              'Apr' => 'April',
              'May' => 'Mei',
              'Jun' => 'Juni',
              'Jul' => 'Juli',
              'Aug' => 'Agustus',
              'Sep' => 'September',
              'Oct' => 'Oktober',
              'Nov' => 'November',
              'Dec' => 'Desember',
          );
        $pdf = PDF::loadview('ta/draft/bebaslab',compact('data','kalabsel','kalabik','kalabkj','kalabtele','kalabele',
        'laboranele','pembimbing','bebaslab','monthList'),[],$config);
        return $pdf->stream();
    }

    public function halpengesahan($id){
        $data = Mahasiswa::find($id);
        $ta = Ta::where('mahasiswa_id',$id)->get()->last();
        $pendadaran = Pendadaran::where('ta_id',$ta->id)->get()->last();
        $pem1 = Pembimbing::pembimbing($ta->id)->first();
        $pem2 = Pembimbing::pembimbing($ta->id)->last();
        $uji1 = Penguji::pengujipendadaran($ta->id)->first();
        $uji2 = Penguji::pengujipendadaran($ta->id)->last();
        $halpengesahan = Halpengesahan::where('mahasiswa_id',$id)->first();
        $kaprodi = Jabatan::kaprodi();
        $koorta = Jabatan::ta();
        // dd($kaprodi);
        $config = [
            'format' => 'A4-P', // Portrait
             'margin_left'          => 40,
             'margin_right'         => 30,
             'margin_top'           => 30,
             'margin_footer'        => 25,
            // 'margin_bottom'        => 25,
          ];
          $dayList = array(
              'Sun' => 'Minggu',
              'Mon' => 'Senin',
              'Tue' => 'Selasa',
              'Wed' => 'Rabu',
              'Thu' => 'Kamis',
              'Fri' => 'Jumat',
              'Sat' => 'Sabtu'
          );
          $monthList = array(
              'Jan' => 'Januari',
              'Feb' => 'Februari',
              'Mar' => 'Maret',
              'Apr' => 'April',
              'May' => 'Mei',
              'Jun' => 'Juni',
              'Jul' => 'Juli',
              'Aug' => 'Agustus',
              'Sep' => 'September',
              'Oct' => 'Oktober',
              'Nov' => 'November',
              'Dec' => 'Desember',
          );
        $pdf = PDF::loadview('ta/draft/halpengesahan',compact('data','pem1','pem2','uji1','uji2','kaprodi','koorta','halpengesahan','ta','dayList','monthList','pendadaran'),[],$config);
        return $pdf->stream();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cetakProfil($id)
    {
        $no = 0;
        $data = User::find($id);
        $kampretos = $data->nim;
        $mahasiswa = Mahasiswa::where('nim',$kampretos)->get()->last();
        $duaA = dua_a::where('nim',$kampretos)->get();
        $duaB = dua_b::where('nim',$kampretos)->get();
        $duaC = dua_c::where('nim',$kampretos)->get();
        $tigaA = tiga_a::where('nim',$kampretos)->get();
        $tigaB = tiga_b::where('nim',$kampretos)->get();
        $empatA = empat_a::where('nim',$kampretos)->get();
        $empatB = empat_b::where('nim',$kampretos)->get();
        $limaA = lima_a::where('nim',$kampretos)->get();
        $limaB = lima_b::where('nim',$kampretos)->get();
        $limaC = lima_c::where('nim',$kampretos)->get();
        $limaD = lima_d::where('nim',$kampretos)->get();
        $limaE = lima_e::where('nim',$kampretos)->get();
        $limaF = lima_f::where('nim',$kampretos)->get();
        $enamA = enam_a::where('nim',$kampretos)->get();
        $enamB = enam_b::where('nim',$kampretos)->get();
        $enamC = enam_c::where('nim',$kampretos)->get();
        $config = [
            'format' => 'A4-L', // Portrait
             'margin_left'          => 10,
             'margin_right'         => 10,
             'margin_top'           => 15,
            // 'margin_bottom'        => 25,
          ];

          $monthList = array(
              'Jan' => 'Januari',
              'Feb' => 'Februari',
              'Mar' => 'Maret',
              'Apr' => 'April',
              'May' => 'Mei',
              'Jun' => 'Juni',
              'Jul' => 'Juli',
              'Aug' => 'Agustus',
              'Sep' => 'September',
              'Oct' => 'Oktober',
              'Nov' => 'November',
              'Dec' => 'Desember',
          );
        $pdf = PDF::loadview('cetakProfil',compact('enamC','enamB','enamA','limaF','limaE','limaD','limaC','limaB','limaA','empatB','empatA','tigaB','tigaA','duaC','duaB','no','duaA','mahasiswa','monthList'),[],$config);
        return $pdf->stream();
    }

    public function pembimbingcetakProfilpenguji($id)
    {
        // return $id;

        // $data = Dosen::bimbingan(Auth::user()->nim)->where('id',$id)->get()->last();
        // $data = User::where('nim',$id)->get();

        // return $data;
        $check = tabelsubmitrpl::where('nim',$id)->get()->first();
        $check2 = $check->status;
        if($check2 == 0){
            return back()->with('alert','Maaf, Mahasiswa Belum Submit RPL !!');
        }

        $no = 0;
        $kampretos = $id;
        $mahasiswa = Mahasiswa::where('nim',$kampretos)->get()->last();
        $duaA = dua_a::where('nim',$kampretos)->get();
        $duaB = dua_b::where('nim',$kampretos)->get();
        $duaC = dua_c::where('nim',$kampretos)->get();
        $tigaA = tiga_a::where('nim',$kampretos)->get();
        $tigaB = tiga_b::where('nim',$kampretos)->get();
        $empatA = empat_a::where('nim',$kampretos)->get();
        $empatB = empat_b::where('nim',$kampretos)->get();
        $limaA = lima_a::where('nim',$kampretos)->get();
        $limaB = lima_b::where('nim',$kampretos)->get();
        $limaC = lima_c::where('nim',$kampretos)->get();
        $limaD = lima_d::where('nim',$kampretos)->get();
        $limaE = lima_e::where('nim',$kampretos)->get();
        $limaF = lima_f::where('nim',$kampretos)->get();
        $enamA = enam_a::where('nim',$kampretos)->get();
        $enamB = enam_b::where('nim',$kampretos)->get();
        $enamC = enam_c::where('nim',$kampretos)->get();
        $config = [
            'format' => 'A4-L', // Portrait
             'margin_left'          => 10,
             'margin_right'         => 10,
             'margin_top'           => 15,
            // 'margin_bottom'        => 25,
          ];

          $monthList = array(
              'Jan' => 'Januari',
              'Feb' => 'Februari',
              'Mar' => 'Maret',
              'Apr' => 'April',
              'May' => 'Mei',
              'Jun' => 'Juni',
              'Jul' => 'Juli',
              'Aug' => 'Agustus',
              'Sep' => 'September',
              'Oct' => 'Oktober',
              'Nov' => 'November',
              'Dec' => 'Desember',
          );
        $pdf = PDF::loadview('cetakProfil',compact('enamC','enamB','enamA','limaF','limaE','limaD','limaC','limaB','limaA','empatB','empatA','tigaB','tigaA','duaC','duaB','no','duaA','mahasiswa','monthList'),[],$config);
        return $pdf->stream();
    }
    public function pembimbingcetakProfil($id)
    {
        // return $id;

        // $data = Dosen::bimbingan(Auth::user()->nim)->where('id',$id)->get()->last();
        // $data = User::where('nim',$id)->get();

        // return $data;

        $no = 0;
        $kampretos = $id;
        $mahasiswa = Mahasiswa::where('nim',$kampretos)->get()->last();
        $duaA = dua_a::where('nim',$kampretos)->get();
        $duaB = dua_b::where('nim',$kampretos)->get();
        $duaC = dua_c::where('nim',$kampretos)->get();
        $tigaA = tiga_a::where('nim',$kampretos)->get();
        $tigaB = tiga_b::where('nim',$kampretos)->get();
        $empatA = empat_a::where('nim',$kampretos)->get();
        $empatB = empat_b::where('nim',$kampretos)->get();
        $limaA = lima_a::where('nim',$kampretos)->get();
        $limaB = lima_b::where('nim',$kampretos)->get();
        $limaC = lima_c::where('nim',$kampretos)->get();
        $limaD = lima_d::where('nim',$kampretos)->get();
        $limaE = lima_e::where('nim',$kampretos)->get();
        $limaF = lima_f::where('nim',$kampretos)->get();
        $enamA = enam_a::where('nim',$kampretos)->get();
        $enamB = enam_b::where('nim',$kampretos)->get();
        $enamC = enam_c::where('nim',$kampretos)->get();
        $config = [
            'format' => 'A4-L', // Portrait
             'margin_left'          => 10,
             'margin_right'         => 10,
             'margin_top'           => 15,
            // 'margin_bottom'        => 25,
          ];

          $monthList = array(
              'Jan' => 'Januari',
              'Feb' => 'Februari',
              'Mar' => 'Maret',
              'Apr' => 'April',
              'May' => 'Mei',
              'Jun' => 'Juni',
              'Jul' => 'Juli',
              'Aug' => 'Agustus',
              'Sep' => 'September',
              'Oct' => 'Oktober',
              'Nov' => 'November',
              'Dec' => 'Desember',
          );
        $pdf = PDF::loadview('cetakProfil',compact('enamC','enamB','enamA','limaF','limaE','limaD','limaC','limaB','limaA','empatB','empatA','tigaB','tigaA','duaC','duaB','no','duaA','mahasiswa','monthList'),[],$config);
        return $pdf->stream();
    }

    public function dataProfil($id)
    {
        $no = 0;
        $data = User::find($id);
        $kampretos = $data->nim;
        $mahasiswa = Mahasiswa::where('nim',$kampretos)->get()->last();
        $datanya = dua_a::where('nim',$kampretos)->get();
        $duaA = dua_a::where('nim',$kampretos)->get();
        $duaB = dua_b::where('nim',$kampretos)->get();
        $duaC = dua_c::where('nim',$kampretos)->get();
        $tigaA = tiga_a::where('nim',$kampretos)->get();
        $tigaB = tiga_b::where('nim',$kampretos)->get();
        $empatA = empat_a::where('nim',$kampretos)->get();
        $empatB = empat_b::where('nim',$kampretos)->get();
        $limaA = lima_a::where('nim',$kampretos)->get();
        $limaB = lima_b::where('nim',$kampretos)->get();
        $limaC = lima_c::where('nim',$kampretos)->get();
        $limaD = lima_d::where('nim',$kampretos)->get();
        $limaE = lima_e::where('nim',$kampretos)->get();
        $limaF = lima_f::where('nim',$kampretos)->get();
        $enamA = enam_a::where('nim',$kampretos)->get();
        $enamB = enam_b::where('nim',$kampretos)->get();
        $enamC = enam_c::where('nim',$kampretos)->get();
        $config = [
            'format' => 'A4-L', // Portrait
             'margin_left'          => 10,
             'margin_right'         => 10,
             'margin_top'           => 15,
            // 'margin_bottom'        => 25,
          ];

          $monthList = array(
              'Jan' => 'Januari',
              'Feb' => 'Februari',
              'Mar' => 'Maret',
              'Apr' => 'April',
              'May' => 'Mei',
              'Jun' => 'Juni',
              'Jul' => 'Juli',
              'Aug' => 'Agustus',
              'Sep' => 'September',
              'Oct' => 'Oktober',
              'Nov' => 'November',
              'Dec' => 'Desember',
          );
          return view('dataProfil',compact('datanya','enamC','enamB','enamA','limaF','limaE','limaD','limaC','limaB','limaA','empatB','empatA','tigaB','tigaA','duaC','duaB','no','duaA','mahasiswa','monthList'),[],$config);
        // return $pdf->stream();
    }
    public function pembimbingdataProfil($id)
    {
        // $data = User::where('nim',$id)->get();
        // return $data;

        $no = 0;
        $kampretos = $id;
        $mahasiswa = Mahasiswa::where('nim',$kampretos)->get()->last();
        $datanya = dua_a::where('nim',$kampretos)->get();
        $duaA = dua_a::where('nim',$kampretos)->get();
        $duaB = dua_b::where('nim',$kampretos)->get();
        $duaC = dua_c::where('nim',$kampretos)->get();
        $tigaA = tiga_a::where('nim',$kampretos)->get();
        $tigaB = tiga_b::where('nim',$kampretos)->get();
        $empatA = empat_a::where('nim',$kampretos)->get();
        $empatB = empat_b::where('nim',$kampretos)->get();
        $limaA = lima_a::where('nim',$kampretos)->get();
        $limaB = lima_b::where('nim',$kampretos)->get();
        $limaC = lima_c::where('nim',$kampretos)->get();
        $limaD = lima_d::where('nim',$kampretos)->get();
        $limaE = lima_e::where('nim',$kampretos)->get();
        $limaF = lima_f::where('nim',$kampretos)->get();
        $enamA = enam_a::where('nim',$kampretos)->get();
        $enamB = enam_b::where('nim',$kampretos)->get();
        $enamC = enam_c::where('nim',$kampretos)->get();
        $config = [
            'format' => 'A4-L', // Portrait
             'margin_left'          => 10,
             'margin_right'         => 10,
             'margin_top'           => 15,
            // 'margin_bottom'        => 25,
          ];

          $monthList = array(
              'Jan' => 'Januari',
              'Feb' => 'Februari',
              'Mar' => 'Maret',
              'Apr' => 'April',
              'May' => 'Mei',
              'Jun' => 'Juni',
              'Jul' => 'Juli',
              'Aug' => 'Agustus',
              'Sep' => 'September',
              'Oct' => 'Oktober',
              'Nov' => 'November',
              'Dec' => 'Desember',
          );
          return view('dataProfil',compact('datanya','enamC','enamB','enamA','limaF','limaE','limaD','limaC','limaB','limaA','empatB','empatA','tigaB','tigaA','duaC','duaB','no','duaA','mahasiswa','monthList'),[],$config);
        // return $pdf->stream();
    }

    public function pembimbingdataProfilpenguji($id)
    {
        // $data = User::where('nim',$id)->get();
        // return $data;
        // $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        // // $nimnya = $mhs->nim;
        $check = tabelsubmitrpl::where('nim',$id)->get()->first();
        $check2 = $check->status;
        if($check2 == 0){
            return back()->with('alert','Maaf, Mahasiswa Belum Submit RPL !!');
        }
        // return $check2;

        $no = 0;
        $kampretos = $id;
        $mahasiswa = Mahasiswa::where('nim',$kampretos)->get()->last();
        $datanya = dua_a::where('nim',$kampretos)->get();
        $duaA = dua_a::where('nim',$kampretos)->get();
        $duaB = dua_b::where('nim',$kampretos)->get();
        $duaC = dua_c::where('nim',$kampretos)->get();
        $tigaA = tiga_a::where('nim',$kampretos)->get();
        $tigaB = tiga_b::where('nim',$kampretos)->get();
        $empatA = empat_a::where('nim',$kampretos)->get();
        $empatB = empat_b::where('nim',$kampretos)->get();
        $limaA = lima_a::where('nim',$kampretos)->get();
        $limaB = lima_b::where('nim',$kampretos)->get();
        $limaC = lima_c::where('nim',$kampretos)->get();
        $limaD = lima_d::where('nim',$kampretos)->get();
        $limaE = lima_e::where('nim',$kampretos)->get();
        $limaF = lima_f::where('nim',$kampretos)->get();
        $enamA = enam_a::where('nim',$kampretos)->get();
        $enamB = enam_b::where('nim',$kampretos)->get();
        $enamC = enam_c::where('nim',$kampretos)->get();
        $config = [
            'format' => 'A4-L', // Portrait
             'margin_left'          => 10,
             'margin_right'         => 10,
             'margin_top'           => 15,
            // 'margin_bottom'        => 25,
          ];

          $monthList = array(
              'Jan' => 'Januari',
              'Feb' => 'Februari',
              'Mar' => 'Maret',
              'Apr' => 'April',
              'May' => 'Mei',
              'Jun' => 'Juni',
              'Jul' => 'Juli',
              'Aug' => 'Agustus',
              'Sep' => 'September',
              'Oct' => 'Oktober',
              'Nov' => 'November',
              'Dec' => 'Desember',
          );
          return view('dataProfilpenguji',compact('datanya','enamC','enamB','enamA','limaF','limaE','limaD','limaC','limaB','limaA','empatB','empatA','tigaB','tigaA','duaC','duaB','no','duaA','mahasiswa','monthList'),[],$config);
        // return $pdf->stream();
    }

    public function duaA(Request $request){

        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;

        dua_a::create([
            'nim' => $nimnya,
            'gelar_tahun' => $request->gelar,
            'jurusan' => $request->jurusan,
            'nama_alamat_telp' => $request->nama_univ,
            'sks' => $request->sks,
            'judul_skripsi' => $request->judul,
            'nama_pem' => $request->nama_pem,
            'ijazah' => $request->ijazah,
            'transkrip' => $request->transkrip,
        ]);

        return redirect('PresensiSeminarKP')->with('message','Data Berhasil Ditambah !!');

    }

    public function duaBshow(){
        // return 0;
        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;
        $datanya = dua_b::where('nim',$nimnya)->get();

        // return view('duaB',compact('datanya'));

        $check = tabelsubmitrpl::where('nim',$nimnya)->get()->first();

        // if($check == null){
        //     return view('errors.errorpem'); 
        $submitan = tabelsubmitrpl::where('id',1)->get()->first();
        if($submitan->status == 0){
            return view('errors.errorpem'); 
        }
        if($check->status == 1){
            return view('errors.errorpem'); 
        }
        // }else{
        // }
        return view('duaB',compact('datanya'));

        

    }

    public function duaB(Request $request){

        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;

        dua_b::create([
            'nim' => $nimnya,
            'jenis_pendidikan' => $request->jenis,
            'waktu' => $request->waktu,
            'nama_alamat_telp' => $request->nama_alamat,
            'materi' => $request->materi,
            'lamanya' => $request->lama,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        
        // return $request;
        return redirect('dua/b')->with('message','Data Berhasil Ditambah !!');

    }

    public function duaCshow(){
        // return 0;
        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;
        $datanya = dua_c::where('nim',$nimnya)->get();

        $check = tabelsubmitrpl::where('nim',$nimnya)->get()->first();

        // if($check == null){
        //     return view('errors.errorpem'); 
        // }else{
            
        // }
        $submitan = tabelsubmitrpl::where('id',1)->get()->first();
        if($submitan->status == 0){
            return view('errors.errorpem'); 
        }
        if($check->status == 1){
            return view('errors.errorpem'); 
        }
        return view('duaC',compact('datanya'));

    }

    public function duaC(Request $request){

        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;

        dua_c::create([
            'nim' => $nimnya,
            'jenis_pendidikan' => $request->jenis,
            'waktu' => $request->waktu,
            'nama_alamat_telp' => $request->nama_alamat,
            'materi' => $request->materi,
            'lamanya' => $request->lama,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        
        // return $request;
        return redirect('dua/c')->with('message','Data Berhasil Ditambah !!');

    }
    
    public function tigaAshow(){
        // return 0;
        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;
        $datanya = tiga_a::where('nim',$nimnya)->get();
        
        $check = tabelsubmitrpl::where('nim',$nimnya)->get()->first();

        // if($check == null){
        //     return view('errors.errorpem'); 
        // }else{
            
        // }
        $submitan = tabelsubmitrpl::where('id',1)->get()->first();
        if($submitan->status == 0){
            return view('errors.errorpem'); 
        }
        if($check->status == 1){
            return view('errors.errorpem'); 
        }
        return view('tigaA',compact('datanya'));
        // return $nimnya;
        
    }

    public function tigaA(Request $request){

        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;

        // return $nimnya;

       tiga_a::create([
            'nim' => $nimnya,
            'bentuk_kegiatan' => $request->jenis,
            'waktu' => $request->waktu,
            'nama_alamat_telp' => $request->nama_alamat,
            'judul' => $request->materi,
            'daftar' => $request->lama,
            // 'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        
        // // return $request;
        return redirect('tiga/a')->with('message','Data Berhasil Ditambah !!');

    }

    public function tigaBshow(){
        // return 0;
        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;
        $datanya = tiga_b::where('nim',$nimnya)->get();
        
        $check = tabelsubmitrpl::where('nim',$nimnya)->get()->first();

        // if($check == null){
        //     return view('errors.errorpem'); 
        // }else{
            
        // }
        $submitan = tabelsubmitrpl::where('id',1)->get()->first();
        if($submitan->status == 0){
            return view('errors.errorpem'); 
        }
        if($check->status == 1){
            return view('errors.errorpem'); 
        }
        return view('tigaB',compact('datanya'));
        // return $nimnya;
        
    }

    public function tigaB(Request $request){

        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;

        // return $nimnya;

       tiga_b::create([
            'nim' => $nimnya,
            'bentuk_kegiatan' => $request->jenis,
            'waktu' => $request->waktu,
            'nama_alamat_telp' => $request->nama_alamat,
            'judul' => $request->materi,
            'daftar' => $request->lama,
            // 'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        
        // // return $request;
        return redirect('tiga/b')->with('message','Data Berhasil Ditambah !!');

    }

    public function empatAshow(){
        // return 0;
        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;
        $datanya = empat_a::where('nim',$nimnya)->get();
        
        $check = tabelsubmitrpl::where('nim',$nimnya)->get()->first();

        // if($check == null){
        //     return view('errors.errorpem'); 
        // }else{
            
        // }
        $submitan = tabelsubmitrpl::where('id',1)->get()->first();
        if($submitan->status == 0){
            return view('errors.errorpem'); 
        }
        if($check->status == 1){
            return view('errors.errorpem'); 
        }
        return view('empatA',compact('datanya'));
        // return $nimnya;
        
    }

    public function empatA(Request $request){

        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;

        // return $nimnya;

       empat_a::create([
            'nim' => $nimnya,
            'jenis_bidang' => $request->jenis,
            'waktu' => $request->waktu,
            'nama_alamat_telp' => $request->nama_alamat,
            'jabatan' => $request->materi,
            'uraian' => $request->lama,
            // 'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        
        // // return $request;
        return redirect('empat/a')->with('message','Data Berhasil Ditambah !!');

    }

    public function empatBshow(){
        // return 0;
        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;
        $datanya = empat_b::where('nim',$nimnya)->get();
        
        $check = tabelsubmitrpl::where('nim',$nimnya)->get()->first();

        // if($check == null){
        //     return view('errors.errorpem'); 
        // }else{
            $submitan = tabelsubmitrpl::where('id',1)->get()->first();
        if($submitan->status == 0){
            return view('errors.errorpem'); 
        }
            if($check->status == 1){
                return view('errors.errorpem'); 
            }
        // }
        return view('empatB',compact('datanya'));
        // return $nimnya;
        
    }

    public function empatB(Request $request){

        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;

        // return $nimnya;

       empat_b::create([
            'nim' => $nimnya,
            'jenis_bidang' => $request->jenis,
            'waktu' => $request->waktu,
            'nama_alamat_telp' => $request->nama_alamat,
            'jabatan' => $request->materi,
            'uraian' => $request->lama,
            // 'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        
        // // return $request;
        return redirect('empat/b')->with('message','Data Berhasil Ditambah !!');

    }

    public function limaAshow(){
        // return 0;
        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;
        $datanya = lima_a::where('nim',$nimnya)->get();
        
        $check = tabelsubmitrpl::where('nim',$nimnya)->get()->first();

        // if($check == null){
        //     return view('errors.errorpem'); 
        // }else{
            
        // }
        $submitan = tabelsubmitrpl::where('id',1)->get()->first();
        if($submitan->status == 0){
            return view('errors.errorpem'); 
        }
        if($check->status == 1){
            return view('errors.errorpem'); 
        }
        return view('limaA',compact('datanya'));
        // return $nimnya;
        
    }

    public function limaA(Request $request){

        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;

        // return $nimnya;

       lima_a::create([
            'nim' => $nimnya,
            'topik' => $request->topik,
            'judul' => $request->judul,
            'nama_lembaga' => $request->nama_alamat,
            'kedudukan' => $request->kedudukan,
            'kedudukan_penulisan' => $request->kedudukan_penulisan,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        
        // // return $request;
        return redirect('lima/a')->with('message','Data Berhasil Ditambah !!');

    }

    public function limaBshow(){
        // return 0;
        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;
        $datanya = lima_b::where('nim',$nimnya)->get();
        
        $check = tabelsubmitrpl::where('nim',$nimnya)->get()->first();

        // if($check == null){
        //     return view('errors.errorpem'); 
        // }else{
            
        // }
        $submitan = tabelsubmitrpl::where('id',1)->get()->first();
        if($submitan->status == 0){
            return view('errors.errorpem'); 
        }
        if($check->status == 1){
            return view('errors.errorpem'); 
        }
        return view('limaB',compact('datanya'));
        // return $nimnya;
        
    }

    public function limaB(Request $request){

        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;

        // return $nimnya;

       lima_b::create([
            'nim' => $nimnya,
            'topik' => $request->topik,
            'judul' => $request->judul,
            'nama_lembaga' => $request->nama_alamat,
            'kedudukan' => $request->kedudukan,
            // 'kedudukan_penulisan' => $request->kedudukan_penulisan,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        
        // // return $request;
        return redirect('lima/b')->with('message','Data Berhasil Ditambah !!');

    }

    public function limaCshow(){
        // return 0;
        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;
        $datanya = lima_c::where('nim',$nimnya)->get();
        
        $check = tabelsubmitrpl::where('nim',$nimnya)->get()->first();

        // if($check == null){
        //     return view('errors.errorpem'); 
        // }else{
            
        // }
        $submitan = tabelsubmitrpl::where('id',1)->get()->first();
        if($submitan->status == 0){
            return view('errors.errorpem'); 
        }
        if($check->status == 1){
            return view('errors.errorpem'); 
        }
        return view('limaC',compact('datanya'));
        // return $nimnya;
        
    }

    public function limaC(Request $request){

        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;

        // return $nimnya;

       lima_c::create([
            'nim' => $nimnya,
            'judul' => $request->judul,
            'waktu' => $request->waktu,
            'nama_lembaga' => $request->nama_alamat,
            'jumlah' => $request->kedudukan,
            'jenis' => $request->kedudukan_penulisan,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        
        // // return $request;
        return redirect('lima/c')->with('message','Data Berhasil Ditambah !!');

    }

    public function limaDshow(){
        // return 0;
        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;
        $datanya = lima_d::where('nim',$nimnya)->get();
        
        $check = tabelsubmitrpl::where('nim',$nimnya)->get()->first();

        // if($check == null){
        //     return view('errors.errorpem'); 
        // }else{
            $submitan = tabelsubmitrpl::where('id',1)->get()->first();
        if($submitan->status == 0){
            return view('errors.errorpem'); 
        }
            if($check->status == 1){
                return view('errors.errorpem'); 
            }
        // }
        return view('limaD',compact('datanya'));
        // return $nimnya;
        
    }

    public function limaD(Request $request){

        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;

        // return $nimnya;

       lima_d::create([
            'nim' => $nimnya,
            'jenis' => $request->judul,
            'waktu' => $request->waktu,
            'nama_lembaga' => $request->nama_alamat,
            'jumlah' => $request->kedudukan,
            'lamanya' => $request->kedudukan_penulisan,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        
        // // return $request;
        return redirect('lima/d')->with('message','Data Berhasil Ditambah !!');

    }

    public function limaEshow(){
        // return 0;
        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;
        $datanya = lima_e::where('nim',$nimnya)->get();
        
        $check = tabelsubmitrpl::where('nim',$nimnya)->get()->first();

        // if($check == null){
        //     return view('errors.errorpem'); 
        // }else{
            
        // }
        $submitan = tabelsubmitrpl::where('id',1)->get()->first();
        if($submitan->status == 0){
            return view('errors.errorpem'); 
        }
        if($check->status == 1){
            return view('errors.errorpem'); 
        }
        return view('limaE',compact('datanya'));
        // return $nimnya;
        
    }

    public function limaE(Request $request){

        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;

        // return $nimnya;

       lima_e::create([
            'nim' => $nimnya,
            'topik' => $request->judul,
            'judul' => $request->waktu,
            'jenis' => $request->nama_alamat,
            // 'jumlah' => $request->kedudukan,
            // 'lamanya' => $request->kedudukan_penulisan,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        
        // // return $request;
        return redirect('lima/e')->with('message','Data Berhasil Ditambah !!');

    }

    public function limaFshow(){
        // return 0;
        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;
        $datanya = lima_f::where('nim',$nimnya)->get();
        
        $check = tabelsubmitrpl::where('nim',$nimnya)->get()->first();

        // if($check == null){
        //     return view('errors.errorpem'); 
        // }else{
            
        // }
        $submitan = tabelsubmitrpl::where('id',1)->get()->first();
        if($submitan->status == 0){
            return view('errors.errorpem'); 
        }
        if($check->status == 1){
            return view('errors.errorpem'); 
        }
        return view('limaF',compact('datanya'));
        // return $nimnya;
        
    }

    public function limaF(Request $request){

        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;

        // return $nimnya;

       lima_f::create([
            'nim' => $nimnya,
            'judul' => $request->judul,
            'bidang' => $request->waktu,
            'jumlah' => $request->nama_alamat,
            // 'jumlah' => $request->kedudukan,
            // 'lamanya' => $request->kedudukan_penulisan,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        
        // // return $request;
        return redirect('lima/f')->with('message','Data Berhasil Ditambah !!');

    }

    public function enamAshow(){
        // return 0;
        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;
        $datanya = enam_a::where('nim',$nimnya)->get();
        
        $check = tabelsubmitrpl::where('nim',$nimnya)->get()->first();

        // if($check == null){
        //     return view('errors.errorpem'); 
        // }else{
            
        // }
        $submitan = tabelsubmitrpl::where('id',1)->get()->first();
        if($submitan->status == 0){
            return view('errors.errorpem'); 
        }
        if($check->status == 1){
            return view('errors.errorpem'); 
        }
        return view('enamA',compact('datanya'));
        // return $nimnya;
        
    }

    public function enamA(Request $request){

        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;

        // return $nimnya;

       enam_a::create([
            'nim' => $nimnya,
            'jenis_pendidikan' => $request->judul,
            'waktu' => $request->waktu,
            'nama_alamat_telp' => $request->nama_alamat,
            // 'jumlah' => $request->kedudukan,
            // 'lamanya' => $request->kedudukan_penulisan,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        
        // // return $request;
        return redirect('enam/a')->with('message','Data Berhasil Ditambah !!');

    }

    public function enamBshow(){
        // return 0;
        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;
        $datanya = enam_b::where('nim',$nimnya)->get();
        
        $check = tabelsubmitrpl::where('nim',$nimnya)->get()->first();

        // if($check == null){
        //     return view('errors.errorpem'); 
        // }else{
            $submitan = tabelsubmitrpl::where('id',1)->get()->first();
            if($submitan->status == 0){
                return view('errors.errorpem'); 
            } 
            if($check->status == 1){
                return view('errors.errorpem'); 
            }
        // }
        return view('enamB',compact('datanya'));
        // return $nimnya;
        
    }

    public function enamB(Request $request){

        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;

        // return $nimnya;

       enam_b::create([
            'nim' => $nimnya,
            'nama_organisasi' => $request->judul,
            // 'waktu' => $request->waktu,
            'nama_alamat_telp' => $request->nama_alamat,
            'kedudukan' => $request->kedudukan,
            'lamanya' => $request->kedudukan_penulisan,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        
        // // return $request;
        return redirect('enam/b')->with('message','Data Berhasil Ditambah !!');

    }

    public function enamCshow(){
        // return 0;
        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;
        $datanya = enam_c::where('nim',$nimnya)->get();
        
        $check = tabelsubmitrpl::where('nim',$nimnya)->get()->first();

        // if($check == null){
        //     return view('errors.errorpem'); 
        // }else{
            
        // }
        $submitan = tabelsubmitrpl::where('id',1)->get()->first();
        if($submitan->status == 0){
            return view('errors.errorpem'); 
        }
        if($check->status == 1){
            return view('errors.errorpem'); 
        }
        return view('enamC',compact('datanya'));
        // return $nimnya;
        
    }

    public function enamC(Request $request){

        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;

        // return $nimnya;

       enam_c::create([
            'nim' => $nimnya,
            'bentuk' => $request->judul,
            'instansi' => $request->waktu,
            'tahun' => $request->nama_alamat,
            // 'kedudukan' => $request->kedudukan,
            'lamanya' => $request->kedudukan_penulisan,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        
        // // return $request;
        return redirect('enam/c')->with('message','Data Berhasil Ditambah !!');

    }

    public function wordExport(){
        $tp = new TemplateProcessor('kampretos.docx');
        $tp->setValue('id','mangan terusssssss');
        $tp->saveAs('youngjun.docx');
        return response()->download('youngjun.docx')->deleteFileAfterSend(true);
    }

    public function lihat(){
        $users = User::all();
        return view('khususAdmin',compact('users'));
    }

    public function hapus2a($id){

        dua_a::destroy($id);
        return redirect('PresensiSeminarKP')->with('message','Data Berhasil Dihapus !!');

    }

    public function hapus2b($id){
        
        dua_b::destroy($id);
        return redirect('dua/b')->with('message','Data Berhasil Dihapus !!');
    
    }

    public function hapus2c($id){
        
        dua_c::destroy($id);
        return redirect('dua/c')->with('message','Data Berhasil Dihapus !!');
    
    }

    public function hapus3a($id){
        
        tiga_a::destroy($id);
        return redirect('tiga/a')->with('message','Data Berhasil Dihapus !!');
    
    }

    public function hapus3b($id){
        
        tiga_b::destroy($id);
        return redirect('tiga/b')->with('message','Data Berhasil Dihapus !!');
    
    }
    
    public function hapus4a($id){
        
        empat_a::destroy($id);
        return redirect('empat/a')->with('message','Data Berhasil Dihapus !!');
    
    }

    public function hapus4b($id){
        
        empat_b::destroy($id);
        return redirect('empat/b')->with('message','Data Berhasil Dihapus !!');
    
    }

    public function hapus5a($id){
        
        lima_a::destroy($id);
        return redirect('lima/a')->with('message','Data Berhasil Dihapus !!');
    
    }

    public function hapus5b($id){
        
        lima_b::destroy($id);
        return redirect('lima/b')->with('message','Data Berhasil Dihapus !!');
    
    }

    public function hapus5c($id){
        
        lima_c::destroy($id);
        return redirect('lima/c')->with('message','Data Berhasil Dihapus !!');
    
    }

    public function hapus5d($id){
        
        lima_d::destroy($id);
        return redirect('lima/d')->with('message','Data Berhasil Dihapus !!');
    
    }

    public function hapus5e($id){
        
        lima_e::destroy($id);
        return redirect('lima/e')->with('message','Data Berhasil Dihapus !!');
    
    }

    public function hapus5f($id){
        
        lima_f::destroy($id);
        return redirect('lima/f')->with('message','Data Berhasil Dihapus !!');
    
    }

    public function hapus6a($id){
        
        enam_a::destroy($id);
        return redirect('enam/a')->with('message','Data Berhasil Dihapus !!');
    
    }

    public function hapus6b($id){
        
        enam_b::destroy($id);
        return redirect('enam/b')->with('message','Data Berhasil Dihapus !!');
    
    }

    public function hapus6c($id){
        
        enam_c::destroy($id);
        return redirect('enam/c')->with('message','Data Berhasil Dihapus !!');
    
    }

    public function komentar2a(Request $request,$id){
        
        // return $id;
        dua_a::where('id',$id)->update([
            'komentar' => $request->komentar
        ]);
        return back()->with('message','Data Berhasil dikomentari !!');
        // return back()->withInput();
    
    }

    public function komentar2b(Request $request,$id){
        
        // return $id;
        dua_b::where('id',$id)->update([
            'komentar' => $request->komentar
        ]);
        return back()->with('message','Data Berhasil dikomentari !!');
        // return back()->withInput();
    
    }

    public function komentar2c(Request $request,$id){
        
        // return $id;
        dua_c::where('id',$id)->update([
            'komentar' => $request->komentar
        ]);
        return back()->with('message','Data Berhasil dikomentari !!');
        // return back()->withInput();
    
    }

    public function komentar3a(Request $request,$id){
        
        // return $id;
        tiga_a::where('id',$id)->update([
            'komentar' => $request->komentar
        ]);
        return back()->with('message','Data Berhasil dikomentari !!');
        // return back()->withInput();
    
    }

    public function komentar3b(Request $request,$id){
        
        // return $id;
        tiga_b::where('id',$id)->update([
            'komentar' => $request->komentar
        ]);
        return back()->with('message','Data Berhasil dikomentari !!');
        // return back()->withInput();
    
    }

    public function komentar4a(Request $request,$id){
        
        // return $id;
        empat_a::where('id',$id)->update([
            'komentar' => $request->komentar
        ]);
        return back()->with('message','Data Berhasil dikomentari !!');
        // return back()->withInput();
    
    }

    public function komentar4b(Request $request,$id){
        
        // return $id;
        empat_b::where('id',$id)->update([
            'komentar' => $request->komentar
        ]);
        return back()->with('message','Data Berhasil dikomentari !!');
        // return back()->withInput();
    
    }

    public function komentar5a(Request $request,$id){
        
        // return $id;
        lima_a::where('id',$id)->update([
            'komentar' => $request->komentar
        ]);
        return back()->with('message','Data Berhasil dikomentari !!');
        // return back()->withInput();
    
    }

    public function komentar5b(Request $request,$id){
        
        // return $id;
        lima_b::where('id',$id)->update([
            'komentar' => $request->komentar
        ]);
        return back()->with('message','Data Berhasil dikomentari !!');
        // return back()->withInput();
    
    }

    public function komentar5c(Request $request,$id){
        
        // return $id;
        lima_c::where('id',$id)->update([
            'komentar' => $request->komentar
        ]);
        return back()->with('message','Data Berhasil dikomentari !!');
        // return back()->withInput();
    
    }

    public function komentar5d(Request $request,$id){
        
        // return $id;
        lima_d::where('id',$id)->update([
            'komentar' => $request->komentar
        ]);
        return back()->with('message','Data Berhasil dikomentari !!');
        // return back()->withInput();
    
    }

    public function komentar5e(Request $request,$id){
        
        // return $id;
        lima_e::where('id',$id)->update([
            'komentar' => $request->komentar
        ]);
        return back()->with('message','Data Berhasil dikomentari !!');
        // return back()->withInput();
    
    }

    public function komentar5f(Request $request,$id){
        
        // return $id;
        lima_f::where('id',$id)->update([
            'komentar' => $request->komentar
        ]);
        return back()->with('message','Data Berhasil dikomentari !!');
        // return back()->withInput();
    
    }

    public function komentar6a(Request $request,$id){
        
        // return $id;
        enam_a::where('id',$id)->update([
            'komentar' => $request->komentar
        ]);
        return back()->with('message','Data Berhasil dikomentari !!');
        // return back()->withInput();
    
    }

    public function komentar6b(Request $request,$id){
        
        // return $id;
        enam_b::where('id',$id)->update([
            'komentar' => $request->komentar
        ]);
        return back()->with('message','Data Berhasil dikomentari !!');
        // return back()->withInput();
    
    }

    public function komentar6c(Request $request,$id){
        
        // return $id;
        enam_c::where('id',$id)->update([
            'komentar' => $request->komentar
        ]);
        return back()->with('message','Data Berhasil dikomentari !!');
        // return back()->withInput();
    
    }

    
    public function edit2a($id){
        
        $datanya = dua_a::where('id',$id)->get()->first();
        $idnya = $id;
        return view('ngedit2a',compact('datanya','idnya'));
    }
    public function ubah2a(Request $request,$id){
        
        // return $request;
        // // return $id;

        dua_a::where('id',$id)->update([
            // 'nim' => $nimnya,
            'gelar_tahun' => $request->gelar,
            'jurusan' => $request->jurusan,
            'nama_alamat_telp' => $request->nama_univ,
            'sks' => $request->sks,
            'judul_skripsi' => $request->judul,
            'nama_pem' => $request->nama_pem,
            'ijazah' => $request->ijazah,
            'transkrip' => $request->transkrip,
        ]);
        return redirect('PresensiSeminarKP')->with('message','Data Berhasil Diubah !!');
        // return back()->withInput();
    
    }

    public function edit2b($id){
        
        $datanya = dua_b::where('id',$id)->get()->first();
        $idnya = $id;
        return view('ngedit2b',compact('datanya','idnya'));
    }
    public function ubah2b(Request $request,$id){
        
        // return $request;
        // // return $id;

        dua_b::where('id',$id)->update([
            // 'nim' => $nimnya,
            'jenis_pendidikan' => $request->jenis,
            'waktu' => $request->waktu,
            'nama_alamat_telp' => $request->nama_alamat,
            'materi' => $request->materi,
            'lamanya' => $request->lama,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        return redirect('dua/b')->with('message','Data Berhasil Diubah !!');
        // return back()->withInput();
    
    }

    public function edit2c($id){
        
        $datanya = dua_c::where('id',$id)->get()->first();
        $idnya = $id;
        return view('ngedit2c',compact('datanya','idnya'));
    }

    public function ubah2c(Request $request,$id){
        
        // return $request;
        // // return $id;

        dua_c::where('id',$id)->update([
            // 'nim' => $nimnya,
            'jenis_pendidikan' => $request->jenis,
            'waktu' => $request->waktu,
            'nama_alamat_telp' => $request->nama_alamat,
            'materi' => $request->materi,
            'lamanya' => $request->lama,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        return redirect('dua/c')->with('message','Data Berhasil Diubah !!');
        // return back()->withInput();
    
    }

    public function edit3a($id){
        
        $datanya = tiga_a::where('id',$id)->get()->first();
        $idnya = $id;
        return view('ngedit3a',compact('datanya','idnya'));
    }

    public function ubah3a(Request $request,$id){
        
        // return $request;
        // // return $id;

        tiga_a::where('id',$id)->update([
            // 'nim' => $nimnya,
            'bentuk_kegiatan' => $request->jenis,
            'waktu' => $request->waktu,
            'nama_alamat_telp' => $request->nama_alamat,
            'judul' => $request->materi,
            'daftar' => $request->lama,
            // 'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        return redirect('tiga/a')->with('message','Data Berhasil Diubah !!');
        // return back()->withInput();
    
    }

    public function edit3b($id){
        
        $datanya = tiga_b::where('id',$id)->get()->first();
        $idnya = $id;
        return view('ngedit3b',compact('datanya','idnya'));
    }

    public function ubah3b(Request $request,$id){
        
        // return $request;
        // // return $id;

        tiga_b::where('id',$id)->update([
            // 'nim' => $nimnya,
            'bentuk_kegiatan' => $request->jenis,
            'waktu' => $request->waktu,
            'nama_alamat_telp' => $request->nama_alamat,
            'judul' => $request->materi,
            'daftar' => $request->lama,
            // 'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        return redirect('tiga/b')->with('message','Data Berhasil Diubah !!');
        // return back()->withInput();
    
    }

    public function edit4a($id){
        
        $datanya = empat_a::where('id',$id)->get()->first();
        $idnya = $id;
        return view('ngedit4a',compact('datanya','idnya'));
    }

    public function ubah4a(Request $request,$id){
        
        // return $request;
        // // return $id;

        empat_a::where('id',$id)->update([
            // 'nim' => $nimnya,
            'jenis_bidang' => $request->jenis,
            'waktu' => $request->waktu,
            'nama_alamat_telp' => $request->nama_alamat,
            'jabatan' => $request->materi,
            'uraian' => $request->lama,
            // 'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        return redirect('empat/a')->with('message','Data Berhasil Diubah !!');
        // return back()->withInput();
    
    }

    public function edit4b($id){
        
        $datanya = empat_b::where('id',$id)->get()->first();
        $idnya = $id;
        return view('ngedit4b',compact('datanya','idnya'));
    }

    public function ubah4b(Request $request,$id){
        
        // return $request;
        // // return $id;

        empat_b::where('id',$id)->update([
            // 'nim' => $nimnya,
            'jenis_bidang' => $request->jenis,
            'waktu' => $request->waktu,
            'nama_alamat_telp' => $request->nama_alamat,
            'jabatan' => $request->materi,
            'uraian' => $request->lama,
            // 'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        return redirect('empat/b')->with('message','Data Berhasil Diubah !!');
        // return back()->withInput();
    
    }

    public function edit5a($id){
        
        $datanya = lima_a::where('id',$id)->get()->first();
        $idnya = $id;
        return view('ngedit5a',compact('datanya','idnya'));
    }

    public function ubah5a(Request $request,$id){
        
        // return $request;
        // // return $id;

        lima_a::where('id',$id)->update([
            // 'nim' => $nimnya,
            'topik' => $request->topik,
            'judul' => $request->judul,
            'nama_lembaga' => $request->nama_alamat,
            'kedudukan' => $request->kedudukan,
            'kedudukan_penulisan' => $request->kedudukan_penulisan,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        return redirect('lima/a')->with('message','Data Berhasil Diubah !!');
        // return back()->withInput();
    
    }

    public function edit5b($id){
        
        $datanya = lima_b::where('id',$id)->get()->first();
        $idnya = $id;
        return view('ngedit5b',compact('datanya','idnya'));
    }

    public function ubah5b(Request $request,$id){
        
        // return $request;
        // // return $id;

        lima_b::where('id',$id)->update([
            // 'nim' => $nimnya,
            'topik' => $request->topik,
            'judul' => $request->judul,
            'nama_lembaga' => $request->nama_alamat,
            'kedudukan' => $request->kedudukan,
            // 'kedudukan_penulisan' => $request->kedudukan_penulisan,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        return redirect('lima/b')->with('message','Data Berhasil Diubah !!');
        // return back()->withInput();
    
    }

    public function edit5c($id){
        
        $datanya = lima_c::where('id',$id)->get()->first();
        $idnya = $id;
        return view('ngedit5c',compact('datanya','idnya'));
    }

    public function ubah5c(Request $request,$id){
        
        // return $request;
        // // return $id;

        lima_c::where('id',$id)->update([
            // 'nim' => $nimnya,
            'judul' => $request->judul,
            'waktu' => $request->waktu,
            'nama_lembaga' => $request->nama_alamat,
            'jumlah' => $request->kedudukan,
            'jenis' => $request->kedudukan_penulisan,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        return redirect('lima/c')->with('message','Data Berhasil Diubah !!');
        // return back()->withInput();
    
    }

    public function edit5d($id){
        
        $datanya = lima_d::where('id',$id)->get()->first();
        $idnya = $id;
        return view('ngedit5d',compact('datanya','idnya'));
    }

    public function ubah5d(Request $request,$id){
        
        // return $request;
        // // return $id;

        lima_d::where('id',$id)->update([
            // 'nim' => $nimnya,
            'jenis' => $request->judul,
            'waktu' => $request->waktu,
            'nama_lembaga' => $request->nama_alamat,
            'jumlah' => $request->kedudukan,
            'lamanya' => $request->kedudukan_penulisan,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        return redirect('lima/d')->with('message','Data Berhasil Diubah !!');
        // return back()->withInput();
    
    }

    public function edit5e($id){
        
        $datanya = lima_e::where('id',$id)->get()->first();
        $idnya = $id;
        return view('ngedit5e',compact('datanya','idnya'));
    }

    public function ubah5e(Request $request,$id){
        
        // return $request;
        // // return $id;

        lima_e::where('id',$id)->update([
            // 'nim' => $nimnya,
            'topik' => $request->judul,
            'judul' => $request->waktu,
            'jenis' => $request->nama_alamat,
            // 'jumlah' => $request->kedudukan,
            // 'lamanya' => $request->kedudukan_penulisan,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        return redirect('lima/e')->with('message','Data Berhasil Diubah !!');
        // return back()->withInput();
    
    }

    public function edit5f($id){
        
        $datanya = lima_f::where('id',$id)->get()->first();
        $idnya = $id;
        return view('ngedit5f',compact('datanya','idnya'));
    }

    public function ubah5f(Request $request,$id){
        
        // return $request;
        // // return $id;

        lima_f::where('id',$id)->update([
            // 'nim' => $nimnya,
            'judul' => $request->judul,
            'bidang' => $request->waktu,
            'jumlah' => $request->nama_alamat,
            // 'jumlah' => $request->kedudukan,
            // 'lamanya' => $request->kedudukan_penulisan,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        return redirect('lima/f')->with('message','Data Berhasil Diubah !!');
        // return back()->withInput();
    
    }

    public function edit6a($id){
        
        $datanya = enam_a::where('id',$id)->get()->first();
        $idnya = $id;
        return view('ngedit6a',compact('datanya','idnya'));
    }

    public function ubah6a(Request $request,$id){
        
        // return $request;
        // // return $id;

        enam_a::where('id',$id)->update([
            // 'nim' => $nimnya,
            'jenis_pendidikan' => $request->judul,
            'waktu' => $request->waktu,
            'nama_alamat_telp' => $request->nama_alamat,
            // 'jumlah' => $request->kedudukan,
            // 'lamanya' => $request->kedudukan_penulisan,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        return redirect('enam/a')->with('message','Data Berhasil Diubah !!');
        // return back()->withInput();
    
    }

    public function edit6b($id){
        
        $datanya = enam_b::where('id',$id)->get()->first();
        $idnya = $id;
        return view('ngedit6b',compact('datanya','idnya'));
    }

    public function ubah6b(Request $request,$id){
        
        // return $request;
        // // return $id;

        enam_b::where('id',$id)->update([
            // 'nim' => $nimnya,
            'nama_organisasi' => $request->judul,
            // 'waktu' => $request->waktu,
            'nama_alamat_telp' => $request->nama_alamat,
            'kedudukan' => $request->kedudukan,
            'lamanya' => $request->kedudukan_penulisan,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        return redirect('enam/b')->with('message','Data Berhasil Diubah !!');
        // return back()->withInput();
    
    }

    public function edit6c($id){
        
        $datanya = enam_c::where('id',$id)->get()->first();
        $idnya = $id;
        return view('ngedit6c',compact('datanya','idnya'));
    }

    public function ubah6c(Request $request,$id){
        
        // return $request;
        // // return $id;

        enam_c::where('id',$id)->update([
            // 'nim' => $nimnya,
            'bentuk' => $request->judul,
            'instansi' => $request->waktu,
            'tahun' => $request->nama_alamat,
            // 'kedudukan' => $request->kedudukan,
            'lamanya' => $request->kedudukan_penulisan,
            'jadwal' => $request->jadwal,
            'sertifikat' => $request->sertifikat,
        ]);
        return redirect('enam/c')->with('message','Data Berhasil Diubah !!');
        // return back()->withInput();
    
    }

    public function showNilaiPenguji($id)
    {
        // return $id;
        // $data = Dosen::where(Auth::user()->nim)->get();
        $yongjun = Dosen::pengujisemhas(Auth::user()->nim);
        // $data = Auth::user()->nim;
        $data2 = $yongjun->where('ta_id',$id)->first();
        // return $data2;
        $murid = Ta::where('id',$id)->get()->first();
        // return $murid;   
        $nimnya = $murid->mahasiswa_id;
        // return $yongjun;    

        $data = Mahasiswa::where('id',$nimnya)->get()->first();
        $nimbro = $data->nim;
        // return $nimbro;

        $cpem = $data2->penguji_ke;
        $cpembimbing = $data2->penguji_semhas;

        $lin = Ta::where('mahasiswa_id',$data->id)->get()->first();

        $pengecheck = tabelnilai::where('nimnya',$nimbro)->where('jabatan','penguji')
        ->where('no_pem',$cpem)->where('dosennya',$cpembimbing)->get()->first();
        
        if($pengecheck->status == 1){
            // return 'sudah isi dude';
            $pengecheck2 = Pembimbing::where('ta_id',$lin->id)->get()->count();
            
            if($pengecheck2 == 1){
                // return 'isi 1';
                $yangke1 = tabelnilai::where('nimnya',$nimbro)->where('jabatan','pembimbing')
                ->where('no_pem',1)->get()->first();
                return view('lembarNilaiPengujiAfter',compact('pengecheck','data','yangke1'));
            }
            if($pengecheck2 == 2){
                // return 'isi 2';
                $yangke1 = tabelnilai::where('nimnya',$nimbro)->where('jabatan','pembimbing')
                ->where('no_pem',1)->get()->first();
                $yangke2 = tabelnilai::where('nimnya',$nimbro)->where('jabatan','pembimbing')
                ->where('no_pem',2)->get()->first();
                return view('lembarNilaiPenguji2After',compact('pengecheck','data','yangke1','yangke2'));
            }
            if($pengecheck2 == 3){
                // return 'isi 3';
                $yangke1 = tabelnilai::where('nimnya',$nimbro)->where('jabatan','pembimbing')
                ->where('no_pem',1)->get()->first();
                $yangke2 = tabelnilai::where('nimnya',$nimbro)->where('jabatan','pembimbing')
                ->where('no_pem',2)->get()->first();
                $yangke3 = tabelnilai::where('nimnya',$nimbro)->where('jabatan','pembimbing')
                ->where('no_pem',3)->get()->first();
                return view('lembarNilaiPenguji3After',compact('pengecheck','data','yangke1','yangke2','yangke3'));
            }
        }
        // return $pengecheck;

        $pengecheck2 = Pembimbing::where('ta_id',$lin->id)->get()->count();
        // $kentang = Pembimbing::where('ta_id',709)->get()->first();
        // return 'nilai sudah di input';
        if($pengecheck2 == 1){
            $yangke1 = tabelnilai::where('nimnya',$nimbro)->where('jabatan','pembimbing')
            ->where('no_pem',1)->get()->first();
            return view('lembarNilaiPenguji',compact('data2','data','yangke1'));
        }
        if($pengecheck2 == 2){
            $yangke1 = tabelnilai::where('nimnya',$nimbro)->where('jabatan','pembimbing')
            ->where('no_pem',1)->get()->first();
            $yangke2 = tabelnilai::where('nimnya',$nimbro)->where('jabatan','pembimbing')
            ->where('no_pem',2)->get()->first();
            return view('lembarNilaiPenguji2',compact('data2','data','yangke1','yangke2'));
        }
        if($pengecheck2 == 3){
            $yangke1 = tabelnilai::where('nimnya',$nimbro)->where('jabatan','pembimbing')
            ->where('no_pem',1)->get()->first();
            $yangke2 = tabelnilai::where('nimnya',$nimbro)->where('jabatan','pembimbing')
            ->where('no_pem',2)->get()->first();
            $yangke3 = tabelnilai::where('nimnya',$nimbro)->where('jabatan','pembimbing')
            ->where('no_pem',3)->get()->first();
            return view('lembarNilaiPenguji3',compact('data2','data','yangke1','yangke2','yangke3'));
        }
       
    }

}
