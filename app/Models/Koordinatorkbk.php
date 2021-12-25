<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Koordinatorkbk extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'koordinator_kbk';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = ['perusahaan_nama','perusahaan_almt','perusahaan_jenis','pic','tgl_mulai_kp','tgl_selesai_kp'];

    protected $guarded = [];
}
