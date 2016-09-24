<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model{
    
    protected $table = 'airport';
    protected $fillable = array('airport_name','city','country','faa','icao','coordinates','altitude','timezone');
   
}


