<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tblestacion_est extends Model
{
    public $timestamps = false;
    protected $table = 'taller.tblestaciones_est';
    protected $primaryKey= 'est_id';
    
    public function scopeTblestacioncount($query) 
    {
      return $query->select(DB::raw('count(*) as total'));
    }
    
    public function scopePaginacion($query,$sidx,$sord,$limit,$start) 
    {
      return $query->orderBy($sidx, $sord)->limit($limit)->offset($start);
    }
    
    public function scopeRecuperar($query,$est_id) 
    {
      return $query->where('est_id',$est_id);
    }
}
