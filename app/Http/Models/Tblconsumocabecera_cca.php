<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tblconsumocabecera_cca extends Model
{
    public $timestamps = false;
    protected $table = 'taller.tblconsumocabecera_cca';
    protected $primaryKey= 'cca_id';
    
}
