<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Helpers\CustomHelper;
use App\Models\AirlineRouteSearch;

class AirlineRoute extends Model {

    protected $table = 'airline_routes';

    protected $guarded = ['id'];

    protected $fillable = [];

    public function userData(): BelongsTo {
        return $this->BelongsTo('App\Models\User', 'user_id');
    }

    public function airlineData(): BelongsTo {
        return $this->BelongsTo('App\Models\AirlineMaster', 'airline', 'code');
    }

    public function sourceData(): BelongsTo {
        return $this->BelongsTo('App\Models\AirportCodesMaster', 'source', 'code');
    }

    public function destinationData(): BelongsTo {
        return $this->BelongsTo('App\Models\AirportCodesMaster', 'destination', 'code');
    }

    public function airlineRouteSegments(): HasMany {
        return $this->hasMany('App\Models\AirlineRouteSegment', 'airline_route_id');
    }

    public function arlineRouteInventory(): HasMany {
        return $this->hasMany('App\Models\AirlineRouteInventory', 'airline_route_id')->orderBy('admin_adult_price','asc');
    }

    public static function getAirlineRoutes($params=[]) {
    	$response = [];
    	// $response['success'] = false;
    	// $response['messsage'] = '';
    	$source = $params['from']??'';
    	$destination = $params['to']??'';
    	$dep = $params['dep']??'';
    	$tripType = $params['tripType']??0;
    	$pClass = $params['pClass']??'Economy';
    	$ADT = $params['ADT']??1;
    	$CHD = $params['CHD']??0;
    	$INF = $params['INF']??0;
    	if($source && $destination && $dep) {
            $inventory = $ADT + $CHD;
            $search_id = CustomHelper::genrateFlightSearchId();
            $req_data = [
                'slug' => $search_id,
                'source' => $source,
                'destination' => $destination,
                'dep' => $dep,
                'tripType' => $tripType,
                'pClass' => $pClass,
                'ADT' => $ADT,
                'CHD' => $CHD,
                'INF' => $INF,
                'inventory' => $inventory,
            ];
            $isSaved = AirlineRouteSearch::create($req_data);
            if($isSaved) {
                $params['search_id'] = $search_id;
            }
            $query = AirlineRoute::select('airline_routes.*',\DB::raw("(SELECT admin_adult_price FROM airline_route_inventory WHERE airline_route_inventory.airline_route_id = airline_routes.id AND status = 1 AND inventory >= ".$inventory." AND start_date <= '".$dep."' order by admin_adult_price asc limit 1) as sort"))->where('status', 1);
            $query->where('source',$source);
            $query->where('destination',$destination);
    		$query->withWhereHas('arlineRouteInventory', function($q1) use($dep, $inventory) {
    			$q1->where('status',1);
                $q1->where('inventory','>=',$inventory);
    			$q1->whereDate('start_date',$dep);
                // $q1->whereDate('start_date','<=',$dep);
    			// $q1->whereDate('end_date','>=',$dep);
    		});
    		$query->orderBy('sort','asc');//orderBy('featured','desc')->orderBy('sort_order','asc')->orderBy('name','asc');
    		$records = $query->get();
    		$records_arr = [];
    		if($records) {
    			foreach($records as $row) {
    				$airlineRouteData = AirlineRoute::parseAirlineRoute($row, $params);
                    if(isset($airlineRouteData['totalPriceList']) && count($airlineRouteData['totalPriceList']) > 0) {
                        $records_arr[] = $airlineRouteData;
                    }
    			}
    		}
    		$response['tripInfos']['ONWARD'] = $records_arr;
    	}
    	return $response;
    }

