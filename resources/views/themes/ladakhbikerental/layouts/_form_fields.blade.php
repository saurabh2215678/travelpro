<?php
$pl_holder_val = '';
$element=(object)$element;
#prd((object)$element);
#prd($element->toArray());
$name = isset($element->label)?$element->label:'';
$id = isset($element->id)?$element->id:0;
$data = isset($element->data)?$element->data:'';
$placeholder = isset($element->label)?$element->label:'';
$defaultValue = isset($element->defaultValue)?$element->defaultValue:'';
$validation = isset($element->validation)?$element->validation:'';

$is_array = isset($element->is_array)?$element->is_array:'';
$data_table = isset($element->data_table)?$element->data_table:'';

$className = isset($element->className)?$element->className:'';
$classNameInner = isset($element->classNameInner)?$element->classNameInner:'';
$placeholder = isset($element->placeHolder)?$element->placeHolder:'';
$placeholder = isset($element->placeHolder)?$element->placeHolder:'';

if($data_table!='') {

}

$place_holder = '';
$old = old('');

if($place_holder)
{ 
  // pr($full_data); 
	$pl_holder_val=$full_data->field_label;
	if($full_data->validations=='required')
	{
		$pl_holder_val=$full_data->field_label."*";
	}
} 
switch ($element->type) {
	case 'name':
	?>
	<div class="form-floating mb-3 {{$className}}"><label>{{$name}}</label><input type="text" placeholder="{{$placeholder}}" {{($validation=='required')?'required':''}} class="form-control {{$classNameInner}}" dataTypeInput="input{{$element->type}}" name="input{{$id}}" value="{{old('input'.$id)}}" /><span class="input{{$id}}_error error"></span></div>
	<?php
	break;

case 'email':
?>
<div class="form-floating mb-3 {{$className}}"><label>{{$name}}</label><input type="email" placeholder="{{$placeholder}}" {{($validation=='required')?'required':''}} class="form-control {{$classNameInner}}" dataTypeInput="{{$element->type}}" name="input{{$id}}" value="{{old('input'.$id)}}" /><span class="input{{$id}}_error error"></span></div>
<?php
break;

case 'phone':
?>
<div class="form-floating mb-3 {{$className}}"><label>{{$name}}</label>

@if(isset($slug) && $slug == '[call_back_request]')
	<input style="width: 50px;" type="text" placeholder="Country Code" class="form-control" name="input{{$id}}_country_code" value="+91" /><span class="input{{$id}}_country_code_error error"></span>
@else
<select name="input{{$id}}_country_code" class="form-select country_code">
<?php /*{{$country->emoji}}*/ ?>
		@if($countries)
		@foreach($countries as $country)
		<option value="{{$country->phonecode}}" {{($country->phonecode==91)?'selected':''}} >+{{$country->phonecode}}</option>
		@endforeach
		@endif
</select>
@endif
	<input type="text" {{($validation=='required')?'required':''}} placeholder="{{$name}}" class="form-control {{$classNameInner}}" dataTypeInput="{{$element->type}}" name="input{{$id}}" value="{{old('input'.$id)}}" maxlength="12" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" /><span class="input{{$id}}_error error"></span>
</div>
<?php
break;


case 'country':
?>
<div class="form-floating mb-3 {{$className}}"><label>{{$name}}</label><select class="form-select {{$classNameInner}}" {{($validation=='required')?'required':''}} name="input{{$id}}">
	<?php
	$selectVal = old('input'.$id,'101');
	?>
	@if($countries)
	@foreach($countries as $country)
	<option value="{{$country->name}}" {{($country->id==$selectVal)?'selected':''}} >{{$country->name}}</option>
	@endforeach
	@endif
</select>				
<span class="input{{$id}}_error error"></span>
</div>
<?php
break;


case 'zipcode':
?>
<div class="form-floating mb-3 {{$className}}"><label>{{$name}}</label>
	<input type="text" {{($validation=='required')?'required':''}} placeholder="{{$placeholder}}" class="form-control {{$classNameInner}}" dataTypeInput="{{$element->type}}" name="input{{$id}}" value="{{old('input'.$id)}}" maxlength="10" <?php /*onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"*/ ?> /><span class="input{{$id}}_error error"></span>
	
</div>
<?php
break;


	case 'text':
	?>
	<div class="form-floating mb-3 {{$className}}"><label>{{$name}}</label><input type="text" placeholder="{{$placeholder}}" {{($validation=='required')?'required':''}} class="form-control {{$classNameInner}}" dataTypeInput="input{{$element->type}}" name="input{{$id}}" value="{{old('input'.$id)}}" /><span class="input{{$id}}_error error"></span></div>
	<?php
	break;

case 'number':
?>
<div class="form-floating mb-3 {{$className}}"><label>{{$name}}</label><input type="text" {{($validation=='required')?'required':''}} placeholder="{{$placeholder}}" class="form-control {{$classNameInner}}" dataTypeInput="{{$element->type}}" name="input{{$id}}" value="{{old('input'.$id)}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" /><span class="input{{$id}}_error error"></span></div>
<?php
break;

case 'textarea':
?>
<div class="form-floating mb-3 {{$className}}"><label>{{$name}}</label><textarea placeholder="{{$placeholder}}" {{($validation=='required')?'required':''}} class="form-control {{$classNameInner}}" dataTypeInput="{{$element->type}}" name="input{{$id}}" style="height: 100px;">{{old('input'.$id)}}</textarea><span class="input{{$id}}_error error"></span></div>
<?php
break;

	case 'text_only':
?><div class="form-floating mb-3 {{$className}}"><label>{{$name}}</label><div>{{$data}}</div><span class="input{{$id}}_error error"></span></div>
<?php
break;


case 'checkbox':
		//prd($data);
			$checkedVal = old('input'.$id);
			if(!is_array($checkedVal)){
				$checkedVal = [];
			}
		//pr($checkedVal);
		//$checkbox_data = explode(',', $data);  
		
		//labelDisplayNone col-md-12 mb-4 For I accept the Terms and Conditions
		//w-100 labelasHead light_content For Disclaimer
			if(!empty($data)){ //col-md-4 ?>
				<div class=" {{$className}}">
					<div class="form-floating checkbox_wrapper mb-3"><label>{{$placeholder}}</label><?php $checkboxtData = explode('#', $data);
					foreach($checkboxtData as $key=>$ckd1){
				#$ckd = trim($ckd);
						$ckd = explode(':', $ckd1);
						$checked = '';
						if(in_array($ckd[0], $checkedVal)){ $checked = 'checked'; } ?>
						<input type="checkbox" id="form_radio{{$id}}_{{$key}}" class="form-check-input {{$classNameInner}}" dataTypeInput="{{$element->type}}" name="input{{$id}}[]" value="{{$ckd[0]}}" {{$checked}} {{($validation=='required')?'required':''}} >
						<?php echo '<label for="form_radio'.$id.'_'.$key.'">'.$ckd[1].'</label>';
					} 
				} ?>

			</div>
		</div>
	<span class="input{{$id}}_error error"></span>
		<?php
		break;

		case 'radio':
		$checkedVal = old('input'.$id);
		$radioData = explode('#', $data);
		//pr($checkedVal);
		if(!empty($radioData)){
			?><div class="form-floating radio_wrapper w-100 mb-5 pb-4 mt-4 pt-2 {{$className}}"><label>{{$placeholder}}</label><?php 			
			foreach($radioData as $key=>$rd1){
				$rd = explode(':', $rd1);
				//$rd = trim($rd);
				$checked = '';
				if($rd[0] == $checkedVal){ $checked = 'checked'; } ?>
				<input type="radio" class="form-check-input {{$classNameInner}}" dataTypeInput="{{$element->type}}" id="form_radio{{$id}}_{{$key}}" {{($validation=='required')?'required':''}} name="input{{$id}}" value="{{$rd[0]}}" {{$checked}}>
				
				<?php echo '<label for="form_radio'.$id.'_'.$key.'">'.$rd[1].'</label>'; }?>
		</div>
	<?php }
	break;

case 'select':
	?>
	<div class="form-floating mb-3 {{$className}}"><label>{{$placeholder}}</label><select class="form-select {{$classNameInner}}" {{($validation=='required')?'required':''}} name="input{{$id}}">
		<?php
		$labelName = strtolower($placeholder);
		$searchVal = isset($search_data[$labelName])?$search_data[$labelName]:'';
		$selectVal = old('input'.$id,$searchVal);
		if(!empty($data)){
			$selectData = explode('#', $data);
			if(!empty($selectData)){
				foreach($selectData as $val1){
					$val = explode(':', $val1);
					if(!empty($val) && isset($val[0])){
						$selected = '';
						if($selectVal == $val[0]){
							$selected = 'selected';
						}
						?>
						<option value="{{(isset($val[0]))?$val[0]:''}}" {{$selected}}>{{(isset($val[1]))?$val[1]:''}}</option>
					<?php } }	} } ?>			
				</select>				
				<span class="input{{$id}}_error error"></span>
			</div>
			<?php
			break;



case 'file':
?>
<div class="w-100 file_wrapper {{$className}}"><label>{{$placeholder}}</label>
	<div class="file-input"><input type="file" placeholder="{{$placeholder}}" class="form-control {{$classNameInner}}" {{($validation=='required')?'required':''}} name="input{{$id}}" value="{{old('input'.$id)}}" /></div><span class="input{{$id}}_error error"></span></div>
	<?php
	break;

case 'url':
?>
<div class="form-floating mb-3 {{$className}}"><label>{{$placeholder}}</label><input type="url" placeholder="{{$placeholder}}" {{($validation=='required')?'required':''}} class="form-control {{$classNameInner}}" name="input{{$id}}" value="{{old('input'.$id)}}" /><span class="input{{$id}}_error error"></span></div>
<?php
break;
	

			
	case 'datepicker':
	?>
	<div class="form-floating mb-3 {{$className}}">
		<label>{{$placeholder}}</label>
		 <div class='input-group date' dataTypeInput="{{$element->type}}">
        <input type='text' {{($validation=='required')?'required':''}} placeholder="{{$placeholder}}" class="form-control {{$element->type}} {{$classNameInner}}" name="input{{$id}}" value="{{old('input'.$id)}}"/>
        <span class="input-group-addon">
        <span class="glyphicon glyphicon-calendar"></span>
        </span>
      </div>
		<span class="input{{$id}}_error error"></span>
</div>

	
	<?php 
	break;
	case 'monthpicker':
	?>
	<div class="form-floating mb-3 {{$className}}">
		<label>{{$placeholder}}</label>
		 <div class='input-group date' dataTypeInput="{{$element->type}}">
        <input type='hidden' {{($validation=='required')?'required':''}} placeholder="{{$placeholder}}" class="form-control js-monthpicker {{$classNameInner}}" name="input{{$id}}" value="{{old('input'.$id)}}"/>
        <input type="text">
        <span class="input-group-addon">
        <span class="glyphicon glyphicon-calendar"></span>
        </span>
      </div>
      <span class="input{{$id}}_error error"></span>
</div>

	<?php 
	break;
	case 'datetimepiker':
	?>
	<div class="form-floating mb-3 {{$className}}">
		<label>{{$placeholder}}</label>
		 <div class='input-group date' dataTypeInput="{{$element->type}}">
        <input type='text' {{($validation=='required')?'required':''}} placeholder="{{$placeholder}}" class="form-control {{$classNameInner}}" name="input{{$id}}" value="{{old('input'.$id)}}"/>
        <span class="input-group-addon">
        <span class="glyphicon glyphicon-calendar"></span>
        </span>
      </div>
      <span class="input{{$id}}_error error"></span>
</div>

<?php 
	break;
	case 'yearpicker':
	?>
	<div class="form-floating mb-3 {{$className}}">
		<label>{{$placeholder}}</label>
		 <div class='input-group date' dataTypeInput="{{$element->type}}">
        <input type='text' {{($validation=='required')?'required':''}} placeholder="{{$placeholder}}" class="form-control {{$classNameInner}}" name="input{{$id}}" value="{{old('input'.$id)}}"/>
        <span class="input-group-addon">
        <span class="glyphicon glyphicon-calendar"></span>
        </span>
      </div>
<span class="input{{$id}}_error error"></span>
</div>

	<?php
	break;
	case 'timepicker':
	?>
	<div class="form-floating mb-3 {{$className}}">
		<label>{{$placeholder}}</label>
		 <div class='input-group date' dataTypeInput="{{$element->type}}">
        <input type='text' {{($validation=='required')?'required':''}} placeholder="{{$placeholder}}" class="form-control {{$classNameInner}}" name="input{{$id}}" value="{{old('input'.$id)}}"/>
        <span class="input-group-addon">
        <span class="glyphicon glyphicon-calendar"></span>
        </span>
      </div>
<span class="input{{$id}}_error error"></span>
</div>

	<?php
	break;	
	default:
		# code...
	break;
}

/*echo '<?php echo form_error(\'id'.$id.'\'); ?>';*/
?>