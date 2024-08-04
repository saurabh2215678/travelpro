<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\EnquiryInteraction;
use App\Models\EnquiryForType;
use App\Models\EnquiryDestination;
use App\Models\EnquiryAccommodation;
use App\Models\EnquiryAccommodationType;
use App\Models\Destination;
use App\Models\Accommodation;
use App\Helpers\CustomHelper;

class Enquiry extends Model {

	protected $table = 'enquiries';
	protected $guarded = ['id'];
	protected $fillable = [];

	public function Form() {
		return $this->belongsTo('App\Models\Form', 'form_id');
	}

	public function UserData() {
		return $this->belongsTo('App\Models\User', 'user_id');
	}
	/**
     * @return HasMany
     */
	public function EnquiryForType(): HasMany
	{
		return $this->hasMany('App\Models\EnquiryForType', 'enquiry_id');
	}	
	/**
     * @return HasMany
     */
	public function Destination(): HasMany
	{
		return $this->hasMany('App\Models\EnquiryDestination', 'enquiry_id')->where('type','destination');
	}
	/**
     * @return HasMany
     */
	public function SubDestination(): HasMany
	{
		return $this->hasMany('App\Models\EnquiryDestination', 'enquiry_id')->where('type','sub_destination');
	}

	public function Package() {
		return $this->belongsTo('App\Models\Package', 'package_id');
	}
	/**
     * @return HasMany
     */
	public function Accommodation():HasMany
	{
		return $this->hasMany('App\Models\EnquiryAccommodation', 'enquiry_id');
	}
	
	public function AccommodationType() {
		return $this->hasMany('App\Models\EnquiryAccommodationType', 'enquiry_id');
	}
	/**
     * @return HasMany
     */
	public function EnquiryInteraction(): HasMany
	{
		return $this->hasMany('App\Models\EnquiryInteraction', 'enquiry_id')->orderBy('created_at','desc');
	}

	public function LastInteraction() {
		return $this->hasOne('App\Models\EnquiryInteraction', 'enquiry_id')->orderBy('created_at','desc');
	}

	public function Order() {
		return $this->belongsTo('App\Models\Order', 'order_id');
	}

	public function GetCategory() {
		return $this->belongsTo('App\Models\EnquiriesMaster', 'lead_status');
	}
	
	public function GetColor() {
		return $this->belongsTo('App\Models\EnquiriesMaster', 'rating');
	}

