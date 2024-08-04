<link rel="stylesheet" href="{{ asset(config('custom.assets').'/css/website.css') }}">
<?php 
if(isset($accommodation) && !empty($accommodation) ){
$accoRating = 0;
$storage = Storage::disk('public');
$accommodation_path = 'accommodations/';
$destination_id = (!empty($accommodation->accommodationDestination))? $accommodation->accommodationDestination->name : "";
$accommodation_facility = (isset($accommodation->accommodation_facility))?json_decode($accommodation->accommodation_facility):[];
$accommodation_name = isset($accommodation->name) ? $accommodation->name:'';
$accommodation_brief = isset($accommodation->brief) ? $accommodation->brief:'';
$accommodation_description = isset($accommodation->description) ? $accommodation->description:'';
$accommodation_star = isset($accommodation->star_classification) ? $accommodation->star_classification:'';
$accommodation_slug = isset($accommodation->slug) ? $accommodation->slug:'';
$accommodation_image = isset($accommodation->image) ? $accommodation->image:'';

$accommodationSrc =asset(config('custom.assets').'/images/noimagebig.jpg');

if(!empty($accommodation_image)){
   if($storage->exists($accommodation_path.$accommodation_image)){
      $accommodationSrc = asset('/storage/'.$accommodation_path.$accommodation_image);
   }
}

$accoRating = isset($accommodation->star_classification) ? $accommodation->star_classification:'';
$address = isset($accommodation->address) ? $accommodation->address:'';
    $i = 1;
$accommodation_features_arr = isset($accommodation->accommodation_feature) ? json_decode($accommodation->accommodation_feature): [];
$accommodation_facilities_arr = isset($accommodation->accommodation_facility)?json_decode($accommodation->accommodation_facility):[];
?>
<style type="text/css">
.rating_list li:not(.active) svg {
    opacity: 1;
}
.hotel-facilities ul li svg {
    display: inline-block;
}
</style>
<div class="popup_inner hotel_detail_popup">
   <div class="row">
      <div class="col-md-6">
         <div class="hote_detail_box">
            <div class="title">
              {{ $accommodation_name }}
            </div>
            <div class="star-loc mt-1">
                <ul class="rating_list">
                 <?php
                    if(is_numeric($accoRating) && $accoRating > 0){
                    for ($i = 0; $i < $accoRating; $i++) { ?>
                    <li rating="{{ $accoRating }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                    </li>
                  <?php } }?>
                      </ul>
                     </div>
                    <div class="address_accm mt-2">
                        <?php
                       $name_arr = [];
                       $place_name = $accommodation->AccommodationDefaultLocation->DestinationLocation->name??'';
                       if($place_name) {
                          $name_arr[] = $place_name;
                       }
                       $distination_name = $accommodation->accommodationDestination->name??'';
                       if($distination_name) {
                          $name_arr[] = $distination_name;
                       }
                       if(!empty($name_arr)) {
                       ?>
                      <p class="text-xs flex"><img class="map-i" src="{{asset(config('custom.assets').'/images/map.png') }}"> {{implode(', ',$name_arr)}}</p>
                      <?php } ?>

                      <p class="text-xs mt-2">{{$address}}</p>
                    </div>
                    
                    <?php if(!empty($accommodation_features_arr)){ ?>
                    <div class="hotel-facilities text-sm mt-2">
                        <div class="facility_title">Highlights : </div>
                        <ul class="flex">
                            <?php foreach($accommodation_features_arr as $feature_id){ 

                                if(CustomHelper::showAccommodationFeature($feature_id,'icon')){?>
                                <li>
                                    <img src="{{CustomHelper::showAccommodationFeature($feature_id,'icon')}}" >
                                    <span>{{CustomHelper::showAccommodationFeature($feature_id)}}</span>
                                </li>
                            <?php } }  ?>
                        </ul>
                    </div>
                    <?php } ?>

                    <?php if(!empty($accommodation_facilities_arr)){ ?>
                    <div class="hotel-facilities text-sm mt-2">
                        <div class="facilities">
                            <div class="facility_title">Facilities : </div>
                            <ul class="flex">
                                <?php foreach($accommodation_facilities_arr as $facility_id){ ?>
                                <li><svg width="1em" height="1em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="SvgIconstyled__SvgIconStyled-sc-1i6f60b-0 RBeKP"><path d="M21.453 4.487l1.094 1.026a.5.5 0 0 1 .023.707L10.412 19.188a1.25 1.25 0 0 1-1.692.122l-.104-.093-7.146-7.146a.5.5 0 0 1 0-.707l1.06-1.061a.5.5 0 0 1 .707 0l6.234 6.234L20.746 4.51a.5.5 0 0 1 .707-.023z"></path></svg> {{CustomHelper::showAccommodationFacility($facility_id)}}</li>
                            <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <?php } ?>
            <div class="hote_description table-responsive">
                {!! $accommodation_description !!}
            </div>
         </div>
      </div>
      <div class="col-md-6">
         <div class="popup_slider">
            <img class="img-responsive" src="{{ $accommodationSrc }}">
         </div>
      </div>
   </div>
</div>
<?php }?>
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
.hote_detail_box {
    height: 340px;
    overflow: auto;
}
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
    padding-top: 1.1rem;
    padding-bottom: 1rem;
}
.table th {
    color: #fff;
    background-color: #01b3a7;
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
.popup_inner.hotel_detail_popup .hote_description table {
  font-size: 14px;
}
.popup_inner.hotel_detail_popup .hote_description thead th, .popup_inner.hotel_detail_popup .hote_description table tr td {
  font-size: 14px;
  padding: 0.5rem 0.5rem;
}
</style>
