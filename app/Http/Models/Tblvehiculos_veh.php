<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Tblvehiculos_veh extends Model
{
    public $timestamps = false;
    protected $table = 'taller.tblvehiculos_veh';
    protected $primaryKey= 'veh_id';
}
