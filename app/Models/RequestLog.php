<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model {

    protected $table = 'request_log';

    protected $fillable = [
		'access_url',
		'ip_address',
		'country_code',
		'region_code',
		'region_name',
		'city',
    ];
}