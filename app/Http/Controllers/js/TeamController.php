<?php
namespace App\Http\Controllers\js;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Models\TeamManagement;
use App\Helpers\CustomHelper;

use Inertia\Inertia;
use Storage;



class TeamController extends Controller
{

    private $limit;
    private $theme;
    public function __construct(){
        $this->limit = 10;
        $this->theme = config('custom.themejs');
    }

    public function index(Request $request) {
        $data = [];

        $seo_tags = CustomHelper::getSeoModules('team_listing');
        $data['page_title'] = (!empty($seo_tags->page_title))?$seo_tags->page_title:'';
        $data['page_url_label'] = $seo_tags->page_url_label??'Team';
        $data['page_brief'] = $seo_tags->page_brief??'';
        $data['page_description'] = $seo_tags->page_description??'';
        $data['meta_title'] = (!empty($seo_tags->meta_title))?$seo_tags->meta_title:$seo_tags->page_title;
        $data['meta_description'] = $seo_tags->meta_description??'';
        $data['meta_keyword'] = $seo_tags->meta_keyword??'';
        $data['other_meta_tag'] = $seo_tags->other_meta_tag??'';
        $banner_image = '';
        if($seo_tags->image) {
            $banner_image = url('/storage/seo_tags/'.$seo_tags->image);
        } else {
            $banner_image = asset(config('custom.assets').'/images/default_common_banner.jpg');
        }
        $data['banner_image'] = $banner_image;
        // prd($banner_image);

        $limit = $this->limit;
        $id = (isset($request->id))?$request->id:'';
        $teamManagers = TeamManagement::where('status', 1)->whereJsonContains('category',["1"])->orderBy('sort_order','asc')->get();

        $teamGuides = TeamManagement::where('status', 1)->whereJsonContains('category',["2"])->orderBy('sort_order','asc')->get();

        $team = TeamManagement::where('status', 1)->orderBy('sort_order','asc')->get();
        $data['teamManagers'] = $teamManagers;
        $data['teamGuides'] = $teamGuides;
        $data['teams'] = $team;

        if($team){
        $teamData = [];
        $data = [];
        $seo_data = [];
        $limit = $this->limit;

        $meta_title = '';
        $meta_description = '';
        $meta_keyword = '';
        $breadcrumb_title = '';

        $seo_tags = CustomHelper::getSeoModules('team_listing');
        $banner_image = '';
        $identifier = '';
        if(!empty($seo_tags)) {
            $identifier = $seo_tags->identifier;
            $seo_data['page_title'] = (!empty($seo_tags->page_title))?$seo_tags->page_title:'';
            $seo_data['page_url_label'] = $seo_tags->page_url_label??'';
            $seo_data['page_brief'] = $seo_tags->page_brief??'';
            $seo_data['page_description'] = $seo_tags->page_description??'';
            $seo_data['page_url'] = $seo_tags->page_url??'';
            $seo_data['page_detail_url'] = $seo_tags->page_detail_url??'';
            $seo_data['meta_title'] = (!empty($seo_tags->meta_title))?$seo_tags->meta_title:$seo_tags->page_title;
            $seo_data['meta_description'] = $seo_tags->meta_description??'';
            $seo_data['meta_keyword'] = $seo_tags->meta_keyword??'';
            $seo_data['other_meta_tag'] = $seo_tags->other_meta_tag??'';
            
            if($seo_tags->image) {
                $banner_image = url('/storage/seo_tags/'.$seo_tags->image);
            } else {
                $banner_image = asset(config('custom.assets').'/images/default_common_banner.jpg');
            }
            $breadcrumb_title = $seo_tags->page_url_label??'Team Listing';
            $meta_title = $seo_data['meta_title'];
            $meta_description = $seo_data['meta_description'];
            $meta_keyword = $seo_data['meta_keyword'];            
        }
        $seo_data['banner_image'] = $banner_image;
        // prd($banner_image);
        foreach ($team as $teamLists) {
            $storage = Storage::disk('public');
            $manager_path = "teammanagements/";
            $manager_thumb_path = "teammanagements/thumb/";

            $managerSrc =asset(config('custom.assets').'/images/noimage.jpg');

            $image = $teamLists->image;

            if(!empty($image) && $storage->exists($manager_path.$image))
            {
               $managerSrc = asset('/storage/'.$manager_path.$image);
            }

            $data['title'] = CustomHelper::wordsLimit($teamLists->title);
            $data['sub_title'] = CustomHelper::wordsLimit($teamLists->sub_title);
            $data['designation'] = CustomHelper::wordsLimit($teamLists->designation);
            $data['detail_profile'] = CustomHelper::wordsLimit($teamLists->detail_profile);
            $data['managerSrc'] = $managerSrc;

            $teamData[] = [
                'title' => $teamLists->title,
                'sub_title' => $teamLists->sub_title,
                'designation' => $teamLists->designation,
                //'detail_profile' => CustomHelper::wordsLimit($teamLists->detail_profile),
                'detail_profile' => $teamLists->detail_profile??'',
                'managerSrc' => $managerSrc,
            ];
        }
    }

        $data['team'] = $teamData;

        $breadcrumbData = [];
        $breadcrumbData[] = ['url'=>'/','label'=>'Home'];
            $breadcrumbData[] = ['url'=>route('teamListing'),'label'=>$breadcrumb_title];
            
        $data['breadcrumbData'] = $breadcrumbData;
        $data['seo_data'] = $seo_data;
        View::share('seo_data', $seo_data);
        //prd($teamData);
        return Inertia::render('teams/index', $data);
        //return view(config('custom.theme').'.teams.index', $data);
    }

    // public function details(Request $request){

    //     $data = [];

    //     $slug = (isset($request->slug))?$request->slug:'';
    //     if(isset($slug) && !empty($slug)){
    //         $teamDetails = TeamManagement::where('slug',$slug)->where('status',1)->first();
    //         if(isset($teamDetails) && !empty($teamDetails)){
    //            $seo_tags = CustomHelper::getSeoModules('team_listing');
    //            $data['breadcrumb_title'] = $seo_tags->page_url_label??'Team';
    //             $data['page_title'] = isset($teamDetails->meta_title) ? $teamDetails->meta_title:$teamDetails->title;
    //             $data['page_heading'] = $teamDetails->title;

    //             $data['teamDetails'] = $teamDetails;

    //             return view(config('custom.theme').'.teams.details', $data);
    //         }else{
    //             abort(404);
    //         }
    //     }else{
    //         abort(404);
    //     }
    // }

}
