<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Sitemap\SitemapGenerator;
use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Accommodation;
use App\Models\Package;
use App\Models\Blog;

use CustomHelper;
use Storage;

class SitemapController extends Controller
{

    public function index(){

        SitemapGenerator::create(route('homePage'))
            ->getSitemap()
            ->writeToFile(public_path('sitemap_home.xml'));
        // ->writeToFile(public_path('sitemap_home.xml'));

        echo 'done'; 
    }


public function sitemap_file(){

        header('Content-type: text/xml');
        $cdn_url = CustomHelper::WebsiteSettings('CLOUD_FRONT_URL');
        $WEBSITE_URL = url('/'); //CustomHelper::WebsiteSettings('WEBSITE_URL');

        //$file_arr = ['sitemap_home','sitemap_hotels','sitemap_packages','sitemap_activities','sitemap_blogs'];

        $file_arr[] = 'sitemap_home';
        if(CustomHelper::isAllowedModule('package_listing')){
            $file_arr[] = 'sitemap_packages';
        }
        if(CustomHelper::isAllowedModule('activity_listing')){
            $file_arr[] = 'sitemap_activities';
        }
        if(CustomHelper::isAllowedModule('hotel_listing')){
            $file_arr[] = 'sitemap_hotels';
        }
        if(CustomHelper::isAllowedModule('blog_listing')){
            $file_arr[] = 'sitemap_blogs';
        }
        $file_text = '<?xml version="1.0" encoding="UTF-8"?>
        <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
       
        foreach ($file_arr as $key => $file) {
            $file_text .=' <sitemap><loc>'.$WEBSITE_URL.'/'.$file.'.xml</loc></sitemap>';
         }
         $file_text .=' </sitemapindex>';
         echo $file_text ;
         exit;

 }

    public function hotels(){      
        
            //==================
            header('Content-type: text/xml');
            $file_text ='<?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

            //==============
             $hotels = Accommodation::select('slug','updated_at')->where('status',1)->get();
             $WEBSITE_URL = url('/'); 

            foreach ($hotels as $key => $hotels) {

                $updated_at = $hotels->updated_at ?? '';
                $slug = $hotels->slug ;
                $changefreq = 'daily';

                $file_text .=   '<url>
                <loc>'.$WEBSITE_URL.'/hotel/'.$slug.'</loc>
                <lastmod>'.$updated_at.'</lastmod>
                <changefreq>'.$changefreq.'</changefreq>
                <priority>0.9</priority>
                </url>';
            }

            $file_text .='</urlset>';
            echo $file_text ;
            exit;
    }
    
  public function packages(){      
        
            //==================
            header('Content-type: text/xml');
            $file_text ='<?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

            //==============
             $packages = Package::select('is_activity','slug','updated_at')->where('status',1)->orderBy('sort_order','asc')->get();
             $WEBSITE_URL = url('/');     

            foreach ($packages as $key => $package) {
                
                $module = '/package/';
                if($package->is_activity ==1 ){
                    $module = '/activity/';
                }

                $updated_at = $package->updated_at ?? '';
                $slug = $package->slug ;
                $changefreq = 'daily';

                $file_text .=   '<url>
                <loc>'.$WEBSITE_URL.$module.$slug.'</loc>
                <lastmod>'.$updated_at.'</lastmod>
                <changefreq>'.$changefreq.'</changefreq>
                <priority>0.9</priority>
                </url>';
            }

            $file_text .='</urlset>';
            echo $file_text ;
            exit;
    }

 public function blogs(){      
        
            //==================
            header('Content-type: text/xml');
            $file_text ='<?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

            //==============
             $blogs = Blog::select('slug','updated_at')->where('status',1)->where('type','blogs')->orderBy('sort_order','asc')->get();
             $WEBSITE_URL = url('/');     

            foreach ($blogs as $key => $blog) {
                
                $module = '/blog/detail/';
                $updated_at = $blog->updated_at ?? '';
                $slug = $blog->slug ;
                $changefreq = 'daily';

                $file_text .=   '<url>
                <loc>'.$WEBSITE_URL.$module.$slug.'</loc>
                <lastmod>'.$updated_at.'</lastmod>
                <changefreq>'.$changefreq.'</changefreq>
                <priority>0.9</priority>
                </url>';
            }

            $file_text .='</urlset>';
            echo $file_text ;
            exit;
    }
}
