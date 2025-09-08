<?php

namespace App\Models;

use App\CPU\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Classmail extends Model
{
    const CREATED_AT = 'add_date_time';
    const UPDATED_AT = 'update_date_time';
    protected $table = 'class_mail';

    public static function all_class_mail()
    {
        return DB::table("class_mail")->where('status',1)->get();
    }    
}
