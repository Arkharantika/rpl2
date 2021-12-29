<?php

namespace App\Http\Controllers\Dosen\Pembimbing;

use App\Models\Dosen;
use App\Models\Dosen2;
use App\Models\Pembimbing;
use App\Models\Ta;
use App\Models\Nilaisemhaspembimbing;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Mahasiswa;
use App\Models\tabelnilai;
// use App\Models\Ta;

class NilaisemhasController extends Controller
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
        //
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
        $validatedData = $request->validate([
            'id_pembimbing' => 'required',
            'a1' => 'required',
            'a2' => 'required',
            'a3' => 'required',
            'a4' => 'required',
            'b1' => 'required',
            'b2' => 'required',
            'b3' => 'required',
            'b4' => 'required',
            'b5' => 'required',
            'c1' => 'required',
            'c2' => 'required',
            'c3' => 'required',
            'revisi' => 'required',
        ]);

        $a = ($request->a1 + $request->a2 + $request->a3 + $request->a4) / 400 * 30;
        $b = ($request->b1 + $request->b2 + $request->b3 + $request->b4 + $request->b5) / 500 * 40;
        $c = ($request->c1 + $request->c2 + $request->c3) / 300 * 30;
        $total = $a + $b + $c;
        // dd($validatedData);

        Nilaisemhaspembimbing::Create([
            'ta_pembimbing_id' => $request->id_pembimbing,
            'a1' => $request->a1,
            'a2' => $request->a2,
            'a3' => $request->a3,
            'a4' => $request->a4,
            'b1' => $request->b1,
            'b2' => $request->b2,
            'b3' => $request->b3,
            'b4' => $request->b4,
            'b5' => $request->b5,
            'c1' => $request->c1,
            'c2' => $request->c2,
            'c3' => $request->c3,
            'revisi' => $request->revisi,
            'total' => $total,
            'status_nilai' => 0,
        ]);

        return redirect()->back()->with('message','Nilai berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return $id;
        $yusuf = Dosen::bimbinganta(Auth::user()->nim);
        $data2 = $yusuf->where('ta_id',$id)->first();
        // return $yusuf;
        $cpembimbing = $data2->pembimbing;
        $cpem = $data2->pem;

        $check = Auth::user()->nim;

        $murid = Ta::where('id',$id)->get()->first();
        $nimnya = $murid->mahasiswa_id;
        $data = Mahasiswa::where('id',$nimnya)->get()->first();
        $nimbro = $data->nim;

        // return $nimbro;

        $pengecheckM = tabelnilai::where('nimnya',$nimbro)->where('jabatan','pembimbing')
        ->where('status',0)
        ->where('no_pem',$cpem)->where('dosennya',$cpembimbing)->get()->first();
        if($pengecheckM != null){
            return view('dosen.semhas.nilai.view',compact('data','data2'));
        }

        $pengecheck = tabelnilai::where('nimnya',$nimbro)->where('jabatan','pembimbing')
        ->where('no_pem',$cpem)->where('dosennya',$cpembimbing)->get()->first();

        // return $pengecheck;

        // BAB A
        $jumlah_step_1 = (($pengecheck->no_1 * 20 * 1) + ($pengecheck->no_2 * 20 * (1/2)) + ($pengecheck->no_3 * 20 * (3/10)) + ($pengecheck->no_4 * 20 * (1/5)))/2;
        $jumlah_step_2 = (($pengecheck->no_5 * 30 * 1) + ($pengecheck->no_6 * 30 * (6/10)) + ($pengecheck->no_7 * 30 * (2/5)))/2;   
        $jumlah_step_3 = (($pengecheck->no_8 * 40 * (1/2)) + ($pengecheck->no_9 * 40 * (1/2)) + ($pengecheck->no_10 * 40 * 1))/2;       
        $jumlah_step_4 = ($pengecheck->no_11 * 10 * (1/4)) ;
        $jumlah_step_A = $jumlah_step_1 + $jumlah_step_2 + $jumlah_step_3 + $jumlah_step_4;
        
        // BAB B
        $jumlah_step_5 = ($pengecheck->no_12 * 40 * (3/4)) + ($pengecheck->no_13 * 40 * (1/4)) ;
        $jumlah_step_6 = ($pengecheck->no_14 * 20 * (3/4)) + ($pengecheck->no_15 * 20 * (1/4)) ;
        $jumlah_step_7 = ($pengecheck->no_16 * 10 * (2/4)) + ($pengecheck->no_17 * 10 * (2/4)) ;
        $jumlah_step_8 = ($pengecheck->no_18 * 30 * (2/4)) + ($pengecheck->no_19 * 30 * (35/100)) + ($pengecheck->no_20 * 30 * (15/100)) ;
        $jumlah_step_B = $jumlah_step_5 + $jumlah_step_6 + $jumlah_step_7 + $jumlah_step_8;
        
        // BAB C
        $jumlah_step_9 = (($pengecheck->no_21 * 60 * 1) + ($pengecheck->no_22 * 60 * (3/5)) + ($pengecheck->no_23 * 60 * (2/5)) + ($pengecheck->no_24 * 60 * (1/5)))/2;
        $jumlah_step_10 = (($pengecheck->no_25 * 40 * 1) + ($pengecheck->no_26 * 40 * (35/100)) + ($pengecheck->no_27 * 40 * (65/100)))/2;
        $jumlah_step_C = $jumlah_step_9 + $jumlah_step_10;
        
        // BAB D
        $jumlah_step_11 = ($pengecheck->no_28 * 30 * (2/4)) + ($pengecheck->no_29 * 30 * (2/4));
        $jumlah_step_12 = ($pengecheck->no_30 * 50 * 1)/2 + ($pengecheck->no_31 * 50 * (2/4))/2 + ($pengecheck->no_32 * 50 * (3/10))/2 + ($pengecheck->no_33 * 50 * (1/5))/2;
        $jumlah_step_13 = ($pengecheck->no_34 * 10 * 1);
        $jumlah_step_14 = ($pengecheck->no_35 * 10 * 1);
        $jumlah_step_D = $jumlah_step_11 + $jumlah_step_12 + $jumlah_step_13 + $jumlah_step_14;
        
        // BAB E
        $jumlah_step_15 = ($pengecheck->no_36 * 25 * 1) + ($pengecheck->no_37 * 25 * 1) + ($pengecheck->no_38 * 25 * 1) + ($pengecheck->no_39 * 25 * 1);
        
        //  BAB F
        $jumlah_step_16 = ($pengecheck->no_40 * 10 * 1) + ($pengecheck->no_41 * 10 * 1) + ($pengecheck->no_42 * 10 * 1) + ($pengecheck->no_43 * 10 * 1)
        + ($pengecheck->no_44 * 10 * 1) + ($pengecheck->no_45 * 10 * 1) + ($pengecheck->no_46 * 10 * 1) + ($pengecheck->no_47 * 10 * 1)
        + ($pengecheck->no_48 * 20 * 1);
        
        // TOTAL ALL
        $jumlah_step_total = ($jumlah_step_A + $jumlah_step_B + $jumlah_step_C + $jumlah_step_D + $jumlah_step_15 + $jumlah_step_16)/6;

        // return $jumlah_step_D;

        if($pengecheck != null){
            return view('dosen.semhas.nilai.view2',compact(
                'jumlah_step_11','jumlah_step_12','jumlah_step_13','jumlah_step_14','jumlah_step_D',
                'jumlah_step_9','jumlah_step_10','jumlah_step_C',
                'jumlah_step_15','jumlah_step_16','jumlah_step_total',
                'jumlah_step_5','jumlah_step_6','jumlah_step_7','jumlah_step_8','jumlah_step_B',
                'jumlah_step_A','jumlah_step_4','jumlah_step_3','jumlah_step_2','jumlah_step_1','data','data2','pengecheck'));
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Dosen::getbimbingansemhas($id,Auth::user()->nim);
        $nilai = Nilaisemhaspembimbing::where('ta_pembimbing_id', $data->id)->first();
        if ($nilai->status_nilai == 0) {
            return view('dosen.semhas.nilai.edit',compact('data','nilai'));
        }else {
            return redirect()->back();
        }
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
        $validatedData = $request->validate([
            'a1' => 'required',
            'a2' => 'required',
            'a3' => 'required',
            'a4' => 'required',
            'b1' => 'required',
            'b2' => 'required',
            'b3' => 'required',
            'b4' => 'required',
            'b5' => 'required',
            'c1' => 'required',
            'c2' => 'required',
            'c3' => 'required',
            'revisi' => 'required',
        ]);

        $a = ($request->a1 + $request->a2 + $request->a3 + $request->a4) / 400 * 30;
        $b = ($request->b1 + $request->b2 + $request->b3 + $request->b4 + $request->b5) / 500 * 40;
        $c = ($request->c1 + $request->c2 + $request->c3) / 300 * 30;
        $total = $a + $b + $c;
        // dd($total);

        Nilaisemhaspembimbing::where('id',$id)->update([
            'a1' => $request->a1,
            'a2' => $request->a2,
            'a3' => $request->a3,
            'a4' => $request->a4,
            'b1' => $request->b1,
            'b2' => $request->b2,
            'b3' => $request->b3,
            'b4' => $request->b4,
            'b5' => $request->b5,
            'c1' => $request->c1,
            'c2' => $request->c2,
            'c3' => $request->c3,
            'revisi' => $request->revisi,
            'total' => $total,
            'status_nilai' => 0,
        ]);

        return redirect(route('dosen.semhas.index'))->with('message','Update nilai berhasil disimpan!');
    }

    public function validasi(Request $request, $id)
    {
        Nilaisemhaspembimbing::where('id',$id)->update([
            'status_nilai' => 1,
        ]);

        return redirect()->back()->with('message','Nilai telah di Submit.');
    }

    public function updateStatus(Request $request)
    {
        $pem = Nilaisemhaspembimbing::findOrFail($request->pem_id);
        $pem->status_nilai = $request->status;
        $pem->save();

        // return redirect()->back();
        return response()->json(['message' => 'User status updated successfully.']);
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
}
