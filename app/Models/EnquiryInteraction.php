<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\EnquiryInteractionForType;
use App\Models\EnquiryInteractionDestination;
use App\Helpers\CustomHelper;

class EnquiryInteraction extends Model {

	protected $table = 'enquiries_interactions';
	protected $guarded = ['id'];
	protected $fillable = [];

	public function Enquiry() {
		return $this->belongsTo('App\Models\Enquiry', 'enquiry_id');
	}

	public function GetColor() {
		return $this->belongsTo('App\Models\EnquiriesMaster', 'rating');
	}

	public static function CreateInteraction($data) {
		$response = [];
		$response['success'] = false;
		$response['msg'] = '';
		if(isset($data['enquiry_id']) && !empty($data['enquiry_id'])) {
			try {
				$record = [];
				$record['enquiry_id'] = $data['enquiry_id'];
				/*if(isset($data['type'])) {
					$record['type'] = $data['type'];
				}*/
				if(array_key_exists('cc_id',$data)) {
					$record['cc_id'] = $data['cc_id'];
				}
				// if(isset($data['interaction_content'])) {
				if(array_key_exists('interaction_content',$data)) {
					$record['comment'] = $data['interaction_content'];
				}
				/*if(isset($data['destination'])) {
					$record['destination'] = $data['destination'];
				}
				if(isset($data['sub_destination'])) {
					$record['sub_destination'] = $data['sub_destination'];
				}*/
				// if(isset($data['package'])) {
				if(array_key_exists('package',$data)) {
					$record['package'] = $data['package'];
				}
				/*if(isset($data['accommodation'])) {
					$record['accommodation'] = $data['accommodation'];
				}*/
				// if(isset($data['activity'])) {
				if(array_key_exists('activity',$data)) {
					$record['activity'] = $data['activity'];
				}
				// if(isset($data['lead_source'])) {
				if(array_key_exists('lead_source',$data)) {
					$record['lead_source'] = $data['lead_source'];
				}
				// if(isset($data['lead_status'])) {
				if(array_key_exists('lead_status',$data)) {
					$record['lead_status'] = $data['lead_status'];
				}
				// if(isset($data['lead_sub_status'])) {
				if(array_key_exists('lead_sub_status',$data)) {
					$record['lead_sub_status'] = $data['lead_sub_status'];
				}
				// if(isset($data['rating'])) {
				if(array_key_exists('rating',$data)) {
					$record['rating'] = $data['rating'];
				}
				// if(isset($data['followup_date'])) {
				if(array_key_exists('followup_date',$data)) {
					$record['followup_date'] = (!empty($data['followup_date']))?$data['followup_date']:NULL;
				}
				// if(isset($data['created_by'])) {
				if(array_key_exists('created_by',$data)) {
					$record['created_by'] = $data['created_by'];
					$record['created_type'] = $data['created_type']??'backend';
				} else {
					$record['created_by'] = auth()->user()->id??'';
					$record['created_type'] = 'backend';
				}
				$isSaved = EnquiryInteraction::create($record);
				if($isSaved) {
					$enquiry_interaction_id = $isSaved->id;
					
					// if(isset($data['enquiry_for'])) {
					if(array_key_exists('enquiry_for',$data)) {
						EnquiryInteractionForType::where('enquiry_interaction_id',$enquiry_interaction_id)->delete();
						if(is_array($data['enquiry_for'])) {
							$enquiry_for = $data['enquiry_for'];
						} else {
							$enquiry_for = explode(',', $data['enquiry_for']);
						}
						if(!empty($enquiry_for)) {
							$enquiry_for_arr = [];
							foreach($enquiry_for as $k=>$enquiry_for_id) {
								$enquiry_for_arr[] = [
									'enquiry_interaction_id' => $enquiry_interaction_id,
									'enquiry_for_id' => $enquiry_for_id??NULL,
									'sort_order' => $k??0
								];
							}
							if(!empty($enquiry_for_arr)) {
								EnquiryInteractionForType::insert($enquiry_for_arr);
							}
						}
					}
					
					// if(isset($data['destination'])) {
					if(array_key_exists('destination',$data)) {
						EnquiryInteractionDestination::where('enquiry_interaction_id',$enquiry_interaction_id)->delete();
						if(is_array($data['destination'])) {
							$destination = $data['destination'];
						} else {
							$destination = explode(',', $data['destination']);
						}
						if(!empty($destination)) {
							$destination_arr = [];
							foreach($destination as $k=>$destination_id) {
								$destination_arr[] = [
									'enquiry_interaction_id' => $enquiry_interaction_id,
									'destination_id' => $destination_id??NULL,
									'type' => 'destination',
									'sort_order' => $k??0
								];
							}
							if(!empty($destination_arr)) {
								EnquiryInteractionDestination::insert($destination_arr);
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
								$sub_destination_arr[] = [
									'enquiry_interaction_id' => $enquiry_interaction_id,
									'destination_id' => $destination_id??NULL,
									'type' => 'sub_destination',
									'sort_order' => $k??0
								];
							}
							if(!empty($sub_destination_arr)) {
								EnquiryInteractionDestination::insert($sub_destination_arr);
							}
						}
					}

					// if(isset($data['accommodation'])) {
					if(array_key_exists('accommodation',$data)) {
						if(is_array($data['accommodation'])) {
							$accommodation = $data['accommodation'];
						} else {
							$accommodation = explode(',', $data['accommodation']);
						}
						if(!empty($accommodation)) {
							$accommodation_arr = [];
							foreach($accommodation as $k=>$accommodation_id) {
								$accommodation_arr[] = [
									'enquiry_interaction_id' => $enquiry_interaction_id,
									'accommodation_id' => $accommodation_id??NULL,
									'sort_order' => $k??0
								];
							}
							if(!empty($accommodation_arr)) {
								EnquiryInteractionAccommodation::insert($accommodation_arr);
							}
						}
					}

					$response['id'] = $enquiry_interaction_id;
					$response['success'] = true;
				}
			} catch (\Exception $e) {
	            $response['msg'] = $e->getMessage();
	        }
		}
		return $response;
	}
}