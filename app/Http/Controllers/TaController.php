<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\batasan_bimbingan;
use App\Models\Matakuliah;
use App\Models\Peminatan;
use App\Models\Ta;
use App\Models\Jabatan;
use App\Models\Koordinatorkbk;
use App\Models\Pembimbing;
use App\Models\Penguji;
use App\Models\Seminarta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

use App\Models\tabelsubmitrpl;
use App\Models\tabelnilai;

class TaController extends Controller
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
    public function index($nim)
    {
        $datanya = Mahasiswa::mhs($nim)->first()->id;
        // return $datanya;
        // $nim = Auth::user()->nim;
        $data = Mahasiswa::mhs($nim)->first();
        $dosen = Dosen::all();
        $matakuliah = Matakuliah::all();
        $peminatan = Peminatan::all();
        $pending = Ta::pending($nim)->first();
        // $setuju = Ta::setuju($nim)->first();
        $setuju = Ta::where('mahasiswa_id',$datanya)->first();
        $tolak = Ta::tolak($nim)->first();
        // dd($data);
        if($setuju != null){
            $matkul = Ta::matkul($setuju->id);
            $pembimbing = Pembimbing::pembimbing($setuju->id);
            $penguji = Penguji::Pengujisemhas($setuju->id);
            // return $penguji;
            return view('ta.ta_setuju',compact('setuju','matkul','pembimbing','penguji','data')); //TA disetujui
        }elseif($pending != null){
            $matkul = Ta::matkul($pending->id);
            $pembimbing = Pembimbing::pembimbing($pending->id);

            return view('ta.ta_pending',compact('pending','matkul','pembimbing')); //Ta menunggu repon koor ta
        }elseif($tolak != null){
            $matkul = Ta::matkul($tolak->id);
            $pembimbing = Pembimbing::pembimbing($tolak->id);
            // dd($matkul);
            return view('ta.ta_tolak',compact('tolak','matkul','matakuliah','pembimbing','dosen','peminatan')); //Ta ditolak
        }elseif ($data != null){
            // return view('ta.ta_pendaftaran',compact('data','dosen','matakuliah','peminatan')); //Belum daftar ta
            return view('dosenpenguji',compact('data','dosen','matakuliah','peminatan')); //Belum daftar ta
        }else{
            return view('ta.error.pembimbing'); //sementara
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validatedTa = $request->validate([
            'mahasiswa_id' => 'required|unique:ta',
            'sks' => 'required',
            'ipk' => 'required',
            // 'judul' => 'required',
            // 'abstrak' => 'required',
            'status_ta' => 'required',
            // 'peminatan_id' => 'required',
            'cetak_ta' => 'required',
        ]);

        $validatedData = $request->validate([
            // 'kode_mk1' => 'required',
            // 'mk1' => 'required|different:mk3',
            // 'nilai_mk1' => 'required|numeric',
            // 'huruf_mk1' => 'required',
            // // 'kode_mk2' => 'required',
            // 'mk2' => 'required|different:mk1',
            // 'nilai_mk2' => 'required|numeric',
            // 'huruf_mk2' => 'required',
            // // 'kode_mk3' => 'required',
            // 'mk3' => 'required|different:mk2',
            // 'nilai_mk3' => 'required|numeric',
            // 'huruf_mk3' => 'required',
            'pembimbing1' => 'required',
            'pembimbing2' => 'required|different:pembimbing1',
        ]);
        // dd($validatedData);

        $batas = batasan_bimbingan::where('id',1)->first();
        $barier = $batas->jumlah_bimbingan;

        $pembimbing = 'pembimbing1';
        $pembimbing2 = 'pembimbing2';

        $check = Pembimbing::join('ref_dosen','ref_dosen.id','=','ta_pembimbing.pembimbing')
        ->where('ref_dosen.id',$request->$pembimbing)->where('pem',1)->where('status_pendadaranpem',null)->count();

        $check2 = Pembimbing::join('ref_dosen','ref_dosen.id','=','ta_pembimbing.pembimbing')
        ->where('ref_dosen.id',$request->$pembimbing2)->where('pem',2)->where('status_pendadaranpem',null)->count();

        if($check >= $barier){
            return redirect('ta/pengajuan/pendaftaran')->with('message','Pembimbing 1 sudah penuh !');
        }else if($check2 >= $barier){
            return redirect('ta/pengajuan/pendaftaran')->with('message','Pembimbing 2 sudah penuh !');
        }else{
            $ta = Ta::create($validatedTa); 
        }
        
        $ta_id = $ta->id;
        if($ta){

            for ($i = 1; $i <= 3; $i++) {
                $mk = 'mk' . $i;
                $datamk = Matakuliah::where('nama',$request->$mk)->first();
                // $kode_mk = $datamk->kode;
                $nilai_mk = 'nilai_mk' . $i;
                $huruf_mk = 'huruf_mk' . $i;
                DB::table('ta_matkul')->insert([
                    'ta_id' => $ta_id,
                    'nama_matkul' => $request->$mk,
                    'kode_matkul' => $datamk->kode,
                    'ip' => $request->$nilai_mk,
                    'huruf' => $request->$huruf_mk,
                ]);
            }

            for ($i = 1; $i <= 2; $i++) {
                $pembimbing = 'pembimbing' . $i;
                Pembimbing::create([
                    'ta_id' => $ta_id,
                    'pembimbing' => $request->$pembimbing,
                    'pem' => $i,
                    'status_pem' => 'PENDING',
                ]);
            }

            Koordinatorkbk::create([
                'ta_id' => $ta_id,
                'status_kbk' => 'PENDING',
            ]);

            $koorta = Jabatan::Ta();
            $es = $koorta->no_telp;

            // return redirect()->away('https://localhost:8000/notifKoorTA-pendaftaran/'.$es);
            return redirect(route('ta.pendaftaran.index'))->with('message','Terimakasih telah mendaftar Tugas Akhir!');
        }
       //return redirect('ta/pendaftaran')->with('message','Terimakasih telah mengajukan Kerja Praktek!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tolak = Ta::find($id)
                ->select('*','ta.id')
                ->join('ref_mahasiswa','ref_mahasiswa.id','=','ta.mahasiswa_id')
                ->join('ref_peminatan','ref_peminatan.id','=','ta.peminatan_id')
                ->where('nim', Auth::user()->nim)
                ->where('status_ta','PENDING')
                ->firstOrFail();
        $matkul = Ta::matkul($id);
        $pembimbing = Pembimbing::pembimbing($id);
        $dosen = Dosen::all();
        $matkul1 = Matakuliah::all();
        $peminatan = Peminatan::all();
        // dd($pembimbing);
        return view('ta.ta_edit', compact('tolak','matkul','pembimbing','dosen','matkul1','peminatan'));
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
        $validatedTa = $request->validate([
            'sks' => 'required',
            'ipk' => 'required',
            'judul' => 'required',
            'abstrak' => 'required',
            'status_ta' => 'required',
            'peminatan_id' => 'required',
        ]);

        $validatedData = $request->validate([
            // 'kode_mk1' => 'required',
            'mk1' => 'required|different:mk3',
            'nilai_mk1' => 'required|numeric',
            'huruf_mk1' => 'required',
            // 'kode_mk2' => 'required',
            'mk2' => 'required|different:mk1',
            'nilai_mk2' => 'required|numeric',
            'huruf_mk2' => 'required',
            // 'kode_mk3' => 'required',
            'mk3' => 'required|different:mk2',
            'nilai_mk3' => 'required|numeric',
            'huruf_mk3' => 'required',
            'pembimbing1' => 'required',
            'pembimbing2' => 'required|different:pembimbing1',
        ]);
        $ta = Ta::where('id',$id)->update([
            'sks' => $request->sks,
            'ipk' => $request->ipk,
            'judul' => $request->judul,
            'abstrak' => $request->abstrak,
            'status_ta' => $request->status_ta,
            'peminatan_id' => $request->peminatan_id,
            'proses_ta' => 1,
        ]);
        if($ta){

            for ($i = 1; $i <= 3; $i++) {
                $idta = 'idta'.$i;
                $mk = 'mk' . $i;
                $datamk = Matakuliah::where('nama',$request->$mk)->first();
                // $kode_mk = 'kode_mk' . $i;
                $nilai_mk = 'nilai_mk' . $i;
                $huruf_mk = 'huruf_mk' . $i;
                DB::table('ta_matkul')->where('id',$request->$idta)->update([
                    'nama_matkul' => $request->$mk,
                    'kode_matkul' => $datamk->kode,
                    'ip' => $request->$nilai_mk,
                    'huruf' => $request->$huruf_mk,
                ]);
            }

            for ($i = 1; $i <= 2; $i++) {
                $idpem = 'idpem'.$i;
                $pembimbing = 'pembimbing' . $i;
                Pembimbing::where('id',$request->$idpem)->update([
                    'pembimbing' => $request->$pembimbing,
                    'pem' => $i,
                    'status_pem' => 'PENDING',
                ]);
            }
            // alihkan halaman ke halaman index
            return redirect(route('ta.pendaftaran.index'))->with('message','Data Tugas Akhir Berhasil di Update!');
        }
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

    //Cetak Lembar Pendaftaran TA
    public function cetak_pendaftaran(){
        $nim = Auth::user()->nim;
        $data = Ta::pending($nim)->first();
        $matkul = Ta::matkul($data->id);
        $pembimbing = Pembimbing::pembimbing($data->id);
        $ta = Jabatan::ta();
        if($data->peminatan_id == 1){
            $kbk = Jabatan::sel();
        }elseif($data->peminatan_id == 2){
            $kbk = Jabatan::meka();
        }else{
            $kbk = Jabatan::ict();
        }
        $config = [
            'format' => 'A4-P', // Portrait
             'margin_left'          => 30,
             'margin_right'         => 25,
             'margin_top'           => 30,
             'margin_footer'         => 5,
            // 'margin_bottom'        => 25,
          ];
        $pdf = PDF::loadview('ta.cetak_pendaftaran',compact('data','matkul','pembimbing','kbk','ta'),[],$config);
        return $pdf->stream();
    }

    public function batasan_bimbingan(){
        return view('yuyun');
    }

    public function paraPenguji(Request $request){
        // Penguji::create([
        //     'ta_id' => 203,'penguji_ke' => 1,'penguji_semhas' => $request->pengujiwawancara1,
        // ]);

        // return 0;
        

        $checking = Mahasiswa::where('id',$request->mahasiswa_id)->get()->first();
        
        // LIMITER
        if($request->pembimbing1 == null){
            return back()->with('alert','Maaf, isi Minimal 1 Penguji !!');
        }else if($request->pembimbing2 == null && $request->pembimbing3 != null){
            return back()->with('alert','Maaf, isi Penguji dengan urutan yang benar !');
        }else if($request->pembimbing2 != null && $request->pembimbing3 == null){
            if($request->pembimbing2 == $request->pembimbing1){
                return back()->with('alert','Maaf, Penguji Tidak Boleh Sama !!');
            }
        }else if($request->pembimbing2 != null && $request->pembimbing3 != null){
            if($request->pembimbing1 == $request->pembimbing2){
                return back()->with('alert','Maaf, Penguji Tidak Boleh Sama !!');
            }else if($request->pembimbing2 == $request->pembimbing3){
                return back()->with('alert','Maaf, Penguji Tidak Boleh Sama !!');
            }
            else if($request->pembimbing1 == $request->pembimbing3){
                return back()->with('alert','Maaf, Penguji Tidak Boleh Sama !!');
            }
        }

        if($request->pengujiwawancara1 == null){
            return back()->with('alert','Maaf, isi Minimal 1 Penguji !!');
        }else if($request->pengujiwawancara2 == null && $request->pengujiwawancara3 != null){
            return back()->with('alert','Maaf, isi Penguji dengan urutan yang benar !');
        }else if($request->pengujiwawancara2 != null && $request->pengujiwawancara3 == null){
            if($request->pengujiwawancara2 == $request->pengujiwawancara1){
                return back()->with('alert','Maaf, Penguji Tidak Boleh Sama !!');
            }
        }else if($request->pengujiwawancara2 != null && $request->pengujiwawancara3 != null){
            if($request->pengujiwawancara1 == $request->pengujiwawancara2){
                return back()->with('alert','Maaf, Penguji Tidak Boleh Sama !!');
            }else if($request->pengujiwawancara2 == $request->pengujiwawancara3){
                return back()->with('alert','Maaf, Penguji Tidak Boleh Sama !!');
            }
            else if($request->pengujiwawancara1 == $request->pengujiwawancara3){
                return back()->with('alert','Maaf, Penguji Tidak Boleh Sama !!');
            }
        }

        $validatedTa = $request->validate([
            'mahasiswa_id' => 'required|unique:ta',
            'sks' => 'required',
            'ipk' => 'required',
            // 'judul' => 'required',
            // 'abstrak' => 'required',
            'status_ta' => 'required',
            // 'peminatan_id' => 'required',
            'cetak_ta' => 'required',
            'rpl' => 'required',
        ]);

        // $batas = batasan_bimbingan::where('id',1)->first();
        // $barier = $batas->jumlah_bimbingan;

        $pembimbing = 'pembimbing1';
        $pembimbing2 = 'pembimbing2';

        $penguji1 = 'pengujiwawancara1';
        $penguji2 = 'pengujiwawancara2';

        $ta = Ta::create($validatedTa); 
        
        $ta_id = $ta->id;
        if($ta){ 

            if($request->pembimbing3 != null){
                for ($i = 1; $i <= 3; $i++) {
                    $pembimbing = 'pembimbing' . $i;
                    Pembimbing::create([
                        'ta_id' => $ta_id,
                        'pembimbing' => $request->$pembimbing,
                        'pem' => $i,
                        'status_pem' => 'SETUJU',
                        'status_semhas' => 'SETUJU',
                    ]);
                }
                
                tabelnilai::create(['nimnya' => $request->nim,'jabatan' => 'pembimbing','no_pem' => 1,
                    'status' => 0,'dosennya' => $request->pembimbing1,]);
                tabelnilai::create(['nimnya' => $request->nim,'jabatan' => 'pembimbing','no_pem' => 2,
                    'status' => 0,'dosennya' => $request->pembimbing2,]);
                tabelnilai::create(['nimnya' => $request->nim,'jabatan' => 'pembimbing','no_pem' => 3,
                    'status' => 0,'dosennya' => $request->pembimbing3,]);
               
            }

            else if($request->pembimbing3 == null && $request->pembimbing2 != null){
                for ($i = 1; $i <= 2; $i++) {
                    $pembimbing = 'pembimbing' . $i;
                    Pembimbing::create([
                        'ta_id' => $ta_id,
                        'pembimbing' => $request->$pembimbing,
                        'pem' => $i,
                        'status_pem' => 'SETUJU',
                        'status_semhas' => 'SETUJU',
                    ]);
                }
                tabelnilai::create(['nimnya' => $request->nim,'jabatan' => 'pembimbing','no_pem' => 1,
                    'status' => 0,'dosennya' => $request->pembimbing1,]);
                tabelnilai::create(['nimnya' => $request->nim,'jabatan' => 'pembimbing','no_pem' => 2,
                    'status' => 0,'dosennya' => $request->pembimbing2,]);
                
            }

            else if($request->pembimbing2 == null){
                for ($i = 1; $i <= 1; $i++) {
                    $pembimbing = 'pembimbing' . $i;
                    Pembimbing::create([
                        'ta_id' => $ta_id,
                        'pembimbing' => $request->$pembimbing,
                        'pem' => $i,
                        'status_pem' => 'SETUJU',
                        'status_semhas' => 'SETUJU',
                    ]);
                }
                tabelnilai::create(['nimnya' => $request->nim,'jabatan' => 'pembimbing','no_pem' => 1,
                    'status' => 0,'dosennya' => $request->pembimbing1,]);
            }

            if($request->pengujiwawancara3 != null){
                // return 'step 1';
                Penguji::create(['ta_id' => $ta_id,'penguji_ke' => 1,'penguji_semhas' => $request->pengujiwawancara1,
                ]);
                Penguji::create(['ta_id' => $ta_id,'penguji_ke' => 2,'penguji_semhas' => $request->pengujiwawancara2,
                ]);
                Penguji::create(['ta_id' => $ta_id,'penguji_ke' => 3,'penguji_semhas' => $request->pengujiwawancara3,
                ]);
                tabelnilai::create(['nimnya' => $request->nim,'jabatan' => 'penguji','no_pem' => 1,
                    'status' => 0,'dosennya' => $request->pengujiwawancara1,]);
                tabelnilai::create(['nimnya' => $request->nim,'jabatan' => 'penguji','no_pem' => 2,
                    'status' => 0,'dosennya' => $request->pengujiwawancara2,]);
                tabelnilai::create(['nimnya' => $request->nim,'jabatan' => 'penguji','no_pem' => 3,
                    'status' => 0,'dosennya' => $request->pengujiwawancara3,]);
            }
    
            else if($request->pengujiwawancara3 == null && $request->pengujiwawancara2 != null){
                // return 'step 2';
                Penguji::create(['ta_id' => $ta_id,'penguji_ke' => 1,'penguji_semhas' => $request->pengujiwawancara1,
                ]);
                Penguji::create(['ta_id' => $ta_id,'penguji_ke' => 2,'penguji_semhas' => $request->pengujiwawancara2,
                ]);
                tabelnilai::create(['nimnya' => $request->nim,'jabatan' => 'penguji','no_pem' => 1,
                    'status' => 0,'dosennya' => $request->pengujiwawancara1,]);
                tabelnilai::create(['nimnya' => $request->nim,'jabatan' => 'penguji','no_pem' => 2,
                    'status' => 0,'dosennya' => $request->pengujiwawancara2,]);
            }
    
            else if($request->pengujiwawancara2 == null){
                Penguji::create(['ta_id' => $ta_id,'penguji_ke' => 1,'penguji_semhas' => $request->pengujiwawancara1,
                ]);
                tabelnilai::create(['nimnya' => $request->nim,'jabatan' => 'penguji','no_pem' => 1,
                    'status' => 0,'dosennya' => $request->pengujiwawancara1,]);
                // return 'step 3';
            }

            $semta = Seminarta::create([
                'ta_id' => $ta_id,
                'status_seminar' => 'SETUJU',
                'cetak_semhas' => null,
                'draft_semhas' => null,
            ]);

            // $checking = Mahasiswa::where('id',)
            //$yong = tabelsubmitrpl::create([
               // 'nim' => $checking->nim,
               // 'status' => 0
           // ]);

            // return redirect()->away('https://localhost:8000/notifKoorTA-pendaftaran/'.$es);
            return back()->with('message','Data Berhasil diupdate !!');
        }
    }

    public function nilaiPembimbing(Request $request,$id){
        // return $request;
        $data2 = Dosen::bimbinganta(Auth::user()->nim)->first();
        $cpembimbing = $data2->pembimbing;
        $cpem = $data2->pem;
        // return $data2;
        $pengecheck = tabelnilai::where('nimnya',$id)->where('jabatan','pembimbing')
        ->where('no_pem',$cpem)->where('dosennya',$cpembimbing)->get()->first();

        // return $pengecheck;
        // return $request;

         // BAB A
         $jumlah_step_1 = (($request->no1 * 20 * 1) + ($request->no2 * 20 * (1/2)) + ($request->no3 * 20 * (3/10)) + ($request->no4 * 20 * (1/5)))/2;
         $jumlah_step_2 = (($request->no5 * 30 * 1) + ($request->no6 * 30 * (6/10)) + ($request->no7 * 30 * (2/5)))/2;   
         $jumlah_step_3 = (($request->no8 * 40 * (1/2)) + ($request->no9 * 40 * (1/2)) + ($request->no10 * 40 * 1))/2;       
         $jumlah_step_4 = ($request->no11 * 10 * (1/4)) ;
         $jumlah_step_A = $jumlah_step_1 + $jumlah_step_2 + $jumlah_step_3 + $jumlah_step_4;
         
         // BAB B
         $jumlah_step_5 = ($request->no12 * 40 * (3/4)) + ($request->no13 * 40 * (1/4)) ;
         $jumlah_step_6 = ($request->no14 * 20 * (3/4)) + ($request->no15 * 20 * (1/4)) ;
         $jumlah_step_7 = ($request->no16 * 10 * (2/4)) + ($request->no17 * 10 * (2/4)) ;
         $jumlah_step_8 = ($request->no18 * 30 * (2/4)) + ($request->no19 * 30 * (35/100)) + ($request->no20 * 30 * (15/100)) ;
         $jumlah_step_B = $jumlah_step_5 + $jumlah_step_6 + $jumlah_step_7 + $jumlah_step_8;
         
         // BAB C
         $jumlah_step_9 = (($request->no21 * 60 * 1) + ($request->no22 * 60 * (3/5)) + ($request->no23 * 60 * (2/5)) + ($request->no24 * 60 * (1/5)))/2;
         $jumlah_step_10 = (($request->no25 * 40 * 1) + ($request->no26 * 40 * (35/100)) + ($request->no27 * 40 * (65/100)))/2;
         $jumlah_step_C = $jumlah_step_9 + $jumlah_step_10;
         
         // BAB D
         $jumlah_step_11 = ($request->no28 * 30 * (2/4)) + ($request->no29 * 30 * (2/4));
         $jumlah_step_12 = ($request->no30 * 50 * 1)/2 + ($request->no31 * 50 * (2/4))/2 + ($request->no32 * 50 * (3/10))/2 + ($request->no33 * 50 * (1/5))/2;
         $jumlah_step_13 = ($request->no34 * 10 * 1);
         $jumlah_step_14 = ($request->no35 * 10 * 1);
         $jumlah_step_D = $jumlah_step_11 + $jumlah_step_12 + $jumlah_step_13 + $jumlah_step_14;
         
         // BAB E
         $jumlah_step_15 = ($request->no36 * 25 * 1) + ($request->no37 * 25 * 1) + ($request->no38 * 25 * 1) + ($request->no39 * 25 * 1);
         $jumlah_step_E = $jumlah_step_15;
         //  BAB F
         $jumlah_step_16 = ($request->no40 * 10 * 1) + ($request->no41 * 10 * 1) + ($request->no42 * 10 * 1) + ($request->no43 * 10 * 1)
         + ($request->no44 * 10 * 1) + ($request->no45 * 10 * 1) + ($request->no46 * 10 * 1) + ($request->no47 * 10 * 1)
         + ($request->no48 * 20 * 1);
         $jumlah_step_F = $jumlah_step_16;
         
         // TOTAL ALL
         $jumlah_step_total = ($jumlah_step_A + $jumlah_step_B + $jumlah_step_C + $jumlah_step_D + $jumlah_step_15 + $jumlah_step_16)/6;
 
        // return $jumlah_step_total;
        $yakin = tabelnilai::where('nimnya',$id)->where('jabatan','pembimbing')
        ->where('no_pem',$cpem)->where('dosennya',$cpembimbing)->update([
            // 'nimnya' => $id,
            // 'jabatan' => 'pembimbing',
            // 'no_pem' => $data2->pem,
            'status' => 1,
            'dosennya' => $data2->pembimbing,
            'no_1' => $request->no1,
            'no_2' => $request->no2,
            'no_3' => $request->no3,
            'no_4' => $request->no4,
            'no_5' => $request->no5,
            'no_6' => $request->no6,
            'no_7' => $request->no7,
            'no_8' => $request->no8,
            'no_9' => $request->no9,
            'no_10' => $request->no10,
            'no_11' => $request->no11,
            'no_12' => $request->no12,
            'no_13' => $request->no13,
            'no_14' => $request->no14,
            'no_15' => $request->no15,
            'no_16' => $request->no16,
            'no_17' => $request->no17,
            'no_18' => $request->no18,
            'no_19' => $request->no19,
            'no_20' => $request->no20,
            'no_21' => $request->no21,
            'no_22' => $request->no22,
            'no_23' => $request->no23,
            'no_24' => $request->no24,
            'no_25' => $request->no25,
            'no_26' => $request->no26,
            'no_27' => $request->no27,
            'no_28' => $request->no28,
            'no_29' => $request->no29,
            'no_30' => $request->no30,
            'no_31' => $request->no31,
            'no_32' => $request->no32,
            'no_33' => $request->no33,
            'no_34' => $request->no34,
            'no_35' => $request->no35,
            'no_36' => $request->no36,
            'no_37' => $request->no37,
            'no_38' => $request->no38,
            'no_39' => $request->no39,
            'no_40' => $request->no40,
            'no_41' => $request->no41,
            'no_42' => $request->no42,
            'no_43' => $request->no43,
            'no_44' => $request->no44,
            'no_45' => $request->no45,
            'no_46' => $request->no46,
            'no_47' => $request->no47,
            'no_48' => $request->no48,
            'jumlah_A'=>$jumlah_step_A,
            'jumlah_B'=>$jumlah_step_B,
            'jumlah_C'=>$jumlah_step_C,
            'jumlah_D'=>$jumlah_step_D,
            'jumlah_E'=>$jumlah_step_E,
            'jumlah_F'=>$jumlah_step_F,
            'total'=>$jumlah_step_total,
        ]);
        return back()->with('message','Terimakasih telah mensubmit nilai !!');
    }
    public function nilaiPenguji(Request $request,$id){
        // return $request;
        $data2 = Dosen::pengujisemhas(Auth::user()->nim)->first();
        $cpembimbing = $data2->penguji_semhas;
        $cpem = $data2->penguji_ke;

        // BAB A
        $jumlah_step_1 = (($request->no1 * 20 * 1) + ($request->no2 * 20 * (1/2)) + ($request->no3 * 20 * (3/10)) + ($request->no4 * 20 * (1/5)))/2;
        $jumlah_step_2 = (($request->no5 * 30 * 1) + ($request->no6 * 30 * (6/10)) + ($request->no7 * 30 * (2/5)))/2;   
        $jumlah_step_3 = (($request->no8 * 40 * (1/2)) + ($request->no9 * 40 * (1/2)) + ($request->no10 * 40 * 1))/2;       
        $jumlah_step_4 = ($request->no11 * 10 * (1/4)) ;
        $jumlah_step_A = $jumlah_step_1 + $jumlah_step_2 + $jumlah_step_3 + $jumlah_step_4;
        
        // BAB B
        $jumlah_step_5 = ($request->no12 * 40 * (3/4)) + ($request->no13 * 40 * (1/4)) ;
        $jumlah_step_6 = ($request->no14 * 20 * (3/4)) + ($request->no15 * 20 * (1/4)) ;
        $jumlah_step_7 = ($request->no16 * 10 * (2/4)) + ($request->no17 * 10 * (2/4)) ;
        $jumlah_step_8 = ($request->no18 * 30 * (2/4)) + ($request->no19 * 30 * (35/100)) + ($request->no20 * 30 * (15/100)) ;
        $jumlah_step_B = $jumlah_step_5 + $jumlah_step_6 + $jumlah_step_7 + $jumlah_step_8;
        
        // BAB C
        $jumlah_step_9 = (($request->no21 * 60 * 1) + ($request->no22 * 60 * (3/5)) + ($request->no23 * 60 * (2/5)) + ($request->no24 * 60 * (1/5)))/2;
        $jumlah_step_10 = (($request->no25 * 40 * 1) + ($request->no26 * 40 * (35/100)) + ($request->no27 * 40 * (65/100)))/2;
        $jumlah_step_C = $jumlah_step_9 + $jumlah_step_10;
        
        // BAB D
        $jumlah_step_11 = ($request->no28 * 30 * (2/4)) + ($request->no29 * 30 * (2/4));
        $jumlah_step_12 = ($request->no30 * 50 * 1)/2 + ($request->no31 * 50 * (2/4))/2 + ($request->no32 * 50 * (3/10))/2 + ($request->no33 * 50 * (1/5))/2;
        $jumlah_step_13 = ($request->no34 * 10 * 1);
        $jumlah_step_14 = ($request->no35 * 10 * 1);
        $jumlah_step_D = $jumlah_step_11 + $jumlah_step_12 + $jumlah_step_13 + $jumlah_step_14;
        
        // BAB E
        $jumlah_step_15 = ($request->no36 * 25 * 1) + ($request->no37 * 25 * 1) + ($request->no38 * 25 * 1) + ($request->no39 * 25 * 1);
        $jumlah_step_E = $jumlah_step_15;
        //  BAB F
        $jumlah_step_16 = ($request->no40 * 10 * 1) + ($request->no41 * 10 * 1) + ($request->no42 * 10 * 1) + ($request->no43 * 10 * 1)
        + ($request->no44 * 10 * 1) + ($request->no45 * 10 * 1) + ($request->no46 * 10 * 1) + ($request->no47 * 10 * 1)
        + ($request->no48 * 20 * 1);
        $jumlah_step_F = $jumlah_step_16;
        
        // TOTAL ALL
        $jumlah_step_total = ($jumlah_step_A + $jumlah_step_B + $jumlah_step_C + $jumlah_step_D + $jumlah_step_15 + $jumlah_step_16)/6;

        // return $data2;
        $yakin = tabelnilai::where('nimnya',$id)->where('jabatan','penguji')
        ->where('no_pem',$cpem)->where('dosennya',$cpembimbing)->update([
            // 'nimnya' => $id,
            // 'jabatan' => 'pembimbing',
            // 'no_pem' => $data2->pem,
            'status' => 1,
            'dosennya' => $data2->pembimbing,
            'no_1' => $request->no1,
            'no_2' => $request->no2,
            'no_3' => $request->no3,
            'no_4' => $request->no4,
            'no_5' => $request->no5,
            'no_6' => $request->no6,
            'no_7' => $request->no7,
            'no_8' => $request->no8,
            'no_9' => $request->no9,
            'no_10' => $request->no10,
            'no_11' => $request->no11,
            'no_12' => $request->no12,
            'no_13' => $request->no13,
            'no_14' => $request->no14,
            'no_15' => $request->no15,
            'no_16' => $request->no16,
            'no_17' => $request->no17,
            'no_18' => $request->no18,
            'no_19' => $request->no19,
            'no_20' => $request->no20,
            'no_21' => $request->no21,
            'no_22' => $request->no22,
            'no_23' => $request->no23,
            'no_24' => $request->no24,
            'no_25' => $request->no25,
            'no_26' => $request->no26,
            'no_27' => $request->no27,
            'no_28' => $request->no28,
            'no_29' => $request->no29,
            'no_30' => $request->no30,
            'no_31' => $request->no31,
            'no_32' => $request->no32,
            'no_33' => $request->no33,
            'no_34' => $request->no34,
            'no_35' => $request->no35,
            'no_36' => $request->no36,
            'no_37' => $request->no37,
            'no_38' => $request->no38,
            'no_39' => $request->no39,
            'no_40' => $request->no40,
            'no_41' => $request->no41,
            'no_42' => $request->no42,
            'no_43' => $request->no43,
            'no_44' => $request->no44,
            'no_45' => $request->no45,
            'no_46' => $request->no46,
            'no_47' => $request->no47,
            'no_48' => $request->no48,
            'jumlah_A'=>$jumlah_step_A,
            'jumlah_B'=>$jumlah_step_B,
            'jumlah_C'=>$jumlah_step_C,
            'jumlah_D'=>$jumlah_step_D,
            'jumlah_E'=>$jumlah_step_E,
            'jumlah_F'=>$jumlah_step_F,
            'total'=>$jumlah_step_total,
        ]);
        return back()->with('message','Terimakasih telah mensubmit nilai !!');
    }

    public function submitrpl($id){
        // return 0;
        // return $id;
        // $check = Mahasiswa::where('nim')
        $check = tabelsubmitrpl::where('nim',$id)->get()->first();
        if($check == null){
            return back()->with('message','Maaf tidak bisa submit, anda belum memiliki dosen Penguji !!');

        }
        tabelsubmitrpl::where('nim',$id)->update([
            'status' => 1 
        ]);
        return back()->with('berhasil','Terimakasih Sudah Submit !!');

        // return $check;

    }

    public function tutuprpl(){
        tabelsubmitrpl::where('nim','xxx')->update([
            'status' => 0 
        ]);
        return back()->with('berhasil','RPL sukses ditutup !!');

        // return 'rpl ditutup';
    }

    public function bukarpl(){
        tabelsubmitrpl::where('nim','xxx')->update([
            'status' => 1 
        ]);
        return back()->with('berhasil','RPL sukses dibuka !!');
        // return 'rpl dibuka !';
    }
}
