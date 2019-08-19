<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tblcentrocostos_cec extends Model
{
    public $timestamps = false;
    protected $table = 'grifo.tblcentrocostos_cec';
    protected $primaryKey= 'cec_id';
}
