<?php
namespace App\Http\Controllers\js;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\Package;
use App\Models\SmtpSetting;
use App\Models\EmailTemplate;
use App\Models\UrlRedirection;
use App\Helpers\CustomHelper;
use Mail;
use DB;
use Validator;
use Storage;
use Inertia\Inertia;
use Response;

class TestimonialController extends Controller
{

    private $limit;
    private $theme;
    public function __construct(){
        $this->limit = 40;
        $this->theme = config('custom.themejs');
    }

    public function index(Request $request) {
        $currentUrl = \Request::path();
        $url_redirection = UrlRedirection::where('source_url',$currentUrl)->where('show',1)->first();
        if(!empty($url_redirection) && !empty($url_redirection->destination_url)) {
            $redirectUrl = url($url_redirection->destination_url);
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$redirectUrl);
            exit();
        }
        $data = [];
        $seo_data = [];
        $limit = $this->limit;

        $slug = (isset($request->slug))?$request->slug:'';

        $seo_tags = CustomHelper::getSeoModules('testimonial_listing');
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
        }
        $seo_data['banner_image'] = $banner_image;

        $query = Testimonial::where('status',1)->orderBy('featured','desc')->orderBy('sort_order','asc');
        $query->where(function($query1){
            $query1->where('is_deleted',0);
            $query1->orWhereNull('is_deleted');
        });
        $testimonials = $query->whereNotNull('slug')->paginate($limit);
        $links = '';
        $records_arr = [];
        if($testimonials) {
            $storage = Storage::disk('public');
            $path = "testimonials/";
            foreach($testimonials as $testimonial) {
                $record = [];
                $imageSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
                $image = $testimonial->image;
                if(!empty($testimonial->image) && $storage->exists($path.$testimonial->image)) {
                    $imageSrc = asset('/storage/'.$path.$testimonial->image);
                }
                    $thumbPath = 'testimonials/thumb/';
                  $testimonialThumbSrc = asset(config('custom.assets').'/images/testimonial-main-default.png');
                  if(!empty($testimonial->image) && $storage->exists($path.$testimonial->image)){
                     if($storage->exists($path.$image)){
                        $testimonialSrc = asset('/storage/'.$path.$image);
                     }
                     if($storage->exists($path.$image)){
                        $testimonialThumbSrc = asset('/storage/'.$path.$image);
                     }
                  }
                  $testimonialImages = $testimonial->testimonialImages??[];
                  if(empty($testimonialThumbSrc) && empty($testimonialImages)) {
                     // $testimonialThumbSrc = asset(config('custom.assets').'/images/noimage.jpg');
                     $testimonialThumbSrc = asset(config('custom.assets').'/images/noimage.jpg');
                  }
                $record['name'] = CustomHelper::wordsLimit($testimonial->name);
                $drecordata['description'] = CustomHelper::wordsLimit($testimonial->description);
                $record['slug'] = $testimonial->slug??'';
                $record['imageSrc'] = $imageSrc;
                $record['testimonialThumbSrc'] = $testimonialThumbSrc;
                // prd($testimonialThumbSrc);
                $record['url'] = route('testimonialDetails',['slug'=>$testimonial->slug]);
                $records_arr[] = $record;
            }
        }

        $params = [];
        $search_data = CustomHelper::getSearchData('testimonials',$params);
        $testimonial_listing = CustomHelper::getSeoModules('testimonial_listing');
        $testimonialListing = $testimonial_listing->page_url??'testimonials';
        $testimonials->withPath($testimonialListing);
        $results = [];
        $results['data'] = $records_arr;
        $results['links'] = $testimonials->appends($search_data)->links('vendor.pagination.bootstrap-4')->render();
        $data['results'] = $results;
        $data['seo_data'] = $seo_data;
        View::share('seo_data', $seo_data);
        $data['WRITE_YOUR_TESTIMONIAL_URL'] = route('testimonialadd');
        return Inertia::render('testimonials/index', $data);
        // return view(config('custom.theme').'.testimonials.index', $data);
    }

        
    public function ajaxSearchTestimonial(Request $request) {
        $response = [];
        $seo_data = [];
        $response['success'] = false;
        $response['message'] = '';
        $limit = $request->limit??10;
        $featured = $request->featured??0;

        $query = Testimonial::with('testimonialImages')->where('status',1)->orderBy('sort_order','asc');
        $query->where(function($q1) {
            $q1->where('is_deleted', 0);
            $q1->orWhereNull('is_deleted');
        });
        if($request->featured) {
            $query->where('featured',$request->featured);
        }
        $testimonials = $query->paginate($limit);
        $testimonials_arr = [];
        foreach ($testimonials as $testimonial) {
            $testimonials_arr[] = Testimonial::parseTestimonial($testimonial);
        }

        $search_data = [];
        $results = [];
        $results['data'] = $testimonials_arr;
        $results['links'] = $testimonials->appends($search_data)->links('vendor.pagination.bootstrap-4')->render();
        $data['results'] = $results;

        $data['success'] = true;
        return Response::json($data, 200);
    }


    public function details(Request $request) {
        $currentUrl = \Request::path();
        $url_redirection = UrlRedirection::where('source_url',$currentUrl)->where('show',1)->first();
        if(!empty($url_redirection) && !empty($url_redirection->destination_url)) {
            $redirectUrl = url($url_redirection->destination_url);
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$redirectUrl);
            exit();
        }
        $data = [];
        $seo_data = [];
        $limit = $this->limit;
        $slug = (isset($request->slug))?$request->slug:'';
        if(isset($slug) && !empty($slug)) {
            $query = Testimonial::where('slug',$slug)->where('status',1);
            $query->where(function($q1){
                $q1->where('is_deleted',0);
                $q1->orWhereNull('is_deleted');
            });
            $testimonialDetails = $query->first();
            // prd($testimonialDetails);
            if(isset($testimonialDetails) && !empty($testimonialDetails)) {
                $data['testimonialDetails'] = $testimonialDetails;

                $seo_tags = CustomHelper::getSeoModules('testimonial_listing');
                $data['breadcrumb_title'] = $seo_tags->page_url_label??'Testimonials';

                $meta_title = $testimonialDetails->meta_title??'';
                $meta_description = $testimonialDetails->meta_description??'';
                $meta_keyword = $testimonialDetails->meta_keywords??'';
                if(empty($meta_title)) {
                    $meta_title = $testimonialDetails->title;
                }
                $seo_data['meta_title'] = $meta_title;
                $seo_data['meta_description'] = $meta_description;
                $seo_data['meta_keyword'] = $meta_keyword;
                $data['page_heading'] = $testimonialDetails->title;

                $package_ids = json_decode($testimonialDetails->package_id)??[];
                $destination_ids = json_decode($testimonialDetails->destination_id)??[];

                $query = Testimonial::where('status',1)->where('slug','!=',$slug)->orderBy('featured','desc')->orderBy('sort_order','desc');
                $query->where(function($query1){
                    $query1->where('is_deleted',0);
                    $query1->orWhereNull('is_deleted');
                });
                if(!empty($package_ids) && is_array($package_ids)) {
                    $query->where(function($q1) use($package_ids) { 
                        $package_id = $package_ids[0];
                        $q1->orWhereJsonContains('package_id',[(string)$package_id]);
                    });
                }
                if(!empty($destination_ids) && is_array($destination_ids)) {
                    $query->where(function($q1) use($destination_ids) {
                        $destination_id = $destination_ids[0];
                        $q1->orWhereJsonContains('destination_id',[(string)$destination_id]);
                    });
                }
                $related_testimonials = $query->limit($limit)->get();
                $data['related_testimonials'] = $related_testimonials;

                $similar_testimonials = [];
                if($related_testimonials){
                    foreach($related_testimonials as $relatedTestimonial) {
                        $storage = Storage::disk('public');
                        $testimonial_path = "testimonials/";
                        $testimonial_thumb_path = "testimonials/thumb/";

                        $imageSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
                        $testimonialThumbSrc = asset(config('custom.assets').'/images/testimonial-main-default.png');
                   

                        $image = $relatedTestimonial->image;
                        if(!empty($image) && $storage->exists($testimonial_thumb_path.$image)){
                            $testimonialThumbSrc = asset('/storage/'.$testimonial_thumb_path.$image);
                        }
                        if(!empty($image) && $storage->exists($testimonial_path.$image)){
                            $imageSrc = asset('/storage/'.$testimonial_path.$image);
                        }
                        $data['slug'] = $relatedTestimonial->slug??'';
                        $data['testimonialThumbSrc'] = $testimonialThumbSrc;
                        $data['imageSrc'] = $imageSrc;
                        $data['url'] = route('testimonialDetails',['slug'=>$relatedTestimonial->slug]);

                        $similar_testimonials[] = [
                            'name' => $relatedTestimonial->name??'',
                            'description' => $relatedTestimonial->description??'',
                            'testimonialThumbSrc' => $testimonialThumbSrc,
                            'imageSrc' => $imageSrc,
                            'url' => route('testimonialDetails',['slug'=>$relatedTestimonial->slug]) ,
                        ];
                    }

                    $data['related_testimonials'] = $similar_testimonials;
                }

                $explore_package_query = Package::where('status',1);
                $explore_package_query->where('featured',1);
                $explore_package_query->where(function($q1){
                    $q1->where('is_deleted',0);
                    $q1->orWhereNull('is_deleted');
                });
                $explore_package_query->orderBy('featured','DESC');
                $explore_package_query->orderBy('sort_order','ASC');
                $data['explore_package'] = $explore_package_query->take(3)->get();
                // $data['explore_package'] = Package::where('featured',1)->orderBy('sort_order','asc')->take(3)->get();
                // prd($data['explore_package']);
                $explore_package = $explore_package_query->where('is_activity',0)->take(3)->get();
                if($explore_package) {
                    $explore_packages_arr = [];
                    foreach($explore_package as $package) {
                        $explore_packages_arr[] = Package::parsePackage($package);
                    }
                    $data['explore_package'] = $explore_packages_arr;
                    $data['tourPackagesUrl'] = route('tourPackages',$testimonialDetails->slug);
                }
                //prd($explore_package);

                $testimonials_arr = [];
                if($testimonialDetails) {

                    $seo_tags = CustomHelper::getSeoModules('testimonial_listing');
                    $data['breadcrumb_title'] = $seo_tags->page_url_label??'Testimonials';

                    $storage = Storage::disk('public');
                    $path = 'testimonials/';
                    $thumbPath = 'testimonials/';
                    $testimonialThumbSrc = asset(config('custom.assets').'/images/testimonial-main-default.png');
                    $testimonialSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
                   
                        // prd($testimonialDetails);
                        if($testimonialDetails){
                        $tImage = $testimonialDetails->image;
                        if(!empty($tImage)){
                            if($storage->exists($path.$tImage)){
                                $testimonialSrc = asset('/storage/'.$path.$tImage);
                            }
                            if($storage->exists($thumbPath.$tImage)){
                                $testimonialThumbSrc = asset('/storage/'.$thumbPath.$tImage);
                            }
                        }
                        $testimonialImages = $testimonialDetails->testimonialImages??[];
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
                                'alt' => $testimonialDetails->title,
                                'src' => $testimonialThumbSrc,
                            ];
                        }
                        $testimonials_arr = [
                            'name' => $testimonialDetails->name,
                            'description' => $testimonialDetails->description,
                            'testimonialSrc' => $testimonialSrc,
                            'thumbSrc' => $testimonialThumbSrc,
                            'url' => route('testimonialDetails',['slug'=>$testimonialDetails->slug]) ,
                            'images' => $images,
                        ];
                    }
          
                }
                //prd($testimonials_arr);
                $data['testimonialDetails'] = $testimonials_arr;

            $breadcrumbData = [];
            $breadcrumbData[] = ['url'=>'/','label'=>'Home'];
            $breadcrumbData[] = ['url'=>route('testimonialListing'),'label'=>$data['breadcrumb_title']];
            
            $breadcrumbData[] = ['label'=>$testimonialDetails->name];
            $data['breadcrumbData'] = $breadcrumbData;
            $data['seo_data'] = $seo_data;
        View::share('seo_data', $seo_data);
            $data['WRITE_YOUR_TESTIMONIAL_URL'] = route('testimonialadd');

                return Inertia::render('testimonials/details', $data);

                //return view(config('custom.theme').'.testimonials.details', $data);
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    public function add(Request $request) {
        
        $data = [];
        $seo_data = [];
        $testimonialData = [];
        $limit = $this->limit;

        // $seo_tags = CustomHelper::getSeoModules('testimonial_listing');
        // $data['breadcrumb_title'] = $seo_tags->page_title??'';
        // $data['page_title'] = $seo_tags->page_title??'';
        // $data['page_brief'] = $seo_tags->page_brief??'';
        // $data['page_description'] = $seo_tags->page_description??'';
        // $data['meta_title'] = $seo_tags->meta_title??'';
        // $data['meta_description'] = $seo_tags->meta_description??'';
        // $data['meta_keyword'] = $seo_tags->meta_keyword??'';
        // $data['other_meta_tag'] = $seo_tags->other_meta_tag??'';
        // $data['image'] = $seo_tags->image??'';

        if($request->method() == 'POST' || $request->method() == 'post'){
            //prd($request->all());
            $rules['title'] = 'required|min:2|regex:/^[\pL\s\-]+$/u';
            $rules['name'] = 'required|min:2|regex:/^[\pL\s\-]+$/u';
            // $rules['email'] = 'required|email|unique:users|max:255|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
            $rules['email'] = 'required|email|max:255|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
            $rules['description'] = 'required';
            $token = $request['g-recaptcha-response'];
            $validation_msg = [];
            $captchaResp = CustomHelper::checkGoogleReCaptcha($token);
            //prd($captchaResp);
            if(!empty($captchaResp) && $captchaResp['success'] == 1 && $captchaResp["score"] < 0.5){
                    return response()->json([
                    'status' => 'alert-danger',
                    'message' => 'Invalid form values.',
                ]);
                //return redirect()->back()->withInput()->with('alert-danger', 'Invalid form values.');
            }

            $message = [];
            $validator = Validator::make($request->all(), $rules,$message,$validation_msg);
            if($validator->fails()) {
                return Response::json(array(
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()
                ), 400); // 400 being the HTTP code for an invalid request.
            }else{

                // $email_subject = "Testimonial | ".CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                // prd($request->toArray());
                $testimonialData = [];
                $testimonialData['description']= $request->description;
                $testimonialData['status']= $request->status??0;
                $testimonialData['company_name']= $request->company_name??'';
                $testimonialData['position_in_company']= $request->position_in_company??'';
                $testimonialData['website']= $request->website??'';
                $testimonialData['title']= $request->title;
                $testimonialData['name']= $request->name;
                $testimonialData['date_on']= date('Y-m-d-H-i-s');
                $email = $request->email;
                $testimonialData['email'] = $email;
                $savedId = DB::table('testimonials')->insertGetId($testimonialData);
                // $REPLYTO = $email;
                // $to_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
                // $from_email = CustomHelper::WebsiteSettings('FROM_EMAIL');
                // $attachments = [];
                // $getSenderName = SmtpSetting::where('id',1)->select('from_mail','from_name')->first();
                // $sender_email = $getSenderName->from_mail;
                // $from_name = $getSenderName->from_name;
                // $mail_title = 'Testimonial Email';
                $reciver_emails = CustomHelper::WebsiteSettings('ADMIN_EMAIL');

                $title = isset($request->title) ? $request->title : '';
                $name = isset($request->name) ? $request->name : '';
                $email = isset($request->email) ? $request->email : '';
                $company_name = isset($request->company_name) ? $request->company_name : '';
                $position_in_company = isset($request->position_in_company) ? $request->position_in_company : '';
                $website = isset($request->website) ? $request->website : '';
                $description = isset($request->description) ? $request->description : '';
                $reply_to = $email;

                // $email_params = array(
                //     '{title}' => $title,
                //     '{name}' => $name,
                //     '{email}' => $email,
                //     '{company_name}' => $company_name,
                //     '{position_in_company}' => $position_in_company,
                //     '{website}' => $website,
                //     '{description}' => $description,
                // );
                // $res = CustomHelper::CommonMail($from_name, $mail_title, '', $reciver_emails, $sender_email, $email_params, $attachments, $reply_to);

                $websiteSettingsArr = CustomHelper::getSettings(['FRONTEND_LOGO']);
                $storage = Storage::disk('public');
                $path = "settings/";
                $logoSrc =asset(config('custom.assets').'/images/logo.png');
                if(!empty($websiteSettingsArr['FRONTEND_LOGO'])){
                    $logo = $websiteSettingsArr['FRONTEND_LOGO'];
                    if($storage->exists($path.$logo)){
                        $logoSrc = asset('/storage/'.$path.$logo);
                    }
                }

                $common_logo = '';
                $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                $b2c_logo_params = array(
                 '{company_logo}' => $logoSrc
             );
                $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);

                $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
                $system_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
                $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
                $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

                $sales_phone = CustomHelper::getPhoneHref($company_phone);
                $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
                $sales_email = CustomHelper::getEmailHref($company_email);

                $contact_details = '';
                $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
                $b2c_email_params = array(
                    '{company_name}' => $company_name,
                    '{sales_mobile}' => $sales_mobile,
                    '{sales_phone}' => $sales_phone,
                    '{sales_email}' => $sales_email,
                    '{company_title}' => $system_title
                );
                $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);

                $email_params = array(
                    '{title}' => $title,
                    '{name}' => $name,
                    '{email}' => $email,
                    '{company_name}' => $company_name,
                    '{position_in_company}' => $position_in_company,
                    '{website}' => $website,
                    '{description}' => $description,
                    '{header}' => $common_logo??'',
                    '{contact_details}' => $contact_details ?? '',
                    '{date}' => date("l jS \of F Y h:i A"),
                );
                
                $template_slug = 'testimonial-email';
                $email_content_data = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();
        
                $email_content = isset($email_content_data->content) ? $email_content_data->content : '';
                // $email_params = isset($email_params) ? $email_params : [];
                $email_content = strtr($email_content, $email_params);
                $email_subject = isset($email_content_data->subject) ? $email_content_data->subject : '';
                $email_subject = strtr($email_subject, $email_params);
        
                $to_email = $reciver_emails;
                $cc_email = '';
                $bcc_email = '';
                if($email_content_data->email_type == 'customer' && $email_content_data->bcc_admin == 1){
                    $bcc_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL'); 
                }
                if(isset($email_content_data) && !empty($email_content_data)){
                    $params = [];
                    $params['to'] = $to_email;
                    $params['cc'] = $cc_email;
                    $params['bcc'] = $bcc_email;
                    $params['subject'] = $email_subject;
                    $params['email_content'] = $email_content;
                    $params['reply_to'] = $reply_to;
                    $params['module_name'] = 'Testimonial';
                    $params['record_id'] = $savedId ?? 0;
                    $isSent = CustomHelper::sendCommonMail($params);
                }

                // $query_email = CustomHelper::sendEmail('emails.testimonial_add_email', $testimonialData, $to=$to_email, $from_email, $REPLYTO = $from_email, $email_subject);
                //$isSaved = ContactEnquiry::create($emailData);
                // DB::table('testimonials')->insert($testimonialData);

                // query_email-->these variable used when email through data send on local used $email only here.
                // if($res){
                return Response::json(array(
                'success' => true,
                'redirect_url' => '/thankyou',//route('book_now')
                'message' => 'Thank You for connecting Us.' 
                ), 200);
                   //return redirect('thankyou')->with('alert-success', 'Thank You for connecting Us.');
            //     }
            //    else{
            //        return back()->with('alert-danger', 'Error in submitting form.');
            //     }
            }
        }

        $seo_tags = CustomHelper::getSeoModules('testimonial_listing');
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
        }
        $seo_data['banner_image'] = $banner_image;
        // prd($seo_data);
        $data['seo_data'] = $seo_data;
        View::share('seo_data', $seo_data);

        $data['name_title_arr'] = config('custom.name_title_arr');

        return Inertia::render('testimonials/add', $data);

        //return view(config('custom.theme').'.testimonials.testimonial_add', $data);
    }

}
