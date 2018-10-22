<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Promo extends Model
{
    //
    protected $table = 'promos';
    protected $guarded = [];

    // public DB::table('promos')->get();
}
