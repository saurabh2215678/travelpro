@component('admin.layouts.main')
@slot('title')
Admin - Accommodation View - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
<?php
   $name = (isset($accommodation->name))?$accommodation->name:'';
   $destination_id = (isset($accommodation->destination_id))?$accommodation->destination_id:0;
   $slug = (isset($accommodation->slug))?$accommodation->slug:0;
   $accommodation_feature = (isset($accommodation->accommodation_feature))?json_decode($accommodation->accommodation_feature):[];
   $accommodation_facility = (isset($accommodation->accommodation_facility))?json_decode($accommodation->accommodation_facility):[];
   $category_id = (isset($accommodation->category_id))?$accommodation->category_id:'';
   $star_classification = (isset($accommodation->star_classification))?$accommodation->star_classification:'';
   $related_hotels = (isset($accommodation->related_hotels))?json_decode($accommodation->related_hotels):[];
   $address = (isset($accommodation->address))?$accommodation->address:'';
   $contact_phone = (isset($accommodation->contact_phone))?$accommodation->contact_phone:'';
   $contact_email = (isset($accommodation->contact_email))?$accommodation->contact_email:'';
   $rating = (isset($accommodation->rating))?$accommodation->rating:0;
   $checkin_time = (isset($accommodation->checkin_time))?$accommodation->checkin_time:'';
   $checkout_time = (isset($accommodation->checkout_time))?$accommodation->checkout_time:'';
   $brief = (isset($accommodation->brief))?$accommodation->brief:'';
   $description = (isset($accommodation->description))?$accommodation->description:'';
   $policies = (isset($accommodation->policies))?$accommodation->policies:'';
   $latitude = (isset($accommodation->latitude))?$accommodation->latitude:'';
   $longtitude = (isset($accommodation->longtitude))?$accommodation->longtitude:'';
   $sort_order = (isset($accommodation->sort_order))?$accommodation->sort_order:'';
   $status = (isset($accommodation->status))?$accommodation->status:'';
   $featured = (isset($accommodation->featured))?$accommodation->featured:'';
   $meta_title = (isset($accommodation->meta_title))?$accommodation->meta_title:'';
   $meta_description = (isset($accommodation->meta_description))?$accommodation->meta_description:'';
   $meta_keywords = (isset($accommodation->meta_keywords))?$accommodation->meta_keywords:'';
   $image = (isset($accommodation->image))?$accommodation->image:'';
   
   $storage = Storage::disk('public');
   $path = 'accommodations/';
   
   $accommodationObj = "";
       if(!empty($accommodation_feature)){
           $accommodationObj = App\Models\AccommodationFeature::whereIn('id',$accommodation_feature)->get();
       }
   
   $accommFacObj = "";
       if(!empty($accommodation_facility)){
           $accommFacObj = App\Models\AccommodationFacility::whereIn('id',$accommodation_facility)->get();
       }
   
   $accommHotelsObj = "";
       if(!empty($related_hotels)){
           $accommHotelsObj = App\Models\Accommodation::whereIn('id',$related_hotels)->get();
       }
   $routeName = CustomHelper::getAdminRouteName();
       //$back_url=CustomHelper::BackUrl(); 
   $back_url = request()->has('back_url') ? request()->input('back_url') : '';
   $tripTimeArr = config('custom.tripTimeArr');
       
   ?>
<?php
   $active_menu = "accommodations";
   $accommodation_id = isset($accommodation->id) ? $accommodation->id:'';
   ?>
@if(!empty($accommodation))
@include('admin.includes.accommodationoptionmenu')
@endif
<div class="top_title_admin">
   <div class="title">
      <h2>{{ $page_heading }}</h2>
   </div>
   <div class="add_button">
      @if(CustomHelper::checkPermission('accommodations','edit'))
      <a href="{{ route($routeName.'.accommodations.edit', ['id'=>$accommodation->id,'back_url'=>$back_url]) }}" class="btn_admin" title="Edit Accommodations"><i class="fas fa-edit"></i>Edit Accommodations</a>
      @endif
   </div>
