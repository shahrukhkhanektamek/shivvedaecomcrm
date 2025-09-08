<?php

namespace App\Models;

use App\CPU\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Offer extends Model
{
    const CREATED_AT = 'add_date_time';
    const UPDATED_AT = 'update_date_time';
    protected $table = 'offer';
    // protected $casts = [
    //     'parent_id' => 'integer',
    //     'position' => 'integer',
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime',
    //     'home_status' => 'integer',
    //     'priority' => 'integer'
    // ];


    public static function get_all_about_log()
    {
        return DB::table("offer")->orderBy("id","asc")->where('status',1)->get();
    }

    
}
