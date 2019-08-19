<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tblalmacenes_alm extends Model
{
    public $timestamps = false;
    protected $table = 'grifo.tblalmacenes_alm';
    protected $primaryKey= 'alm_id';
}