</div>
<div class="centersec">
   <div class="bgcolor viewsec">
      @include('snippets.errors')
      @include('snippets.flash')
      <div class="alert_msg"></div>
      <div class="table-responsive">
               <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
                  <tr>
                     <td>
                        <div><b>Name: </b></div>
                        <div>{{$accommodation->name}}</div>
                     </td>
                     <td>
                        <div><b>Place: </b></div>
                        <div>{{(isset($accommodation->accommodationDestination))?$accommodation->accommodationDestination->name:""}}</div>
                     </td>
                     <td>
                        <div><b>Slug: </b></div>
                        <div>{{$accommodation->slug}}</div>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <div><b>Accommodation Feature: </b></div>
                        <div><?php if(!empty($accommodationObj) && !($accommodationObj->isEmpty())){
                           foreach ($accommodationObj as $accommFea) {
                               $accomArr[] = $accommFea->title;
                           }
                           echo implode(', ', $accomArr);
                           }
                           ?></div>
                     </td>
                     <td>
                        <div><b>Accommodation Facilities: </b></div>
                        <div><?php if(!empty($accommFacObj) && !($accommFacObj->isEmpty())){
                           foreach ($accommFacObj as $fscility) {
                               $fscilityArr[] = $fscility->title;
                           }
                           echo implode(', ', $fscilityArr);
                           }
                           ?></div>
                     </td>
                     <td>
                        <div><b>Accommodation Category: </b></div>
                        <div>{{$accommodation->category_id}}</div>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <div><b>Rating : </b></div>
                        <div>
                           {{$accommodation->rating}}
                        </div>
                     </td>
                     <td>
                        <div><b>Contact Phone: </b></div>
                        <div> {{$accommodation->contact_phone}}</div>
                     </td>
                     <td>
                        <div><b>Contact Email: </b></div>
                        <div>{{$accommodation->contact_email}}</div>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <div><b>Check IN Time: </b></div>
                        <div>{{CustomHelper::DateFormat($checkin_time, 'h:i A')}}
                        <?php /*foreach ($tripTimeArr as $timekey => $tripTime) { 
                              if($timekey == $checkin_time){
                                 echo $tripTime; 
                              } 
                           } */
                        ?>
                        </div>
                     </td>  
                     <td>
                        <div><b>Check OUT Time: </b></div>
                        <div>{{CustomHelper::DateFormat($checkout_time, 'h:i A')}}
                        <?php /*foreach ($tripTimeArr as $timekey => $tripTime) { 
                              if($checkout_time == $timekey){
                                 echo $tripTime; 
                              } 
                           } */
                        ?>
                        </div>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <div><b>Related Hotels: </b></div>
                        <div>
                           <?php 
                              if(!empty($accommHotelsObj) && !($accommHotelsObj->isEmpty())){
                                  foreach ($accommHotelsObj as $hotel){
                              //prd($accommHotelsObj);
                                      $relatedArr[] = $hotel->name;
                                  }
                                  echo implode(', ', $relatedArr);
                              }
                              ?>
                        </div>
                     </td>
                     <td>
                        <div><b>Address: </b></div>
                        <div>
                           {{$accommodation->address}}
                        </div>
                     </td>
                     <td>
                        <div><b>Map(Embeded Code): </b></div>
                        <div>{{$accommodation->map}}</div>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <div><b>Latitude: </b></div>
                        <div>{{$accommodation->latitude}}</div>
                     </td>
                     <td>
                        <div><b>Longitude: </b></div>
                        <div>{{$accommodation->longtitude}}</div>
                     </td>
                     <td>
                        <div><b>Brief: </b></div>
                        <div>{!! $accommodation->brief !!}</div>
                     </td>
                  </tr>
                  <tr>
                     <td colspan="3">
                        <div><b>Description: </b></div>
                        <div>{!! $accommodation->description !!}</div>
                     </td>
                  </tr>
                  @if($policies)
                  <tr>
                     <td colspan="3">
                        <div><b>Policies: </b></div>
                        <div>{!! $policies !!}</div>
                     </td>
                  </tr>
                  @endif
                  <tr>
                     <td>
                        <div><b>Status: </b></div>
                        <div>{{ CustomHelper::getStatusStr($accommodation->status) }}</div>
                     </td>
                     <td>
                        <div><b>Sort Order: </b></div>
                        <div>{{$accommodation->sort_order}}</div>
                     </td>
                     <td>
                        <div><b>Featured: </b></div>
                        <div>{{ CustomHelper::getStatusStr($accommodation->featured) }}</div>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <div><b>Star Classification: </b></div>
                        <div>{{$accommodation->star_classification.' Star'}}</div>
                     </td>
                     <td>
                        <div><b>Date Created: </b></div>
                        <div>{{ CustomHelper::DateFormat($accommodation->created_at, 'd/m/Y') }}</div>
                     </td>
                     <td>
                        <div><b>Image: </b></div>
                        <div>
                           <?php
                              if(!empty($image)){
                                  if($storage->exists($path.$image)){
                              ?>
                           <div class="col-md-2 image_box">
                              <img src="{{ url('/storage/'.$path.'thumb/'.$image) }}" style="width: 100px;">
                           </div>
                           <?php } ?>
                           <?php } ?>
                        </div>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <div><b>Page Title</b></div>
                        <div>{{ $accommodation->meta_title }}</div>
                     </td>
                     <td>
                        <div><b>Page Keyword</b></div>
                        <div>{{ $accommodation->meta_keywords }}</div>
                     </td>
                  </tr>
                  <tr>
                     <td colspan="3">
                        <div><b>Page Description: </b></div>
                        <div>{!! $accommodation->meta_description !!}</div>
                     </td>
                  </tr>
               </table>
      </div>
   </div>
</div>
@slot('bottomBlock')
@endslot
@endcomponent