<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tblrutasestacion_rte extends Model
{
    public $timestamps = false;
    protected $table = 'taller.tblrutasestacion_rte';
    protected $primaryKey= 'rte_id';
    
    public function scopeTblrutaestacioncount($query) 
    {
      return $query->select(DB::raw('count(*) as total'));
    }
    
    public function scopePaginacion($query,$sidx,$sord,$limit,$start) 
    {
      return $query->orderBy($sidx, $sord)->limit($limit)->offset($start);
    }
    
    public function scopeRecuperar($query,$rte_id) 
    {
      return $query->where('rte_id',$rte_id);
    }
}
