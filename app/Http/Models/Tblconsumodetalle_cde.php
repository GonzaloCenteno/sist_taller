<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tblconsumodetalle_cde extends Model
{
    public $timestamps = false;
    protected $table = 'taller.tblconsumodetalle_cde';
    protected $primaryKey= 'cde_id';
}
