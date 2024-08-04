<style>
    .top_title_admin.fancybox-content {
    width: 750px;
    height: 410px;
}
</style>
<?php
$id = (isset($faq->id))?$faq->id:'';
$question = (isset($faq->question))?$faq->question:'';
$categoryArr = (isset($faq->category))?explode(',',$faq->category):[];
$answer = (isset($faq->answer))?$faq->answer:'';
$sort_order = (isset($faq->sort_order))?$faq->sort_order:0;
$status = (isset($faq->status))?$faq->status:1;

$faqCategoryObj = "";
if(!empty($categoryArr)){
    #\DB::enableQueryLog();
    $faqCategoryObj = App\Models\FaqCategory::whereIn('id',$categoryArr)->get();
    #dd(\DB::getQueryLog());
}

$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

if(empty($back_url)){
    $back_url = $routeName.'/faqs';
}
?>

<div class="top_title_admin">
<div class="title">
<h2>{{ $page_heading }}</h2>
</div>

<div class="centersec">
<div class="bgcolor viewsec">

    @include('snippets.errors')
    @include('snippets.flash')

<div class="ajax_msg"></div>
<table border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec" class="table-responsive">

<tr>
    <td width="806" valign="top" class="innersec">
        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">

             <tr>
                <td colspan="3">
                    <div><b>Question: </b></div>
                    <div>{{$faq->question}}</div>
                </td>
             </tr>

            <tr>
                <td colspan="3">
                    <div><b>Answer: </b></div>
                    <div>{!!$faq->answer!!}</div>
                </td>
             </tr>

            {{--<tr>
                <td>
                    <div><b>Faq Category: </b></div>
                    <div><?php if(!empty($faqCategoryObj) && !($faqCategoryObj->isEmpty())){
                                    foreach ($faqCategoryObj as $faqCate) {
                                        $faqArr[] = $faqCate->title;
                                    }
                                    echo implode(', ', $faqArr);
                                }
                                ?>
                
                    </div>
                </td>
                <td>
                    <div><b>Destination: </b></div>
                    <div>{{ (isset($faq->faqParentDestination)) ? $faq->faqParentDestination->name:'' }}</div>
                </td>
                <td>
                    <div><b>Sub Destination: </b></div>
                    <div>{{ (isset($faq->faqDestination)) ? $faq->faqDestination->name:'' }}</div>
                </td>
            </tr>--}}

            <tr>

                 <td>
                    <div><b>Status: </b></div>
                    <div>{{ CustomHelper::getStatusStr($faq->status) }}</div>
                </td>

                <td>
                    <div><b>Sort Order: </b></div>
                    <div>{{$faq->sort_order}}</div>
                </td>
                <td>
                    <div><b>Date Created: </b></div>
                    <div>{{ CustomHelper::DateFormat($faq->created_at, 'd/m/Y') }}</div>
                </td>
            </tr>
</table>
</div>
</div>