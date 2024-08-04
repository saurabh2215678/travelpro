<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\TeamManagement;
use App\Helpers\CustomHelper;

class TeamController extends Controller
{

    private $limit;
    public function __construct(){
        $this->limit = 10;
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
        }
        $data['banner_image'] = $banner_image;

        $limit = $this->limit;
        $id = (isset($request->id))?$request->id:'';
        $teamManagers = TeamManagement::where('status', 1)->whereJsonContains('category',["1"])->orderBy('sort_order','asc')->get();

        $teamGuides = TeamManagement::where('status', 1)->whereJsonContains('category',["2"])->orderBy('sort_order','asc')->get();

        $team = TeamManagement::where('status', 1)->orderBy('sort_order','asc')->get();
        $data['teamManagers'] = $teamManagers;
        $data['teamGuides'] = $teamGuides;
        $data['teams'] = $team;
        return view(config('custom.theme').'.teams.index', $data);
    }

    public function details(Request $request){

        $data = [];

        $slug = (isset($request->slug))?$request->slug:'';
        if(isset($slug) && !empty($slug)){
            $teamDetails = TeamManagement::where('slug',$slug)->where('status',1)->first();
            if(isset($teamDetails) && !empty($teamDetails)){
               $seo_tags = CustomHelper::getSeoModules('team_listing');
               $data['breadcrumb_title'] = $seo_tags->page_url_label??'Team';
                $data['page_title'] = isset($teamDetails->meta_title) ? $teamDetails->meta_title:$teamDetails->title;
                $data['page_heading'] = $teamDetails->title;

                $data['teamDetails'] = $teamDetails;

                return view(config('custom.theme').'.teams.details', $data);
            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }

}
