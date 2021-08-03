<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Config;

class Company extends \App\Models\Base\Company
{
    use Notifiable;

	protected $fillable = [
		'name',
		'email',
		'prefecture_id',
		'phone',
		'postcode',
		'city',
		'local',
		'street_address',
		'business_hour',
		'regular_holiday',
		'image',
		'fax',
		'url',
		'license_number'
	];
}
