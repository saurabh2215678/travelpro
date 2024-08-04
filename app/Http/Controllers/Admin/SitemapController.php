<?php

namespace App\Http\Controllers\Admin;

// use App\Models\Sitemap;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Sitemap\SitemapGenerator;
use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Helpers\CustomHelper;
use App\Models\CmsPages;
use App\Models\Accommodation;
use App\Models\Package;
use App\Models\ThemeCategories;
use App\Models\Destination;
use App\Models\Blog;
use App\Models\SitemapUpdate;
use App\Models\SeoMetaTag;
use App\Models\MenuItem;

use Validator;
use Storage;
use Image;
use File;

class SitemapController extends Controller{

    public function __construct() {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 1000);
        //$this->limit = 20;
    }

    public function index(){
        $data = [];
        $page_heading = 'Manage Sitemap Generate';

        $data['page_heading'] = $page_heading;
        $data['sitemap_data'] = SitemapUpdate::get();

        return view('admin.sitemaps.index',$data);
    }

/*    public function home(){

        $WEBSITE_URL = url('/'); 

        SitemapGenerator::create($WEBSITE_URL)
            ->hasCrawled(function (Url $url) {
                if ($url->segment(1) === 'storage') {
                    return;
                }
                return $url;
            })
            ->writeToFile(public_path('sitemap_home.xml'));

        $msg = "Home Sitemap Generate successfully";
        return redirect(url('admin/settings/sitemap'))->with('alert-success', $msg);
    
    }
*/

      public function home(){

            $sitemap = Sitemap::create();
            //**Home**//
            $sitemap->add(Url::create('/')->setPriority(1.0)); 

            if(CustomHelper::isAllowedModule('flight')){
              $sitemap->add(Url::create('/flight')->setPriority(0.8));
            }

            if(CustomHelper::isAllowedModule('cab')){
              $sitemap->add(Url::create('/cab')->setPriority(0.8));
            }
           
            //package

            $added_url = [];

            $seo_tags = SeoMetaTag::where(['identifier'=>'package_listing','status'=>1])->first();
            $package_url =  $seo_tags->page_url? '/'.$seo_tags->page_url:'/search-packages';
            if(CustomHelper::isAllowedModule('package_listing')){
                 $sitemap->add(Url::create($package_url)->setPriority(0.8));
            }  
            $added_url[] = $package_url;
            //activities

            $seo_tags = SeoMetaTag::where(['identifier'=>'activity_listing','status'=>1])->first();
            $activity_url =  $seo_tags->page_url? '/'.$seo_tags->page_url:'/activities';
            if(CustomHelper::isAllowedModule('activity_listing')){
                $sitemap->add(Url::create($activity_url)->setPriority(0.8));
            }
            $added_url[] = $activity_url;

            
            //Hotel 

            $seo_tags = SeoMetaTag::where(['identifier'=>'hotel_listing','status'=>1])->first();
            $hotel_url =  $seo_tags->page_url? '/'.$seo_tags->page_url:'/hotels';
            if(CustomHelper::isAllowedModule('hotel_listing')){
                $sitemap->add(Url::create($hotel_url)->setPriority(0.8));
            }
            $added_url[] = $hotel_url;
           
           $seo_tags = SeoMetaTag::where(['identifier'=>'tour_category','status'=>1])->first();
           $tour_category_url =  $seo_tags->page_url? '/'.$seo_tags->page_url:'/tour-category';
            $sitemap->add(Url::create($tour_category_url)->setPriority(0.8));

            $module =  (!empty($seo_tags->page_detail_url)) ? $seo_tags->page_detail_url.'/' : '/tour-category';

            $theme_query = ThemeCategories::select('slug')->where('status',1)->where('parent_id',0)->orderBy('sort_order','ASC')->get();

            if(!empty($theme_query)){
                foreach ($theme_query as $key => $theme) {
                    $theme_slug  = $theme->slug??'';
                    if($theme_slug) {
                        $new_slug =  $module.$theme_slug;
                        if(!in_array($new_slug,$added_url)){
                            $sitemap->add(Url::create($new_slug)
                             // ->setLastModificationDate(Carbon::parse($updated_at))
                               ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                               ->setPriority(0.8)
                           );
                        }
                    }
                }
            }
            $sitemap->add(Url::create($package_url."?flag=2")->setPriority(0.8));
            $sitemap->add(Url::create($package_url."?flag=1")->setPriority(0.8));
           
            $sitemap->add(Url::create("/testimonial")->setPriority(0.8));

            $cms_pages = CmsPages::select('slug')->where('cms_type','page')->where('status',1)->where('parent_id',0)->orderBy('sort_order','ASC')->get();

            $menu_items = MenuItem::select('url')->whereNotNull('url')->where('status',1)->orderBy('sort_order','ASC')->get();

            foreach ($menu_items as $key => $menu_item) {

                 $url  = $menu_item->url ?? '';
                if(!in_array($url,$added_url)){

                    $sitemap->add(Url::create($url)
                       ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                       ->setPriority(0.8)
                   );

                }
            }

            $seo_tags = SeoMetaTag::where(['identifier'=>'cms_listing','status'=>1])->first();
            $module =  (!empty($seo_tags->page_detail_url)) ? $seo_tags->page_detail_url.'/' : '';
            if(!empty($cms_pages)){
                foreach ($cms_pages as $key => $cms_page) {
                    $cms_slug  = $cms_page->slug??'';
                    if($cms_slug) {
                        $new_slug =  $module.$cms_slug;
                        if(!in_array($new_slug,$added_url)){
                            $sitemap->add(Url::create($new_slug)
                             // ->setLastModificationDate(Carbon::parse($updated_at))
                               ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                               ->setPriority(0.8)
                           );
                        }
                    }
                }
            }
            // prd($added_url);
           
            $sitemap->writeToFile(public_path('sitemap_home.xml'));
            $this->sitemap_update('home');  
            $msg = "Home Sitemap Generate successfully";
            return redirect(url('admin/settings/sitemap'))->with('alert-success', $msg);
    
        }

        public function sitemap_update($name){
            $updated_data = [];
            $updated_data['updated_at'] = date('Y-m-d H:i:s'); 
            $isSaved = SitemapUpdate::where('slug',$name)->update($updated_data);

        }

      public function hotels(){

        $sitemap = Sitemap::create();

        $seo_tags = SeoMetaTag::where(['identifier'=>'hotel_listing','status'=>1])->first();
        $module =  $seo_tags->page_detail_url? $seo_tags->page_detail_url.'/':'/hotel/';        
        $module_listing =  $seo_tags->page_url? $seo_tags->page_url.'/':'/hotels/';

        //**Home**//
        if(CustomHelper::isAllowedModule('hotel_listing')){
            $sitemap->add(Url::create($module_listing)->setPriority(1.0));
        } 
        $hotels = Accommodation::select('slug','updated_at')->where('status',1)->get();
        $WEBSITE_URL = url('/'); 

        foreach ($hotels as $key => $hotels) {

            $updated_at = $hotels->updated_at ?? '';
            $slug = $hotels->slug ; 
            // $module = '/hotel/';
            $new_slug =  $module.$slug;
            $sitemap->add(Url::create($new_slug)
                 // ->setLastModificationDate(Carbon::parse($updated_at))
             ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
             ->setPriority(0.8)
         );

        }

        $sitemap->writeToFile(public_path('sitemap_hotels.xml'));
        $this->sitemap_update('hotels');  
        $msg = "Hotels Sitemap Generate successfully";
        return redirect(url('admin/settings/sitemap'))->with('alert-success', $msg);
    

        }

      public function packages(){

        $sitemap = Sitemap::create();
        //**Home**//

        /*$seo_tags = SeoMetaTag::where(['identifier'=>'package_listing','status'=>1])->first();
        $module =  $seo_tags->page_detail_url? $seo_tags->page_detail_url.'/':'/package/';        
        $module_listing =  $seo_tags->page_url? $seo_tags->page_url.'/':'/tour-packages/';*/

        $destinations = Destination::select('slug','updated_at')->where('is_city',0)->where('status',1)->where('parent_id',0)->get();

        $WEBSITE_URL = url('/'); 
        foreach ($destinations as $key => $destination) {

            $updated_at = $destination->updated_at ?? '';
            $slug = $destination->slug ; 
            $module = '/tour-packages/';
            $new_slug =  $module.$slug;
            $sitemap->add(Url::create($new_slug)
                 // ->setLastModificationDate(Carbon::parse($updated_at))
             ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
             ->setPriority(0.8)
         );

        }

        $seo_tags = SeoMetaTag::where(['identifier'=>'package_listing','status'=>1])->first();
        $module =  $seo_tags->page_detail_url? $seo_tags->page_detail_url.'/':'/package/';        
        $module_listing =  $seo_tags->page_url? $seo_tags->page_url.'/':'/search-packages/';

        $seo_tags_acitivity = SeoMetaTag::where(['identifier'=>'activity_listing','status'=>1])->first();
        $module =  $seo_tags->page_detail_url? $seo_tags->page_detail_url.'/':'/package/';        

        $sitemap->add(Url::create($module_listing)->setPriority(1.0)); 
        $packages = Package::select('slug','updated_at')->where('is_activity',0)->where('status',1)->get();
        $WEBSITE_URL = url('/');
        foreach ($packages as $key => $package) {

            $updated_at = $package->updated_at ?? '';
            $slug = $package->slug;
            if($package->is_activity ==1 ){

                $module = '/activity/';
            }

            $new_slug =  $module.$slug;
            $sitemap->add(Url::create($new_slug)
                 // ->setLastModificationDate(Carbon::parse($updated_at))
               ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
               ->setPriority(0.8)
           );

        }

        $sitemap->writeToFile(public_path('sitemap_packages.xml'));
        $this->sitemap_update('packages');  
        $msg = "Packages Sitemap Generate successfully";
        return redirect(url('admin/settings/sitemap'))->with('alert-success', $msg);

        }

   public function activities(){

        $sitemap = Sitemap::create();
        //**Home**//

        $seo_tags = SeoMetaTag::where(['identifier'=>'activity_listing','status'=>1])->first();
        $module =  $seo_tags->page_detail_url? $seo_tags->page_detail_url.'/':'/activity/';        

        // $sitemap->add(Url::create($module_listing)->setPriority(1.0)); 
        $packages = Package::select('slug','updated_at')->where('is_activity',1)->where('status',1)->get();
        $WEBSITE_URL = url('/'); 
        foreach ($packages as $key => $package) {

            $updated_at = $package->updated_at ?? '';
            $slug = $package->slug;
            $new_slug =  $module.$slug;
            $sitemap->add(Url::create($new_slug)
                 // ->setLastModificationDate(Carbon::parse($updated_at))
               ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
               ->setPriority(0.8)
           );

        }

        $sitemap->writeToFile(public_path('sitemap_activities.xml'));
        $this->sitemap_update('packages');  
        $msg = "Packages Sitemap Generate successfully";
        return redirect(url('admin/settings/sitemap'))->with('alert-success', $msg);
    

        }

   public function blogs(){

        $sitemap = Sitemap::create();
        //**Home**//

        $seo_tags = SeoMetaTag::where(['identifier'=>'blog_listing','status'=>1])->first();
        $module =  $seo_tags->page_detail_url? $seo_tags->page_detail_url.'/':'/blog/detail/';        
        $module_listing =  $seo_tags->page_url? $seo_tags->page_url.'/':'/blog/';

        $sitemap->add(Url::create($module_listing)->setPriority(1.0)); 
        $blogs = Blog::select('slug','updated_at')->where('status',1)->get();
        $WEBSITE_URL = url('/'); 

        foreach ($blogs as $key => $blog) {

            $updated_at = $blog->updated_at ?? '';
            $slug = $blog->slug;

            // $module = '/blog/detail/';
            $new_slug =  $module.$slug;
            $sitemap->add(Url::create($new_slug)
                     ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                     ->setPriority(0.8)
            );

        }

        $sitemap->writeToFile(public_path('sitemap_blogs.xml'));
        $this->sitemap_update('packages'); 
        $msg = "Blogs Sitemap Generate successfully";
        return redirect(url('admin/settings/sitemap'))->with('alert-success', $msg);
    
    }

      
   

}