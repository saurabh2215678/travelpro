<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\AirlineMaster;
use App\Models\AirportCodesMaster;

class AirlineRouteSegment extends Model {

    protected $table = 'airline_route_segments';

    protected $guarded = ['id'];

    protected $fillable = [];

    public function airlineData(): BelongsTo {
        return $this->BelongsTo('App\Models\AirlineMaster', 'airline', 'code');
    }

    public function sourceData(): BelongsTo {
        return $this->BelongsTo('App\Models\AirportCodesMaster', 'source', 'code');
    }

    public function destinationData(): BelongsTo {
        return $this->BelongsTo('App\Models\AirportCodesMaster', 'destination', 'code');
    }

    public static function parseAirlineRouteSegment($record, $params=[]) {
        $segmentData = [];
        if($record) {
            $sN = $params['sN']??0;
            $pClass = $params['pClass']??'Economy';
            $departure_date = $params['departure_date']??date('Y-m-d');
            $arrival_date = $params['arrival_date']??date('Y-m-d');
            $connecting_time = $params['connecting_time']??0;
            $ADT = $params['ADT']??1;
            $CHD = $params['CHD']??0;
            $INF = $params['INF']??0;
            $inventoryData = $params['inventoryData']??[];
            $total_segments = $params['total_segments']??1;

            $is_refundable = $inventoryData['is_refundable']??0;

            $airlineData = $record->airlineData??[];
            if(empty($airlineData)) {
                $airlineData = AirlineMaster::where('code',$record->airline)->first();
            }
        	$fD = [
        		'aI' => [
        			'code' => $record->airline,
        			'name' => $airlineData->name??'',
        			'isLcc' => ''
        		],
        		'fN' => $record->flight_number,
        		'eT' => ''
        	];
            $sourceData = $record->sourceData??[];
            if(empty($sourceData)) {
                $sourceData = AirportCodesMaster::where('code',$record->source)->first();
            }
        	$da = [
        		'code' => $record->source,
        		'name' => $sourceData->name??'',
        		'cityCode' => $sourceData->citycode??'',
        		'city' => $sourceData->city??'',
        		'country' => $sourceData->country??'',
        		'countryCode' => $sourceData->countrycode??'',
        		'terminal' => $record->source_terminal
        	];
            $destinationData = $record->destinationData??[];
            if(empty($destinationData)) {
                $destinationData = AirportCodesMaster::where('code',$record->destination)->first();
            }
        	$aa = [
        		'code' => $record->destination,
        		'name' => $destinationData->name??'',
        		'cityCode' => $destinationData->citycode??'',
        		'city' => $destinationData->city??'',
        		'country' => $destinationData->country??'',
        		'countryCode' => $destinationData->countrycode??'',
        		'terminal' => $record->destination_terminal
        	];

            $tIs = [];
            if($ADT){
                $adult_price = $inventoryData['admin_adult_price']??0;
                if($adult_price) {
                    $adult_price = ($adult_price/$total_segments)??0;
                }

                $NF = $adult_price;
                $BF = $adult_price;
                $TAF = 0;
                $TF = $adult_price;
                $OT = 0;

                $iB = '';
                $checkin_adult = $record->checkin_adult??'';
                if($checkin_adult) {
                    $iB = $checkin_adult.' Kg';
                }

                $cB = '';
                $cabin_adult = $record->cabin_adult??'';
                if($cabin_adult) {
                    $cB = $cabin_adult.' Kg';
                }
                for ($i=0; $i < $ADT; $i++) {
                    $tIs[] = [
                        'ssrInfo' => [],
                        'osi' => [],
                        'fd' => [
                            'fC' => [
                                'NF' => $NF,
                                'BF' => $BF,
                                'TAF' => $TAF,
                                'TF' => $TF,
                            ],
                            'afC' => [
                                'TAF' => [
                                    'YQ' => 0,
                                    'MF' => 0,
                                    'MFT' => 0,
                                    'OT' => $OT,
                                    'AGST' => 0,
                                    'MU' => 0,
                                    'YR' => 0,
                                ]
                            ],
                            'bI' => [
                                'iB' => $iB,
                                'cB' => $cB
                            ],
                            'rT' => $is_refundable,
                            'cc' => $pClass,
                            'cB' => '',
                            'fB' => '',
                            'mI' => 0,
                        ],
                        'ti' => '',
                        'pt' => '',
                        'fN' => '',
                        'lN' => '',
                    ];
                }
            }
            if($CHD){
                $child_price = $inventoryData['admin_child_price']??0;
                if($child_price) {
                    $child_price = ($child_price/$total_segments)??0;
                }

                $NF = $child_price;
                $BF = $child_price;
                $TAF = 0;
                $TF = $child_price;
                $OT = 0;

                $iB = '';
                $checkin_child = $record->checkin_child??'';
                if($checkin_child) {
                    $iB = $checkin_child.' Kg';
                }

                $cB = '';
                $cabin_child = $record->cabin_child??'';
                if($cabin_child) {
                    $cB = $cabin_child.' Kg';
                }
                for ($i=0; $i < $CHD; $i++) {
                    $tIs[] = [
                        'ssrInfo' => [],
                        'osi' => [],
                        'fd' => [
                            'fC' => [
                                'NF' => $NF,
                                'BF' => $BF,
                                'TAF' => $TAF,
                                'TF' => $TF,
                            ],
                            'afC' => [
                                'TAF' => [
                                    'YQ' => 0,
                                    'MF' => 0,
                                    'MFT' => 0,
                                    'OT' => $OT,
                                    'AGST' => 0,
                                    'MU' => 0,
                                    'YR' => 0,
                                ]
                            ],
                            'bI' => [
                                'iB' => $iB,
                                'cB' => $cB
                            ],
                            'rT' => $is_refundable,
                            'cc' => $pClass,
                            'cB' => '',
                            'fB' => '',
                            'mI' => 0,
                        ],
                        'ti' => '',
                        'pt' => '',
                        'fN' => '',
                        'lN' => '',
                    ];
                }
            }
            if($INF){
                $infant_price = $inventoryData['admin_infant_price']??0;
                if($infant_price) {
                    $infant_price = ($infant_price/$total_segments)??0;
                }

                $NF = $infant_price;
                $BF = $infant_price;
                $TAF = 0;
                $TF = $infant_price;
                $OT = 0;

                $iB = '';
                $checkin_infant = $record->checkin_infant??'';
                if($checkin_infant) {
                    $iB = $checkin_infant.' Kg';
                }

                $cB = '';
                $cabin_infant = $record->cabin_infant??'';
                if($cabin_infant) {
                    $cB = $cabin_infant.' Kg';
                }
                for ($i=0; $i < $INF; $i++) {
                    $tIs[] = [
                        'ssrInfo' => [],
                        'osi' => [],
                        'fd' => [
                            'fC' => [
                                'NF' => $NF,
                                'BF' => $BF,
                                'TAF' => $TAF,
                                'TF' => $TF,
                            ],
                            'afC' => [
                                'TAF' => [
                                    'YQ' => 0,
                                    'MF' => 0,
                                    'MFT' => 0,
                                    'OT' => $OT,
                                    'AGST' => 0,
                                    'MU' => 0,
                                    'YR' => 0,
                                ]
                            ],
                            'bI' => [
                                'iB' => $iB,
                                'cB' => $cB
                            ],
                            'rT' => $is_refundable,
                            'cc' => $pClass,
                            'cB' => '',
                            'fB' => '',
                            'mI' => 0,
                        ],
                        'ti' => '',
                        'pt' => '',
                        'fN' => '',
                        'lN' => '',
                    ];
                }
            }

            $bI = [
                'tI' => $tIs
            ];

        	$dt = $departure_date.' '.$record->start_time;
        	$at = $arrival_date.' '.$record->end_time;
        	$duration = (strtotime($at)-strtotime($dt))/60;
            $segmentData = [];
            $segmentData['id'] = $record->id??'';
            $segmentData['fD'] = $fD;
            $segmentData['stops'] = $record->stops??0;
            $segmentData['so'] = [];
            $segmentData['duration'] = $duration;
            $segmentData['cT'] = $connecting_time;
            $segmentData['da'] = $da;
            $segmentData['aa'] = $aa;
            $segmentData['dt'] = $dt;
            $segmentData['at'] = $at;
            $segmentData['iand'] = '';
            $segmentData['isRs'] = '';
            $segmentData['sN'] = $sN;
            $segmentData['bI'] = $bI;
        }
        return $segmentData;    
    }
}