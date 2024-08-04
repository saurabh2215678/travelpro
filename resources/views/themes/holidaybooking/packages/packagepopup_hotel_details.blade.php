<?php 
//prd($accommodation);
if(isset($accommodation) && !empty($accommodation)){
$accoRating = 0;
$storage = Storage::disk('public');
$accommodation_path = 'accommodations/';
$accommodation_thumb_path = 'accommodations/thumb/';
$destination_id = (!empty($accommodation->accommodationDestination))? $accommodation->accommodationDestination->name : "";
$accommodation_facility = (isset($accommodation->accommodation_facility))?json_decode($accommodation->accommodation_facility):[];
$accommodation_name = isset($accommodation->name) ? $accommodation->name:'';
$accommodation_brief = isset($accommodation->brief) ? $accommodation->brief:'';
$accommodation_description = isset($accommodation->description) ? $accommodation->description:'';
$accommodation_star = isset($accommodation->star_classification) ? $accommodation->star_classification:'';
$accommodation_slug = isset($accommodation->slug) ? $accommodation->slug:'';
//$accommodation_image = isset($accommodation->image) ? $accommodation->image:'';

$accommodationImages = $accommodation->accommodationImages??[];

$accommodationSrc =asset(config('custom.assets').'/images/noimagebig.jpg');

/*if(!empty($accommodation_image)){
   if($storage->exists($accommodation_path.$accommodation_image)){
      $accommodationSrc = asset('/storage/'.$accommodation_path.$accommodation_image);
   }
}*/

$accommFacObj = "";
    if(!empty($accommodation_facility)){
        $accommFacObj = App\Models\AccommodationFacility::whereIn('id',$accommodation_facility)->get();
    }

$accoRating = isset($accommodation->star_classification) ? $accommodation->star_classification:'';
$address = isset($accommodation->address) ? $accommodation->address:'';
    $i = 1;

$accommodationRooms = $accommodation->AccommodationRooms??[]; ?>

<div class="popup_inner hotel_detail_popup">
   <div class="row">
      <div class="col-md-6">
         <div class="hote_detail_box">
            <div class="title">
              {{ $accommodation_name }}
            </div>
            <div class="star_box">
                        
                           <ul class="rating_list">
                            <?php
                            if(is_numeric($accoRating) && $accoRating > 0){
                            for ($i = 0; $i < $accoRating; $i++) { ?>
                            <li rating="{{ $accoRating }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                            </li>
                            <!-- <li>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                            </li>
                            <li>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                            </li>
                            <li>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                            </li>
                            <li>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                            </li> -->
                  <?php } }?>
                      </ul>
                     </div>
                    <p class="address_accm"><img class="map-i" src="{{asset(config('custom.assets').'/images/map.png') }}" style="width: 10px;position: relative;top: 2px;"> {{ $destination_id }}<br><br>
                        {{$address}}
                    </p>
                    <?php 
                       if(!empty($accommFacObj) && !($accommFacObj->isEmpty())){ ?>
                    <div class="facilities">
                       <div class="facility_title font-semibold"><strong>Facilities:</strong></div>
                       <span class="hotel_facilities text-base" data-value="Bed Only">
                        <!-- <img class="map-i" src="{{asset(config('custom.assets').'/images/check-mark.png') }}" style="display: block;padding-bottom: 10px;"> -->
                        <?php 
                       if(!empty($accommFacObj) && !($accommFacObj->isEmpty())){
                                foreach ($accommFacObj as $fscility) {
                                    $fscilityArr[] = $fscility->title;
                                }
                                echo implode(', ', $fscilityArr);
                            }
                            ?> 
                        </span>
                    </div>
                <?php } ?> 
            
            <div class="hote_description table-responsive">
                {!! $accommodation_description !!}
            </div>
            
            <?php if(!empty($accommodationRooms) && !($accommodationRooms->isEmpty())){ ?>
        <div class="room_type">
            <ul>
                <?php foreach($accommodationRooms as $row){ 
                    $room_type_name = $row->room_type_name??'';
                    $brief = $row->brief??'';
                    ?>
                    <li>
                       <div class="room_list">
                          <h5>{{$room_type_name}}</h5>
                      </div>
                      <div class="room_brif">
                       {{$brief}}
                   </div>
               </li>
           <?php } ?>
       </ul>
    </div>
    <?php } ?>
         </div>
      </div>

      @if(!empty($accommodationImages) && count($accommodationImages)>0)
          <div id="dest_gallery" class="tabcontent col-md-6">
            <!-- <div class="flex flex-wrap">
               @foreach($accommodationImages as $image)
               <a href="{{asset('/storage/'.$accommodation_path.$image->name);}}"><img src="{{asset('/storage/'.$accommodation_thumb_path.$image->name)}}" alt="{{$image->title ?? ''}}"></a>
               @endforeach
            </div> -->
            <div class="swiper hotelimgSwiper">
                <div class="swiper-wrapper">
                @foreach($accommodationImages as $image)
               <a class="swiper-slide" href="{{asset('/storage/'.$accommodation_path.$image->name);}}"><img src="{{asset('/storage/'.$accommodation_path.$image->name)}}" alt="{{$image->title ?? ''}}"></a>
               @endforeach
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                
            </div>
          </div>
          @endif
      <?php /*<div class="col-md-6">
         <div class="popup_slider">
            <img class="img-responsive" src="{{ $accommodationSrc }}">
         </div> 
      </div> */ ?>
   </div>
</div>
<?php }?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style type="text/css">
body {
    font-family: 'SF-Pro-Display', sans-serif;
    padding-top: var(--header-height);
    color: var(--text-color);
    margin: 0;
    overflow-x: hidden;
    height: 100%;
}
.img-responsive {
    display: block;
    max-width: 100%;
    height: auto;
    width: 100%;
}
.row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: nowrap;
    flex-direction: row;
    align-content: start;
}
.col-md-6 {
    width: 50%;
    position: relative;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
    overflow: hidden;
}
/* .hote_detail_box {
    height: 340px;
    overflow: auto;
} */
.title {
    display: inline-block;
    font-size: 20px;
    color: #3e3e3e;
    font-weight: 700;
    margin-bottom: 0;
    padding-right: 10px;
}
.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}
table.table thead th {
    border-top: 0;
    font-size: 16px;
    font-weight: 700;
}
table.table th, table.table td {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
}
.table th {
    color: #fff;
    background-color: #FF6600;
    border-color: #32383e;
}
table th {
    font-size: .9rem;
    font-weight: 400;
}
thead.red_bg tr th {
    font-size: 20px;
    padding: 0.75rem 1rem;
}
.table-bordered td, .table-bordered th {
    border: 1px solid #dee2e6;
    border-top: 0;
    border-left: 0;
}
tbody tr td:nth-child(1) {
    border-left: 1px solid #dee2e6;
}
.table-bordered td{
	padding:10px;
}
.rating_list {
    display: flex;
    list-style: none;
    /* margin: 0; */
    padding: 0;
}
/* .rating_list li:not(.active) svg {
    opacity: 0.4;
} */
.rating_list svg {
    height: 12px;
    fill: #01b3a7;
}
.hotel_facilities {
    display: inline-block;
    font-size: 11px;
    margin-right: 20px;
}
.facility_title {
    font-weight: 500;
    color: #000;
    margin-bottom: 10px;
}
.facilities {
    padding-bottom: 15px;
}
div#dest_gallery .flex {
    display: grid;
    grid-template-columns: auto auto;
    gap: 15px;
}
div#dest_gallery a {
    pointer-events: none;
}
.hotel_detail_popup .hotel_facilities{
    font-size:16px;
}
.hotelimgSwiper.swiper {
      width: 100%;
      height: 100%;
}
.hotelimgSwiper .swiper-slide {
      text-align: center;
      font-size: 18px;
      background: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 25rem;
}
.hotelimgSwiper .swiper-slide img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;
}
.swiper-button-next:after, .swiper-button-prev:after {
    color: #ff6600;
    font-size: 1rem;
    font-weight: 600;
}
.hotelimgSwiper .swiper-button-next, .hotelimgSwiper .swiper-button-prev {
    background: #fff;
    height: 35px;
    width: 35px;
    border-radius: 50%;
}
.room_type ul li {
    list-style: none;
    background: #9e9e9e2e;
    padding: 10px 20px;
    margin-bottom: 10px;
    border-bottom: 2px solid #f47200;
}
.room_type ul {
    padding: 0;
}
.room_type ul li h5 {
    background: #f47200;
    display: inline-block;
    padding: 7px 10px;
    border-radius: 3px;
    color: #fff;
    margin: 8px 0;
}
.room_type .room_brif {font-size: 15px;line-height: 22px;}
.hote_description {
    line-height: 1.5;
    font-size: 14px;
}




@media (min-width: 992px){
.row-cols-2{
    flex:0 0 auto;
    width:50%
}
.w-30{
    flex:0 0 auto;
    width:30%
}
.w-70{
    flex:0 0 auto;
    width:70%
}
.col-md-6 {
    width: 50%;
    position: relative;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
}
.col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9 {
    float: left;
}
}

@media (max-width: 767px){
.row {
    display: block;
}
.col-md-6 {
    width: 100%;
}

}
</style>
<script type="text/javascript">
   $('.rating_list').each(function(){
      var ratingValue = parseInt($(this).attr('rating'))
      $(this).children('li').each(function(i){
         if(i < ratingValue){
            $(this).addClass('active');
         }
      })
   });
</script>
<script>
    var swiper = new Swiper(".hotelimgSwiper", {
      pagination: {
        el: ".swiper-pagination",
        //type: "progressbar",
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
  </script>