	public static function CreateEnquiry($data, $params=[]) {
		// prd($data);
		// prd($params);
		$response = [];
		$response['success'] = false;
		$response['msg'] = '';
		try {
			$id = $params['id']??'';
			$record = [];
			$user_record = [];
			$user_params = [];
			if(array_key_exists('order_id',$data)) {
				$record['order_id'] = $data['order_id']??NULL;
			}
			if(array_key_exists('form_id',$data)) {
				$record['form_id'] = $data['form_id'];
			}
			if(array_key_exists('refer_url',$data)) {
				$record['refer_url'] = $data['refer_url'];
			}
			if(array_key_exists('form_data',$data)) {
				$record['form_data'] = json_encode($data['form_data']);
			}
			if(array_key_exists('cc_id',$data)) {
				$record['cc_id'] = $data['cc_id'];
			}
			if(array_key_exists('name',$data)) {
				$record['name'] = $data['name'];
				$user_record['name'] = $data['name'];
			}
			if(array_key_exists('country_code',$data)) {
				$record['country_code'] = $data['country_code'];
				$user_record['country_code'] = $data['country_code'];
			}
			if(array_key_exists('phone',$data)) {
				$record['phone'] = $data['phone'];
				$user_record['phone'] = $data['phone'];
			}
			if(array_key_exists('email',$data)) {
				$record['email'] = $data['email'];
				$user_record['email'] = $data['email'];
			}
			if(array_key_exists('content',$data)) {
				$record['content'] = $data['content'];
			}
			if(array_key_exists('package_id',$data) && !empty($data['package_id'])) {
				$record['package_id'] = $data['package_id'];
				$record['package_name'] = CustomHelper::showPackage($record['package_id']) ?? [];
			}
			if(array_key_exists('activity',$data)) {
				$record['activity'] = $data['activity'];
			}
			if(array_key_exists('lead_source',$data)) {
				$record['lead_source'] = $data['lead_source'];
			}
			if(array_key_exists('lead_status',$data)) {
				$record['lead_status'] = $data['lead_status'];
			}
			if(array_key_exists('lead_sub_status',$data)) {
				$record['lead_sub_status'] = $data['lead_sub_status'];
			}
			if(array_key_exists('rating',$data)) {
				$record['rating'] = $data['rating'];
			}
			if(array_key_exists('followup_date',$data)) {
				$record['followup_date'] = (!empty($data['followup_date']))?$data['followup_date']:NULL;
			}
			if(array_key_exists('status',$data)) {
				$record['status'] = $data['status']??0;
			}
			if(array_key_exists('owner_id',$data)) {
				$record['owner_id'] = $data['owner_id'];
			}
			if(array_key_exists('owner_date',$data)) {
				$record['owner_date'] = $data['owner_date'];
			}
			if(array_key_exists('other_destination',$data)) {
				$record['other_destination'] = $data['other_destination'];
			} else if(array_key_exists('destination',$data)) {
				if(!is_numeric($data['destination'])) {
					$record['other_destination'] = $data['destination'];
				}
			}
			if(array_key_exists('other_package',$data)) {
				$record['other_package'] = $data['other_package'];
			} else if(array_key_exists('package',$data)) {
				if(!is_numeric($data['package'])) {
					$record['other_package'] = $data['package'];
				}
			}
			if(array_key_exists('accommodation_comment',$data)) {
				$record['accommodation_comment'] = $data['accommodation_comment'];
			} else if(array_key_exists('accommodation',$data)) {
				if(!is_numeric($data['accommodation'])) {
					$record['accommodation_comment'] = $data['accommodation'];
				}
			}
			if(array_key_exists('other_activity',$data)) {
				$record['other_activity'] = $data['other_activity'];
			} else if(array_key_exists('activity',$data)) {
				if(!is_numeric($data['activity'])) {
					$record['other_activity'] = $data['activity'];
				}
			}
			if(array_key_exists('meal_plans',$data)) {
				if(is_array($data['meal_plans'])) {
					$meal_plans = $data['meal_plans'];
				} else {
					$meal_plans = explode(',', $data['meal_plans']);
				}
				$record['meal_plans'] = json_encode($meal_plans);
			}
			if(array_key_exists('date_of_travel',$data)) {
				$date_of_travel = (!empty($data['date_of_travel']))?$data['date_of_travel']:NULL;
				if($date_of_travel) {
					$record['date_of_travel'] = CustomHelper::DateFormat($date_of_travel,'Y-m-d');
				}
			}
			if(array_key_exists('duration',$data)) {
				$record['duration'] = $data['duration'];
			}
			if(array_key_exists('inclusions',$data)) {
				$record['inclusions'] = $data['inclusions'];
			}
			if(array_key_exists('no_of_pax',$data)) {
				$record['no_of_pax'] = $data['no_of_pax'];
			}
			if(array_key_exists('adu_abo_12',$data)) {
				$record['adu_abo_12'] = $data['adu_abo_12'];
			}
			if(array_key_exists('chi_6_12',$data)) {
				$record['chi_6_12'] = $data['chi_6_12'];
			}
			if(array_key_exists('chi_2_5',$data)) {
				$record['chi_2_5'] = $data['chi_2_5'];
			}
			if(array_key_exists('infants_upto_2',$data)) {
				$record['infants_upto_2'] = $data['infants_upto_2'];
			}
			if(array_key_exists('viewed',$data)) {
				$record['viewed'] = $data['viewed'];
			}
			if(!empty($record)) {
				if($id) {
					$isSaved = Enquiry::where('id',$id)->update($record);
					if($isSaved) {

						$response['id'] = $id;
						$response['success'] = true;
					}
			
				} else {
					if(!empty($user_record)) {
						$user_record['password'] = '';
						$user_record['status'] = 0;
						// $user = User::CreateUser($user_record, $params);
						$user = [];
						$user['success'] = true;
						$user['id'] = NULL;
						if($user['success']) {
							if(array_key_exists('user_id',$data) && !empty($data['user_id'])) {
								$record['user_id'] = $data['user_id']??NULL;
							} else {
								$record['user_id'] = $user['id'];
							}
							$record['enquiry_no_id'] = CustomHelper::genrateEnquiryNoId($record['user_id']);
							
							$isSaved = Enquiry::create($record);
							if($isSaved) {
								$response['id'] = $isSaved->id;
								$response['success'] = true;
							}
						} else {
							$response['msg'] = $user['msg'];
						}
					}
				}

				if($response['success'] && $response['id']) {
					$enquiry_id = $response['id'];	

					// if(isset($data['enquiry_for'])) {
					if(array_key_exists('enquiry_for',$data)) {
						EnquiryForType::where('enquiry_id',$enquiry_id)->delete();
						if(is_array($data['enquiry_for'])) {
							$enquiry_for = $data['enquiry_for'];
						} else {
							$enquiry_for = explode(',', $data['enquiry_for']);
						}
						if(!empty($enquiry_for)) {
							$enquiry_for_arr = [];
							foreach($enquiry_for as $k=>$enquiry_for_id) {
								$enquiry_for_arr[] = [
									'enquiry_id' => $enquiry_id,
									'enquiry_for_id' => $enquiry_for_id??NULL,
									'sort_order' => $k??0
								];
							}
							if(!empty($enquiry_for_arr)) {
								EnquiryForType::insert($enquiry_for_arr);
							}
						}
					}

					// if(isset($data['destination'])) {
					if(array_key_exists('destination',$data)) {
						EnquiryDestination::where('enquiry_id',$enquiry_id)->delete();
						if(is_array($data['destination'])) {
							$destination = $data['destination'];
						} else {
							$destination = explode(',', $data['destination']);
						}
						if(!empty($destination)) {
							$destination_arr = [];
							foreach($destination as $k=>$destination_id) {
								$destination_id = $destination_id??NULL;
								if(!empty($destination_id) && is_numeric($destination_id)) {
									$destination_record = Destination::find($destination_id);
									if($destination_record) {
										$destination_arr[] = [
											'enquiry_id' => $enquiry_id,
											'destination_id' => $destination_id??NULL,
											'type' => 'destination',
											'sort_order' => $k??0
										];
									}
								}
							}
							if(!empty($destination_arr)) {
								EnquiryDestination::insert($destination_arr);
							}
						}
					}

					// if(isset($data['sub_destination'])) {
					if(array_key_exists('sub_destination',$data)) {
						if(is_array($data['sub_destination'])) {
							$sub_destination = $data['sub_destination'];
						} else {
							$sub_destination = explode(',', $data['sub_destination']);
						}
						if(!empty($sub_destination)) {
							$sub_destination_arr = [];
							foreach($sub_destination as $k=>$destination_id) {
								$destination_id = $destination_id??NULL;
								if(!empty($destination_id) && is_numeric($destination_id)) {
									$destination_record = Destination::find($destination_id);
									if($destination_record) {
										$sub_destination_arr[] = [
											'enquiry_id' => $enquiry_id,
											'destination_id' => $destination_id??NULL,
											'type' => 'sub_destination',
											'sort_order' => $k??0
										];
									}
								}
							}
							if(!empty($sub_destination_arr)) {
								EnquiryDestination::insert($sub_destination_arr);
							}
						}
					}

					// if(isset($data['accommodation'])) {
					if(array_key_exists('accommodation',$data)) {
						EnquiryAccommodation::where('enquiry_id',$enquiry_id)->delete();
						if(is_array($data['accommodation'])) {
							$accommodation = $data['accommodation'];
						} else {
							$accommodation = explode(',', $data['accommodation']);
						}
						if(!empty($accommodation)) {
							$accommodation_arr = [];
							foreach($accommodation as $k=>$accommodation_id) {
								$accommodation_id = $accommodation_id??NULL;
								if(!empty($accommodation_id) && is_numeric($accommodation_id)) {
									$accommodation_record = Accommodation::find($accommodation_id);
									if($accommodation_record) {
										$accommodation_arr[] = [
											'enquiry_id' => $enquiry_id,
											'accommodation_id' => $accommodation_id??NULL,
											'sort_order' => $k??0
										];										
									}
								}
							}
							if(!empty($accommodation_arr)) {
								EnquiryAccommodation::insert($accommodation_arr);
							}
						}
					}

					if(array_key_exists('accommodation_type',$data)) {
						EnquiryAccommodationType::where('enquiry_id',$enquiry_id)->delete();
						if(is_array($data['accommodation_type'])) {
							$accommodation_type = $data['accommodation_type'];
						} else {
							$accommodation_type = explode(',', $data['accommodation_type']);
						}
						if(!empty($accommodation_type)) {
							$accommodation_type_arr = [];
							foreach($accommodation_type as $k=>$accommodation_type_id) {
								$accommodation_type_arr[] = [
									'enquiry_id' => $enquiry_id,
									'accommodation_type_id' => $accommodation_type_id??NULL,
									'sort_order' => $k??0
								];
							}
							if(!empty($accommodation_type_arr)) {
								EnquiryAccommodationType::insert($accommodation_type_arr);
							}
						}
					}

					$record = $data;
					$record['enquiry_id'] = $response['id'];
					if(array_key_exists('interaction_content',$data)) {
						$record['interaction_content'] = $data['interaction_content'];
					} else if(array_key_exists('content',$data)){
						$record['interaction_content'] = $data['content'];
					}
					if(!(isset($record['interaction_content']) && !empty($record['interaction_content']))) {
						if($id) {
							$record['interaction_content'] = 'Enquiry updated successfully.';
						} else {
							$record['interaction_content'] = 'Enquiry created successfully.';
						}
					}
					if(isset($params['created_by'])) {
						$record['created_by'] = $params['created_by'];
						$record['created_type'] = $params['created_type']??'backend';
					} else {
						$record['created_by'] = auth()->user()->id??NULL;
						$record['created_type'] = $record['created_type']??'customer';
					}
					$interaction = EnquiryInteraction::CreateInteraction($record);
					if($interaction['success']) {
						$response['success'] = true;
						$response['msg'] = $interaction['msg'];
					} else {
						$response['success'] = false;
						$response['msg'] = $interaction['msg'];
					}
				}
			}
		} catch (\Exception $e) {
            $response['msg'] = $e->getMessage();
        }
		return $response;
	}
}