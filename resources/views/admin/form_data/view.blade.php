@component('admin.layouts.main')

@slot('title')
Admin -  Manage Forms Data  View  - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$back_url=CustomHelper::BackUrl(); 
$routeName = CustomHelper::getAdminRouteName();

#prd($formElements); 

?>

<div class="row">
    <div class="col-md-12">
        <div class="titlehead">
            <h1 class="pull-left">Form Data Detail</h1>
            <a href="javascript:;" onclick="history.back()" class="btn btn-success btn-sm" style='float: right;'>Back</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <?php $xData=array(); 
                if(!empty($form_data)){

                    $unsetArray=array('g-recaptcha-response','action'); 

                    $unserializeData = unserialize($form_data->data);
                    foreach($unsetArray as $rec){
                       if(isset($unserializeData[$rec])){
                        unset($unserializeData[$rec]); 
                    }
                }

                     #  prd($unserializeData); 
                foreach($unserializeData as $key=>$value){
                    if($value!='')
                    {
                     $parseKey=array();    
                    
                    if(!isset($formElements[$key])){
                     $parseKey=explode('_full_number',$key); 
                   // prd($formElements[$parseKey[0]]); 
                     $formElementsName=isset($formElements[$parseKey[0]]->label)?$formElements[$parseKey[0]]->label.' with country code':'';


                    }
                   

                    if(isset($formElements[$key]) && $formElements[$key]->type=='text_only'){
                      break;
                  }else  if(isset($formElements[$key]) && $formElements[$key]->type=='datepicker'){
                    if(isset($value)){
                      if(isset($value)){
                       $value= CustomHelper::DisplayDateFormat($value); 
                   }
               }
           }else if(isset($formElements[$key]) && $formElements[$key]->type=='timepicker'){
            if(isset($value)){
               if(isset($value)){
                   $value= CustomHelper::DisplayTimeFormat($value); 
               }
           }
       }else if(isset($formElements[$key]) && $formElements[$key]->type=='datetimepiker'){
        if(isset($value)){
           $value= CustomHelper::DisplayDateTimeFormat($value); 
                #prd($value);
       }
   }else if(isset($formElements[$key]) && $formElements[$key]->type=='checkbox'){
    if(isset($value)){
       $value=implode(', ',$value); 
                #prd($value);
   }
}else if(isset($formElements[$key]) && $formElements[$key]->type=='file'){
    if(isset($value)){
        $imageExt=array('jpg','jpeg','png');   
        $ext = pathinfo($value, PATHINFO_EXTENSION);
        if(in_array($ext,$imageExt)){
            $value='<a href="'.asset($value).'" target="_blank"><img src="'.url($value).'" style="width:75px;"></a>';
        }else{
           $staticImage='/assets/img/document.png';
           $value='<a href="'.asset($value).'" target="_blank"><img src="'.url($staticImage).'" style="width:75px;"></a>';   
       }               
   }
}



?>
<tr>
    <th class="text-left">{{ (isset($formElements[$key]))?$formElements[$key]->label:$formElementsName}}</th>
    <td class="text-left"> 
      <?php echo $value ?>

  </td>
</tr>
<?php } }?>






<?php } ?>
<tr>
  <th class="text-left">Ip Address</th><td>{{ $form_data->ipAddress }}</td></tr>
<tr>
<tr>
  <th class="text-left">Captch Verified</th><td>{{ ($form_data->is_captch)?'Yes':'No' }}</td></tr>
<tr>

    <tr>
  <th class="text-left">User Agent</th><td>{{ $form_data->user_agent }}</td></tr>
<tr>


  <th class="text-left">Submited on</th><td>{{ CustomHelper::DisplayDateTimeFormat($form_data->created_at) }}</td></tr>
</table>
</div>
</div>
</div>


@slot('bottomBlock')

@endslot



@endcomponent