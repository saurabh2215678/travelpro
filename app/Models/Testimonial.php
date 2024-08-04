<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\CustomHelper;
use Storage;

class Testimonial extends Model {
    protected $table = 'testimonials';
    protected $guarded = ['id'];    
    protected $fillable = [];

    public function testimonialImages() {
        return $this->hasMany('App\Models\CommonImage', 'tbl_id')->where('tbl_name','testimonials')->where('category','gallery')->orderBy('is_default','DESC')->orderBy('sort_order','ASC')->orderBy('created_at','DESC');
    }


    public static function parseTestimonial($testimonial,$params=[]) {
        if($testimonial && $testimonial->id) {
            $size = $params['size']??'box';
            $search_data = $params['search_data']??[];

            $storage = Storage::disk('public');
            $path = 'testimonials/';
            $thumbPath = 'testimonials/thumb/';
            $testimonialThumbSrc = asset(config('custom.assets').'/images/testimonial-main-default.png');
            $testimonialSrc = asset(config('custom.assets').'/images/testimonial-main-default.png');

            $tImage = $testimonial->image;
            if(!empty($tImage)){
                if($storage->exists($path.$tImage)){
                    $testimonialSrc = asset('/storage/'.$path.$tImage);
                }
                if($storage->exists($thumbPath.$tImage)){
                    $testimonialThumbSrc = asset('/storage/'.$thumbPath.$tImage);
                }
            }

            $testimonialImages = $testimonial->testimonialImages??[];
            $images = [];
            if($testimonialImages && count($testimonialImages) > 0) {
                foreach($testimonialImages as $image) {
                    $src = $testimonialThumbSrc;
                    if($storage->exists($thumbPath.$image->name)) {
                        $src = asset('/storage/'.$thumbPath.$image->name);
                    }
                    $images[] = [
                        'alt' => $image->title,
                        'src' => $src,
                    ];
                }
            } else {
                $images[] = [
                    'alt' => $testimonial->title,
                    'src' => $testimonialThumbSrc,
                ];
            }
            if(!empty($testimonial->slug)){
                $slug = $testimonial->slug??'';
            }
            $destinationIds = isset($testimonial->destination_id) ? json_decode($testimonial->destination_id, true) : [];
            $destinationArr = [];
            if(!empty($destinationIds)) {
                $destinationObj = Destination::whereIn('id', $destinationIds)->get();
                $destinationArr = $destinationObj->pluck('name')->toArray();
                $destinationArr = implode(',', $destinationArr);
            }
            $testimonial = [
                'name' => $testimonial->name??'',
                'title' => CustomHelper::wordsLimit($testimonial->title),
                'description' => CustomHelper::wordsLimit($testimonial->description),
                'destination' => $destinationArr,
                'testimonialSrc' => $testimonialSrc,
                'thumbSrc' => $testimonialThumbSrc,
                'images' => $images,
                'url' => route('testimonialDetails',['slug'=>$slug]) ,
            ];
        
            if($size == 'listing' || $size == 'detail') {

            }
            if($size == 'detail') {

            }
            return $testimonial;
        }
    }

    
}