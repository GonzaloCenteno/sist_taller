<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tblconsumodetparcial_cdp extends Model
{
    public $timestamps = false;
    protected $table = 'taller.tblconsumodetparcial_cdp';
    protected $primaryKey= 'cdp_id';
}
