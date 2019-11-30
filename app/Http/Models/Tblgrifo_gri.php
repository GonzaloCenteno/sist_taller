<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tblgrifo_gri extends Model
{
    public $timestamps = false;
    protected $table = 'grifo.tblgrifo_gri';
    protected $primaryKey= 'gri_id';
}
