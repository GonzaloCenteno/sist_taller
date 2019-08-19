<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tblbombas_bom extends Model
{
    public $timestamps = false;
    protected $table = 'grifo.tblbombas_bom';
    protected $primaryKey= 'bom_id';
}
