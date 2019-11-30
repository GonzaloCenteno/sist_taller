<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tblviajes_via extends Model
{
    public $timestamps = false;
    protected $table = 'cronograma.vw_programacion_viajes';
    protected $primaryKey= 'via_id';

    public function scopeViajescount($query) 
    {
      return $query->select(DB::raw('count(*) as total'))->get();
    }
    
    public function scopeViajescountbyid($query,$veh_id) 
    {
      return $query->select(DB::raw('count(*) as total'))->where('veh_id',$veh_id)->get();
    }
}
