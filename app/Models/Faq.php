<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Faq extends Model{
    
    protected $table = 'faqs';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'question',
        'tbl_id',
        'tbl_category',
        'tbl_name',
        'category',
        'destination_id',
        'sub_destination_id',
        'answer',
        'sort_order',
        'status'
    ];

    public function faqDestination(){
        return $this->belongsTo('App\Models\Destination', 'sub_destination_id');
    }
    /**
     * @return BelongsTo
     */
    public function faqParentDestination(): BelongsTo
    {
        return $this->belongsTo('App\Models\Destination', 'destination_id');
    }

    public static function faqDelete($id){
        try {
            $data = Faq::where('id', $id)->first();
            $name = $data->question;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . 'FAQ has been deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'FAQ Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This FAQ is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }

    }

}