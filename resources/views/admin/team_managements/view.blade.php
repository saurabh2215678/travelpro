<style>
    .centersec.fancybox-content {
        width: 900px;
        height: 600px;
    }
</style>
<?php
$gender = (isset($teamManagenent->gender))?$teamManagenent->gender:'';
$title = (isset($teamManagenent->title))?$teamManagenent->title:'';
$sub_title = (isset($teamManagenent->sub_title))?$teamManagenent->sub_title:'';
$slug = (isset($teamManagenent->slug))?$teamManagenent->slug:'';
$designation = (isset($teamManagenent->designation))?$teamManagenent->designation:'';
$category = (isset($teamManagenent->category))?json_decode($teamManagenent->category,true):[];
$trip_theme = (isset($teamManagenent->trip_theme))?json_decode($teamManagenent->trip_theme,true):[];
$email = (isset($teamManagenent->email))?$teamManagenent->email:'';
$alt_email = (isset($teamManagenent->alt_email))?$teamManagenent->alt_email:'';
$mobile_no = (isset($teamManagenent->mobile_no))?$teamManagenent->mobile_no:'';
$brief_profile = (isset($teamManagenent->brief_profile))?$teamManagenent->brief_profile:'';
$detail_profile = (isset($teamManagenent->detail_profile))?$teamManagenent->detail_profile:'';
$facebook_link = (isset($teamManagenent->facebook_link))?$teamManagenent->facebook_link:'';
$twitter_link = (isset($teamManagenent->twitter_link))?$teamManagenent->twitter_link:'';
$sort_order = (isset($teamManagenent->sort_order))?$teamManagenent->sort_order:0;
$featured = (isset($teamManagenent->featured))?$teamManagenent->featured:'';
$status = (isset($teamManagenent->status))?$teamManagenent->status:'';
$image = (isset($teamManagenent->image))?$teamManagenent->image:'';

$storage = Storage::disk('public');
$path = 'teammanagements/';

$categoryObj = "";
if(!empty($category)){
    $categoryObj = App\Models\TeamCategory::whereIn('id',$category)->get();
}
$themObj = "";
if(!empty($trip_theme)){
    $themObj = App\Models\ThemeCategories::whereIn('id',$trip_theme)->get();
}
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

if(empty($back_url)){
    $back_url = $routeName.'/teammanagements';
}
?>
<div class="centersec">
    <div class="bgcolor viewsec">
        <div class="ajax_msg"></div>
        <table border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec" class="table-responsive">
            <tr>
                <td width="806" valign="top" class="innersec">
                    <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
                      <tr>
                        <td>
                            <div><b>Gender: </b></div>
                            <div>
                                <?php if($gender == 1){
                                    echo "Male";
                                }elseif($gender == 2){
                                    echo "Female";
                                }else{
                                    echo "others";
                                }?>
                            </div>
                        </td>
                        @if(!empty($title))
                        <td>
                            <div><b>Name: </b></div>
                            <div>{{$title}}</div>
                        </td>
                        @endif

                        @if(!empty($sub_title))
                        <td>
                            <div><b>Title: </b></div>
                            <div>{{$sub_title}}</div>
                        </td>
                        @endif
                    </tr>

                    <tr>
                        <td>
                            <div><b>Slug: </b></div>
                            <div>{{ $slug }}</div>
                        </td>
                        <td>
                            <div><b>Designation: </b></div>
                            <div>{{$designation}}</div>
                        </td>
                        <td>
                            <div><b>Brief Profile: </b></div>
                            <div>{{$brief_profile}}</div>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3">
                            <div><b>Deatil Profile: </b></div>
                            <div>{!!$detail_profile!!}</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div><b>Category: </b></div>
                            <div><?php 
                            if(!empty($categoryObj) && !($categoryObj->isEmpty())){
                                foreach ($categoryObj as $category) {
                                    $categoryArr[] = $category->title;
                                }
                                echo implode(', ', $categoryArr);
                            }
                            ?>
                        </div>
                    </td>
                    <td>
                        <div><b>Tour Trip/Theme: </b></div>
                        <div><?php 
                        if(!empty($themObj) && !($themObj->isEmpty())){
                            foreach ($themObj as $theme) {
                                $themeArr[] = $theme->title;
                            }
                            echo implode(', ', $themeArr);
                        }
                        ?>
                    </div>
                </td>
                <td>
                    <div><b>Email: </b></div>
                    <div>{{$email}}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><b>Alternative Email: </b></div>
                    <div> {{$alt_email}}</div>
                </td>
                <td>
                    <div><b>Contact Phone/Mobile No: </b></div>
                    <div>{{$mobile_no}}</div>
                </td>

                <td>
                    <div><b>Facebook Link: </b></div>
                    <div>{{$facebook_link}}</div>
                </td>
                
            </tr>
            <tr>
                <td>
                    <div><b>Featured: </b></div>
                    <div>{{ CustomHelper::getStatusStr($featured) }}</div>
                </td>
                <td>
                    <div><b>Status: </b></div>
                    <div>{{ CustomHelper::getStatusStr($status) }}</div>
                </td>

                <td>
                    <div><b>Sort Order: </b></div>
                    <div>{{$sort_order}}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><b>Image: </b></div>
                    <div><?php
                    if(!empty($image)){
                        if($storage->exists($path.$image)){ ?>
                            <div class="col-md-2 image_box">
                               <img src="{{ url('/storage/'.$path.'thumb/'.$image) }}" style="width: 100px;">
                           </div>
                            <?php 
                        } 
                    }
                    ?>
               </div>
           </td>
           <td>
            <div><b>Date Created: </b></div>
            <div>{{ CustomHelper::DateFormat($teamManagenent->created_at, 'd/m/Y') }}</div>
        </td>
        <td colspan="3">
            <div><b>Twitter Link: </b></div>
            <div>{{$twitter_link}}</div>
        </td>
    </tr>
</table>
</div>
</div>