<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Models\Package;
use App\Models\SmtpSetting;
use App\Models\EmailTemplate;
use App\Helpers\CustomHelper;
use Mail;
use DB;
use Validator;
use Storage;

class TestimonialController extends Controller
{

    private $limit;
    public function __construct(){
        $this->limit = 40;
    }

    public function index(Request $request) {
        $data = [];
        $limit = $this->limit;

        $slug = (isset($request->slug))?$request->slug:'';
        $seo_tags = CustomHelper::getSeoModules('testimonial_listing');
        $data['page_title'] = (!empty($seo_tags->page_title))?$seo_tags->page_title:'';
        $data['page_url_label'] = $seo_tags->page_url_label??'Testimonials';
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

        $testimonials = Testimonial::where('status',1)->orderBy('featured','desc')->orderBy('sort_order','asc');

        $testimonials->where(function($query1){
            $query1->where('is_deleted',0);
            $query1->orWhereNull('is_deleted');
        });

        $data['testimonials'] = $testimonials->whereNotNull('slug')->paginate($limit);
        return view(config('custom.theme').'.testimonials.index', $data);
    }

        
    public function details(Request $request) {
        $data = [];
        $limit = $this->limit;
        $slug = (isset($request->slug))?$request->slug:'';
        if(isset($slug) && !empty($slug)) {
            $query = Testimonial::where('slug',$slug)->where('status',1);
            $query->where(function($q1){
                $q1->where('is_deleted',0);
                $q1->orWhereNull('is_deleted');
            });
            $testimonialDetails = $query->first();

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
                $data['meta_title'] = $meta_title;
                $data['meta_description'] = $meta_description;
                $data['meta_keyword'] = $meta_keyword;
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

                return view(config('custom.theme').'.testimonials.details', $data);
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    public function add(Request $request) {
        
        $data = [];
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
                return redirect()->back()->withInput()->with('alert-danger', 'Invalid form values.');
            }

            $message = [];
            $validator = Validator::make($request->all(), $rules,$message,$validation_msg);

            if ($validator->fails()){
                return back()->withInput()->withErrors($validator);

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
                    '{header}' => $common_logo,
                    '{contact_details}' => $contact_details??'',
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
                   return redirect('thankyou')->with('alert-success', 'Thank You for connecting Us.');
            //     }
            //    else{
            //        return back()->with('alert-danger', 'Error in submitting form.');
            //     }
            }
        }

        $seo_tags = CustomHelper::getSeoModules('testimonial_listing');
        $data['page_title'] = $seo_tags->page_title??'';
        $data['page_url_label'] = $seo_tags->page_url_label??'Testimonials';
        $data['page_brief'] = $seo_tags->page_brief??'';
        $data['page_description'] = $seo_tags->page_description??'';
        $data['meta_title'] = $seo_tags->meta_title??'';
        $data['meta_description'] = $seo_tags->meta_description??'';
        $data['meta_keyword'] = $seo_tags->meta_keyword??'';
        $data['other_meta_tag'] = $seo_tags->other_meta_tag??'';
        $banner_image = '';
        if($seo_tags->image) {
            $banner_image = url('/storage/seo_tags/'.$seo_tags->image);
        }
        $data['banner_image'] = $banner_image;

        return view(config('custom.theme').'.testimonials.testimonial_add', $data);
    }

}
