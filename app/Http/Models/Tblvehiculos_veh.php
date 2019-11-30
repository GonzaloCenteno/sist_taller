<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tblvehiculos_veh extends Model
{
    public $timestamps = false;
    protected $table = 'taller.tblvehiculos_veh';
    protected $primaryKey= 'veh_id';
    
    public function scopeTblvehiculocount($query) 
    {
      return $query->select(DB::raw('count(*) as total'))->whereNotIn('veh_id',[1,219])->get();
    }
    
    public function scopePaginacion($query,$sidx,$sord,$limit,$start) 
    {
      return $query->whereNotIn('veh_id',[1,219])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
    }
    
    public function scopeRecuperar($query,$veh_id) 
    {
      return $query->where('veh_id',$veh_id);
    }
    
    public function scopeTblvehiculoLikecount($query,$data) 
    {
      return $query->select(DB::raw('count(*) as total'))->whereNotIn('veh_id',[1,219])->where('veh_placa','ilike','%'.$data.'%')->get();
    }
    
    public function scopePaginacionLike($query,$sidx,$sord,$limit,$start,$data) 
    {
      return $query->whereNotIn('veh_id',[1,219])->where('veh_placa','ilike','%'.$data.'%')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
    }
    
    public function scopeUniqueCodigo($query,$codigo,$veh_id)
    {
        return $query->where([['veh_codigo',strtoupper($codigo)],['veh_id','<>',$veh_id]])->get();
    }
}
