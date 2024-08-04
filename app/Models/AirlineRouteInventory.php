<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Helpers\CustomHelper;

class AirlineRouteInventory extends Model {

    protected $table = 'airline_route_inventory';

    protected $guarded = ['id'];

    protected $fillable = [];    

    public function userData(): BelongsTo {
        return $this->BelongsTo('App\Models\User', 'user_id');
    }

    public function routeData(): BelongsTo {
        return $this->BelongsTo('App\Models\AirlineRoute', 'airline_route_id');
    }

    public function inventorySoldCount() {
        return Order::where('payment_status',1)->where('inventory_id',$this->id)->sum('inventory'); //->where('status','SUCCESS')
    }

    public static function parseAirlineRouteInventory($record, $params=[]) {
        $inventoryData = [];
        if($record && $record->id) {
			$is_refundable = $record->is_refundable??0;
			$inventory = $record->inventory??0;            
			$fd = [];
			$ADT = $params['ADT']??0;
            $CHD = $params['CHD']??0;
            $INF = $params['INF']??0;
            $trip_type = $params['trip_type']??'';
            if(empty($trip_type)) {
                $routeData = $record->routeData??[];
                $trip_type = $routeData->trip_type??'Domestic';
            }

            $search_id = $params['search_id']??0;

            $settings_arr = [
                'FARE_RULE_DOMESTIC_CANCELLATION_FEE',
                'FARE_RULE_DOMESTIC_DATE_CHANGE_FEE',
                'FARE_RULE_DOMESTIC_NO_SHOW',
                'FARE_RULE_DOMESTIC_SEAT_CHARGEABLE',
                'FARE_RULE_INTERNATIONAL_TYPE',
                'FARE_RULE_INTERNATIONAL_CANCELLATION_FEE',
                'FARE_RULE_INTERNATIONAL_DATE_CHANGE_FEE',
                'FARE_RULE_INTERNATIONAL_NO_SHOW',
                'FARE_RULE_INTERNATIONAL_SEAT_CHARGEABLE'
            ];
            $websiteSettingsArr = CustomHelper::websiteSettingsArray($settings_arr);
            if($trip_type == 'International') {
                $CANCELLATION_policyInfo = (isset($websiteSettingsArr['FARE_RULE_INTERNATIONAL_CANCELLATION_FEE']))?$websiteSettingsArr['FARE_RULE_INTERNATIONAL_CANCELLATION_FEE']->value:'';
                $DATECHANGE_policyInfo = (isset($websiteSettingsArr['FARE_RULE_INTERNATIONAL_DATE_CHANGE_FEE']))?$websiteSettingsArr['FARE_RULE_INTERNATIONAL_DATE_CHANGE_FEE']->value:'';
                $NO_SHOW_policyInfo = (isset($websiteSettingsArr['FARE_RULE_INTERNATIONAL_NO_SHOW']))?$websiteSettingsArr['FARE_RULE_INTERNATIONAL_NO_SHOW']->value:'';
                $SEAT_CHARGEABLE_policyInfo = (isset($websiteSettingsArr['FARE_RULE_INTERNATIONAL_SEAT_CHARGEABLE']))?$websiteSettingsArr['FARE_RULE_INTERNATIONAL_SEAT_CHARGEABLE']->value:'';
            } else {
                $CANCELLATION_policyInfo = (isset($websiteSettingsArr['FARE_RULE_DOMESTIC_CANCELLATION_FEE']))?$websiteSettingsArr['FARE_RULE_DOMESTIC_CANCELLATION_FEE']->value:'';
                $DATECHANGE_policyInfo = (isset($websiteSettingsArr['FARE_RULE_DOMESTIC_DATE_CHANGE_FEE']))?$websiteSettingsArr['FARE_RULE_DOMESTIC_DATE_CHANGE_FEE']->value:'';
                $NO_SHOW_policyInfo = (isset($websiteSettingsArr['FARE_RULE_DOMESTIC_NO_SHOW']))?$websiteSettingsArr['FARE_RULE_DOMESTIC_NO_SHOW']->value:'';
                $SEAT_CHARGEABLE_policyInfo = (isset($websiteSettingsArr['FARE_RULE_DOMESTIC_SEAT_CHARGEABLE']))?$websiteSettingsArr['FARE_RULE_DOMESTIC_SEAT_CHARGEABLE']->value:'';

            }

            $airlineRouteSegments = $params['airlineRouteSegments']??[];
            $ADULT_bI_iB = '';
            $ADULT_bI_cB = '';
            $CHILD_bI_iB = '';
            $CHILD_bI_cB = '';
            $INFANT_bI_iB = '';
            $INFANT_bI_cB = '';
            $baggage_data = [];
            if($airlineRouteSegments) {
                foreach($airlineRouteSegments as $key => $airlineRouteSegment) {
                    if($key == 0) {
                        if($ADT) {
                            $ADULT_bI_iB = $airlineRouteSegment->checkin_adult.' Kg';
                            $ADULT_bI_cB = $airlineRouteSegment->cabin_adult.' Kg';
                        }
                        if($CHD) {
                            $CHILD_bI_iB = $airlineRouteSegment->checkin_child.' Kg';
                            $CHILD_bI_cB = $airlineRouteSegment->cabin_child.' Kg';
                        }
                        if($INF) {
                            $INFANT_bI_iB = $airlineRouteSegment->checkin_infant.' Kg';
                            $INFANT_bI_cB = $airlineRouteSegment->cabin_infant.' Kg';
                        }
                    }
                }
            }

            if($record->fare_type=='2') {
                $fareIdentifier = 'IIAIR_OFFER_FARE_WITH_PNR';
            } else {
                $fareIdentifier = 'IIAIR_OFFER_FARE_WITHOUT_PNR';
            }
            $searchQuery = [];
            if($trip_type == 'Domestic') {
                $searchQuery['isDomestic'] = 1;
            } else {
                $searchQuery['isDomestic'] = 0;
            }
			if($ADT) {
                $adult_price = ($record->admin_adult_price > 0)?$record->admin_adult_price:$record->adult_price;
                $markup = 0;
                $markup_type = $record->markup_type??0;
                if($markup_type == 1) {
                    $markup += $record->markup??0;
                } else {
                    $paxInfo = [
                        'ADULT' => 1,
                        'CHILD' => 0,
                        'INFANT' => 0,
                    ];
                    $searchQuery['paxInfo'] = $paxInfo;
                    $admin_markup = 0;
                    $admin_markup_details = CustomHelper::getAdminMarkupDetails(($adult_price+$markup),$searchQuery,1,$fareIdentifier);
                    if(!empty($admin_markup_details) && isset($admin_markup_details['markup_price']) && $admin_markup_details['markup_price'] > 0) {
                        $admin_markup = $admin_markup_details['markup_price'];
                    }
                    $markup += $admin_markup;
                }

				// $BF = ($record->adult_price??0);//*$ADT;
                $BF = $adult_price;//($record->adult_price??0);//*$ADT;
				$OT = $markup;//*$ADT;
				$TAF = $OT;
				$TF = sprintf("%.2f",($BF + $TAF));
				$NF = $TF;
				$fd['ADULT'] = [
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
                    'sR' => $inventory,
                    'bI' => [
                        'iB' => $ADULT_bI_iB,
                        'cB' => $ADULT_bI_cB
                    ],
                    'rT' => $is_refundable,
                    'cc' => $record->flight_class,
                    'cB' => 'GS',
                    'fB' => '',
                    'mI' => ''
				];
			}
			
			if($CHD) {
                $child_price = ($record->admin_child_price > 0)?$record->admin_child_price:$record->child_price;
                $markup = 0;
                $markup_type = $record->markup_type??0;
                if($markup_type == 1) {
                    $markup += $record->markup??0;
                } else {
                    $paxInfo = [
                        'ADULT' => 0,
                        'CHILD' => 1,
                        'INFANT' => 0,
                    ];
                    $searchQuery['paxInfo'] = $paxInfo;
                    $admin_markup = 0;
                    $admin_markup_details = CustomHelper::getAdminMarkupDetails(($child_price+$markup),$searchQuery,1,$fareIdentifier);
                    if(!empty($admin_markup_details) && isset($admin_markup_details['markup_price']) && $admin_markup_details['markup_price'] > 0) {
                        $admin_markup = $admin_markup_details['markup_price'];
                    }
                    $markup += $admin_markup;
                }

				$BF = $child_price; //($record->child_price??0);//*$CHD;
				$OT = $markup;//*$CHD;
				$TAF = $OT;
				$TF = sprintf("%.2f",($BF + $TAF));
				$NF = $TF;
				$fd['CHILD'] = [
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
                    'sR' => $inventory,
                    'bI' => [
                        'iB' => $CHILD_bI_iB,
                        'cB' => $CHILD_bI_cB
                    ],
                    'rT' => $is_refundable,
                    'cc' => $record->flight_class,
                    'cB' => 'GS',
                    'fB' => '',
                    'mI' => ''
				];
			}

            if($INF) {
                $infant_price = ($record->admin_infant_price > 0)?$record->admin_infant_price:$record->infant_price;
                $markup = 0;
                $markup_type = $record->markup_type??0;
                if($markup_type == 1) {
                    $markup += $record->markup??0;
                } else {
                    $paxInfo = [
                        'ADULT' => 0,
                        'CHILD' => 0,
                        'INFANT' => 1,
                    ];
                    $searchQuery['paxInfo'] = $paxInfo;
                    $admin_markup = 0;
                    $admin_markup_details = CustomHelper::getAdminMarkupDetails(($infant_price+$markup),$searchQuery,1,$fareIdentifier);
                    if(!empty($admin_markup_details) && isset($admin_markup_details['markup_price']) && $admin_markup_details['markup_price'] > 0) {
                        $admin_markup = $admin_markup_details['markup_price'];
                    }
                    $markup += $admin_markup;
                }

				$BF = $infant_price;//($record->infant_price??0);//*$INF;
				$OT = $markup;//*$INF;
				$TAF = $OT;
				$TF = $BF + $TAF;
				$NF = $TF;
				$fd['INFANT'] = [
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
                    'sR' => $inventory,
                    'bI' => [
                        'iB' => $INFANT_bI_iB,
                        'cB' => $INFANT_bI_cB
                    ],
                    'rT' => $is_refundable,
                    'cc' => $record->flight_class,
                    'cB' => 'GS',
                    'fB' => '',
                    'mI' => ''
				];
			}

            $inventoryData = [
                'fd' => $fd,
                'fareIdentifier' => $fareIdentifier,
                'id' => $record->id.'-'.$search_id,
                'msri' => [],
                'tai' => [
                    'tbi' => [
                        $record->id => [
                            '0' => [
                                'ADULT' => [
                                    'iB' => '15 Kg',
                                    'cB' => '7 Kg'
                                ]
                            ]
                        ]
                    ]
                ],
                'fareRuleInformation' => [
                    'fr' => [
                        'CANCELLATION' => [
                            'DEFAULT' => [
                                'additionalFee' => '',
                                'policyInfo' => $CANCELLATION_policyInfo,
                            ]
                        ],
                        'DATECHANGE' => [
                            'DEFAULT' => [
                                'additionalFee' => '',
                                'policyInfo' => $DATECHANGE_policyInfo,
                            ]
                        ],
                        'NO_SHOW' => [
                            'DEFAULT' => [
                                'additionalFee' => '',
                                'policyInfo' => $NO_SHOW_policyInfo,
                            ]
                        ],
                        'SEAT_CHARGEABLE' => [
                            'DEFAULT' => [
                                'additionalFee' => '',
                                'policyInfo' => $SEAT_CHARGEABLE_policyInfo,
                            ]
                        ]
                    ]
                ],
                'inventory_data' => [
                    'agent_adult_price' => $record->agent_adult_price,
                    'agent_child_price' => $record->agent_child_price,
                    'agent_infant_price' => $record->agent_infant_price,
                    'adult_price' => $record->adult_price,
                    'child_price' => $record->child_price,
                    'infant_price' => $record->infant_price,
                    'admin_adult_price' => $record->admin_adult_price,
                    'admin_child_price' => $record->admin_child_price,
                    'admin_infant_price' => $record->admin_infant_price,
                ]
            ];
        }
        return $inventoryData;
    }
}