    public static function parseAirlineRoute($record,$params=[]) {
        $airlineRouteData = [];
        $dep = $params['dep']??'';
        $pClass = $params['pClass']??'Economy';
        if($record && $record->id) {
            // $storage = Storage::disk('public');
            // $path = 'cabs_sightseeing/';
            $size = $params['size']??'box';
            $search_data = $params['search_data']??[];

            $sI = [];
            $disable_before_departure_hour = $record->disable_before_departure_hour??0;
            $airlineRouteSegments = $record->airlineRouteSegments??[];                    
            if($airlineRouteSegments) {
                $arrival_date_counter = 0;
                $departure_date = $dep;
                $params['total_segments'] = count($airlineRouteSegments);
            	foreach($airlineRouteSegments as $key => $row) {
                    $segment_params = $params;
                    $segment_params['sN'] = $key;
                    $segment_params['departure_date'] = $departure_date;
                    $arrival_date = $departure_date;
                    $is_arrival_next_day = $row->is_arrival_next_day??0;
                    if($is_arrival_next_day) {
                        $arrival_date_counter += 1;
                        $arrival_date = date('Y-m-d', strtotime($departure_date. ' + '.$arrival_date_counter.' days'));
                        $departure_date = $arrival_date;
                    }                    
                    $segment_params['arrival_date'] = $arrival_date;
                    $connecting_time = 0;
                    if(isset($airlineRouteSegments[$key+1])) {
                        $connecting_time = ((strtotime($departure_date.' '.$airlineRouteSegments[$key+1]->start_time)-strtotime($arrival_date.' '.$airlineRouteSegments[$key]->end_time))/60);
                    }
                    $segment_params['connecting_time'] = $connecting_time;
            		$sI[] = AirlineRouteSegment::parseAirlineRouteSegment($row, $segment_params);
            	}
            }

            $totalPriceList = [];
            $arlineRouteInventory = $record->arlineRouteInventory??[];
            if($arlineRouteInventory) {
                foreach($arlineRouteInventory as $key => $row) {
                    if($pClass != $row->flight_class) {
                        continue;
                    }
                    if(isset($params['price_id'])) {
                        if($params['price_id'] != $row->id) {
                            continue;
                        }
                    }

                    if($disable_before_departure_hour) {
                        $disable_before_departure_time = date('Y-m-d H:i:s', strtotime('+'.$disable_before_departure_hour.' hours'));
                        // prd($dep.' '.$record->start_time.' == '.$disable_before_departure_time);
                        if(strtotime($dep.' '.$record->start_time) < strtotime($disable_before_departure_time)) {
                            continue; //Do not allow booking
                        }
                    }

                    $show_offline_flights = false;
                    if($row->fare_type == 2) {
                        $show_offline_flights = true;
                    }
                    if(!$show_offline_flights) {
                        $FLIGHT_OFFER_FARE_DISPLAY_TIMING = CustomHelper::WebsiteSettings('FLIGHT_OFFER_FARE_DISPLAY_TIMING');
                        $FLIGHT_OFFER_FARE_DISPLAY_TIMING_arr = json_decode($FLIGHT_OFFER_FARE_DISPLAY_TIMING,true);
                        $days = $FLIGHT_OFFER_FARE_DISPLAY_TIMING_arr['days']??[];
                        $start_time = $FLIGHT_OFFER_FARE_DISPLAY_TIMING_arr['timing']['start_time']??'';
                        $end_time = $FLIGHT_OFFER_FARE_DISPLAY_TIMING_arr['timing']['end_time']??'';
                        if($days && $start_time && $end_time) {
                            $day = date('l');
                            // pr($day.'='.$start_time.'='.$end_time);
                            if(in_array($day, $days) && strtotime(date('H:i:s')) >= strtotime($start_time) && strtotime(date('H:i:s')) <= strtotime($end_time)){
                                $show_offline_flights = true;
                            }
                        }
                    }

                    if($row->available_for && $show_offline_flights) {
                        $agent_id = $params['agent_id']??0;
                        $available_for = $row->available_for??'';
                        if($available_for == 'all_customer_agent') {
                            $show_offline_flights = true;
                        } else if($available_for == 'all_agent') {
                            if(!empty($agent_id)) {
                                $show_offline_flights = true;
                            } else {
                                $show_offline_flights = false;
                            }
                        } else if($available_for == 'all_customer') {
                            if(!empty($agent_id)) {
                                $show_offline_flights = false;
                            } else {
                                $show_offline_flights = true;
                            }
                        } else if($available_for == 'p_agents') {
                            if(!empty($agent_id)) {
                                $show_offline_flights = false;
                                $p_agents = explode(',', $row->p_agents);
                                if(!empty($p_agents) && in_array($agent_id, $p_agents)) {
                                    $show_offline_flights = true;
                                }
                            } else {
                                $show_offline_flights = false;
                            }
                        }
                    }
                    if($show_offline_flights) {
                        $inventory_params = $params;
                        $inventory_params['trip_type'] = $record->trip_type;
                        $inventory_params['airlineRouteSegments'] = $airlineRouteSegments;
                        $totalPriceList[] = AirlineRouteInventory::parseAirlineRouteInventory($row, $inventory_params);
                    }
                }
            }
            $airlineRouteData = [];
            $airlineRouteData['sI'] = $sI;
            $airlineRouteData['totalPriceList'] = $totalPriceList;
        }
        return $airlineRouteData;
    }
}