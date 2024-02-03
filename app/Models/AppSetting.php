<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    //

     protected $table = "app_settings";
    protected $fillable = [
        'role','version','ios_version','force_update','is_maintenance'
    ];
}
