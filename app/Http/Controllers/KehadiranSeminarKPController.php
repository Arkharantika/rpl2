<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Seminarkp;
use App\Models\dua_a;
use App\Models\TemporarySemKP;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;

use App\Models\tabelsubmitrpl;

class KehadiranSeminarKPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

     public function index()
    {
        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;
        $data_mhs = Mahasiswa::all();
        $data_judul = Seminarkp::all();
        $data = TemporarySemKP::all();
        $datanya = dua_a::where('nim',$nimnya)->get();
        
        $check = tabelsubmitrpl::where('nim',$nimnya)->get()->first();
        $submitan = tabelsubmitrpl::where('id',1)->get()->first();
        if($submitan->status == 0){
            return view('errors.errorpem'); 
        }
        if($check->status == 1){
            return view('errors.errorpem'); 
        }

        return view('kp.PresensiSeminar',compact('datanya','data_mhs','data_judul','mhs','data'));
        // return $check;
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first();
        $nimnya = $mhs->nim;
        $namanya = $mhs->nama_mhs;

        TemporarySemKP::create([
            'nim_mhs' => $nimnya,
            'nama_mhs' => $namanya,
            'nim_penseminar' => $request->nimPemateri
        ]);

        return redirect('PresensiSeminarKP')->with('message','Selamat, anda sudah melakukan presensi !!');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $check = TemporarySemKP::where('id',$id)->get()->first();
        $clarify = $check->nim_mhs;
        $mhs = Mahasiswa::mhs(Auth::user()->nim)->first()->nim;
        
        if ($clarify == $mhs ){
            TemporarySemKP::destroy($id);
            return redirect('PresensiSeminarKP')->with('message','Data berhasil dihapus !!');
        }else{
            return redirect('PresensiSeminarKP')->with('message','Maaf, anda tidak dapat menghapus data selain milik anda !!');
        }
        
    }
}