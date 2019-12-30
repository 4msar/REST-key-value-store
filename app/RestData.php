<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RestData extends Model
{
	protected $fillable = ['key', 'value'];

	const ExpiresIn = 5;

	public static function boot()
	{
		parent::boot();
		self::creating(function($item){
			$item->expires_at = now()->addMinutes(self::ExpiresIn);
		});
		self::updating(function($item){
			$item->expires_at = now()->addMinutes(5);
		});
		self::retrieved(function($item){
			$item->status = 'expired in '. now()->diffInMinutes($item->expires_at);
			if( time() > strtotime($item->expires_at) ){
				$item->delete();
			}
		});
	}

    
}
