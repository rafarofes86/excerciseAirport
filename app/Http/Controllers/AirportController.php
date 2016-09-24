<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Airport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use Illuminate\Http\Request;

class AirportController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{ 
            if(!Cache::has("index1")){
               $listAirport = Airport::all();
               Cache::put('index1',$listAirport,3600);
            } 
            
           $response = Cache::get("index1");
           return response()->json($response);
        }
        
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
            if(!Cache::has("show".$id)){
               $airport = Airport::find($id);
               Cache::put("show".$id,$airport,3600);
            } 
            
           $response = Cache::get("show".$id);
           return response()->json($response);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

        function shortestDistance ($country1,$country2){
            
            if(!Cache::has("shortestDistance".$country1.$country2)){
                $response = array('distance'=>0,'airport1'=>'','airport2'=>'');
                $listCountry1 = DB::table('airport')->where('country',$country1)->get();
                $listCountry2 = DB::table('airport')->where('country',$country2)->get();


                for($i=0; $i<count($listCountry1);$i++){
                    for($j=0; $j<count($listCountry2);$j++){
                        $distance = $this->distanceCalculation($listCountry1[$i]->latitude, $listCountry1[$i]->longitude, $listCountry2[$j]->latitude, $listCountry2[$j]->longitude);
                        if(empty($response['distance']) || $response['distance']>$distance){
                            $response['distance'] = $distance;
                            $response['airport1'] = $listCountry1[$i];
                            $response['airport2'] = $listCountry2[$j];
                        }
                    }     
                }
                
                Cache::put("shortestDistance".$country1.$country2,$response,360);
                return response()->json($response);
            }
            
            $response=Cache::get($country1.$country2);
            return response()->json($response);
        }
        
        function distance($id1,$id2){
            
            if(!Cache::has("distance".$id1.$id2)){
                $point1 = DB::table('airport')->where('id',$id1)->select('latitude','longitude')->first();
                $point2 = DB::table('airport')->where('id',$id2)->select('latitude','longitude')->first();
                $response['distance']=$this->distanceCalculation($point1->latitude, $point1->longitude, $point2->latitude, $point2->longitude);
                
                Cache::put("distance".$id1.$id2,$response,3600);
            }
            
            $response = Cache::get("distance".$id1.$id2);
            return response()->json($response);
        }
        
        function radiusCoordinates($lat,$long,$rad){
            if(!Cache::has("radiusCoordinates".$lat."|".$long."|".$rad)){
                $listAirport['airports'] = DB::table('airport')->select(DB::raw("id, ( 6371 * ACOS( 
                                 COS( RADIANS(".$lat.") ) 
                                 * COS(RADIANS( latitude ) ) 
                                 * COS(RADIANS( longitude ) 
                                 - RADIANS(".$long.") ) 
                                 + SIN( RADIANS(".$lat.") ) 
                                 * SIN(RADIANS( latitude )))) AS distance,
                                 airport_name,city,country,faa as FAA, icao as ICAO,latitude, longitude,
                                 altitude, timezone "))
                                ->groupBy('distance')
                                ->having('distance', '<', $rad)
                                ->get();
                Cache::put("radiusCoordinates".$lat."|".$long."|".$rad,$listAirport,3600);
            }    
            $listAirport = Cache::get("radiusCoordinates".$lat."|".$long."|".$rad);
            return response()->json($listAirport);
        }
        
        function distanceCalculation($lat1, $long1, $lat2, $long2,$decimals = 2) {
            $degrees = rad2deg(acos((sin(deg2rad($lat1))*sin(deg2rad($lat2))) + (cos(deg2rad($lat1))*cos(deg2rad($lat2))*cos(deg2rad($long1-$long2)))));
            $distance = $degrees * 111.13384; // 1 degrees = 111.13384 km, based on the average diameter of the Earth (12,735 km)
            return round($distance,2);
        }

}
