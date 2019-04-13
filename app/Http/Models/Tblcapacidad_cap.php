<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tblcapacidad_cap extends Model
{
    public $timestamps = false;
    protected $table = 'taller.tblcapacidad_cap';
    protected $primaryKey= 'cap_id';
    
    public function scopeTblcapacidadcount($query) 
    {
      return $query->select(DB::raw('count(*) as total'));
    }
    
    public function scopePaginacion($query,$sidx,$sord,$limit,$start) 
    {
      return $query->orderBy($sidx, $sord)->limit($limit)->offset($start);
    }
    
    public function scopeRecuperar($query,$cap_id) 
    {
      return $query->where('cap_id',$cap_id);
    }
}
