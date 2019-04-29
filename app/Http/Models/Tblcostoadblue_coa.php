<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tblcostoadblue_coa extends Model
{
    public $timestamps = false;
    protected $table = 'taller.tblcostoadblue_coa';
    protected $primaryKey= 'coa_id';
    
    public function scopeTblcostoadbluecount($query) 
    {
      return $query->select(DB::raw('count(*) as total'));
    }
    
    public function scopePaginacion($query,$sidx,$sord,$limit,$start) 
    {
      return $query->orderBy($sidx, $sord)->limit($limit)->offset($start);
    }
    
    public function scopeRecuperar($query,$coa_id) 
    {
      return $query->where('coa_id',$coa_id);
    }
}
