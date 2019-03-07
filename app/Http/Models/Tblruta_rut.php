<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tblruta_rut extends Model
{
    public $timestamps = false;
    protected $table = 'taller.tblrutas_rut';
    protected $primaryKey= 'rut_id';
    
    public function scopeTblrutacount($query) 
    {
      return $query->select(DB::raw('count(*) as total'));
    }
    
    public function scopePaginacion($query,$sidx,$sord,$limit,$start) 
    {
      return $query->orderBy($sidx, $sord)->limit($limit)->offset($start);
    }
    
    public function scopeRecuperar($query,$rut_id) 
    {
      return $query->where('rut_id',$rut_id);
    }
}
