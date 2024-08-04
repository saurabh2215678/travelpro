<?php
$element_id = 0;
if(isset($id) && !empty($id)) {
    $element_id = (isset($element->id))?$element->id:0;
}
$is_static = (isset($element->is_static))?$element->is_static:0;
$label = (isset($element->label))?$element->label:'';
$type = (isset($element->type))?$element->type:'';
$validation = (isset($element->validation))?$element->validation:'';
$data = (isset($element->data))?$element->data:'';
$defaultValue = (isset($element->defaultValue))?$element->defaultValue:'';
$is_display = (isset($element->is_display))?$element->is_display:'';
$position = (isset($element->position))?$element->position:'999';
$className = (isset($element->className))?$element->className:'';
$is_hide = (isset($element->is_hide))?$element->is_hide:'0';
$classNameInner = (isset($element->classNameInner))?$element->classNameInner:'';
$placeHolder = (isset($element->placeHolder))?$element->placeHolder:'';
$enquiryMapping = (isset($element->enquiryMapping))?$element->enquiryMapping:'';

$readonly = '';
$disabled = '';
if($is_static == '1'){
    $readonly = 'readonly';
    $disabled = 'disabled';
}
$showRemoveBtn = (isset($showRemoveBtn))?$showRemoveBtn:false;
$enquiry_mapping = config('custom.enquiry_mapping');
// prd($enquiry_mapping);
//echo $form_elements + 1;
?>



<div class="rowBox form_sec">

    <input type="hidden" name="form_element_ids[]" value="{{$element_id}}">
    <input type="hidden" name="is_static[]" value="{{$is_static}}">

     <input type="hidden" name="is_display[]" value="{{$is_display}}" class="is_display">
      <input type="hidden" name="is_hide[]" value="{{$is_hide}}" class="is_hide">


    
    <div class="col-md-3">
        <div class="form-group{{ $errors->has('bottom_content') ? ' has-error' : '' }}">
            <label for="bottom_content" class="control-label">Field Label:</label>
            <input type="text" name="field_label[]" value="{{$label}}" id="field_input_<?php echo $ii;?>" class="form-control" {{$readonly}} >

            @include('snippets.errors_first', ['param' => 'field_label'])
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group{{ $errors->has('placeHolder') ? ' has-error' : '' }}">
            <label for="placeHolder" class="control-label">Placeholder:</label>
            <input type="text" name="placeHolder[]" value="{{$placeHolder}}" class="form-control" >

            @include('snippets.errors_first', ['param' => 'placeHolder'])
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group{{ $errors->has('bottom_content') ? ' has-error' : '' }}">
            <label for="bottom_content" class="control-label">Field Type:</label>

            <select class="form-control field_type" name="field_type[]" {{$disabled}} >
                <?php
                
                if(!empty($form_attribute)){
                    foreach($form_attribute as $fa){
                        $selected = '';
                        if($fa->type == $type){


                            $selected = 'selected';
                        }
                        ?>
                        <option value="{{$fa->type}}" {{$selected}}>{{$fa->label}} </option>
                        <?php
                    }
                }
                ?>
            </select>
            <?php
            if($is_static == '1'){
                ?>
                <input type="hidden" name="field_type[]" value="{{$type}}">
                <input type="hidden" name="enquiryMapping[]" value="{{$enquiryMapping}}">
                <?php
            }
            ?>
            @include('snippets.errors_first', ['param' => 'field_type'])
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group{{ $errors->has('bottom_content') ? ' has-error' : '' }}">
            <label for="bottom_content" class="control-label">Validations:</label>
            <select class="form-control" name="validations[]">
                <option value="">Select</option>
                <option value="required" <?php if($validation == 'required'){ echo 'selected';} ?> >Required</option>
            </select>
            @include('snippets.errors_first', ['param' => 'validations['.$ii.']'])
        </div>
    </div>


    <div class="col-md-3">
        <div class="form-group{{ $errors->has('defaultValue') ? ' has-error' : '' }}">
            <label for="defaultValue" class="control-label">Default Value:</label>
            <input type="text" name="defaultValue[]" class="form-control" value="{{$defaultValue}}">
            @include('snippets.errors_first', ['param' => 'data['.$ii.']'])
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group{{ $errors->has('is_display') ? ' has-error' : '' }}">
    
            <label for="is_display" class="control-label">Display in Dashboard:</label>
            <input type="checkbox" style="display:block" name="is_display1[]" class="check_lg is_display"  value="1" <?php if($is_display == '1' ){ echo 'checked';} ?>>
            @include('snippets.errors_first', ['param' => 'validations['.$ii.']'])
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group{{ $errors->has('is_hide') ? ' has-error' : '' }}">
    
            <label for="is_hide" class="control-label">Hide from Form:</label>
            <input type="checkbox" style="display:block" name="is_hide1[]" class="check_lg is_hide"  value="1" <?php if($is_hide == '1'){ echo 'checked';} ?>>
            @include('snippets.errors_first', ['param' => 'validations['.$ii.']'])
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group{{ $errors->has('position') ? ' has-error' : '' }}">
            <label for="position" class="control-label">Position:</label>
            <input type="number" class="form-control"  name="position[]" value="{{$position}}" >
            @include('snippets.errors_first', ['param' => 'position['.$ii.']'])
        </div>
    </div>

    <div class="col-md-6 dataBox">
        <div class="form-group{{ $errors->has('bottom_content') ? ' has-error' : '' }}">
            <label for="bottom_content" class="control-label">Options:</label>
            <input type="text" name="data[]" class="form-control" value="{{$data}}">
            @include('snippets.errors_first', ['param' => 'data['.$ii.']'])
        </div>
    </div>


    <div class="col-md-3 ">
        <div class="form-group{{ $errors->has('className') ? ' has-error' : '' }}">
            <label for="className" class="control-label">Box Class:</label>
            <input type="text" name="className[]" class="form-control" value="{{$className}}">
            @include('snippets.errors_first', ['param' => 'className['.$ii.']'])
        </div>
    </div>

    <div class="col-md-3 ">
        <div class="form-group{{ $errors->has('classNameInner') ? ' has-error' : '' }}">
            <label for="className" class="control-label">Inner Class:</label>
            <input type="text" name="classNameInner[]" class="form-control" value="{{$classNameInner}}">
            @include('snippets.errors_first', ['param' => 'classNameInner['.$ii.']'])
        </div>
    </div>

    <div class="col-md-3 ">
        <div class="form-group{{ $errors->has('enquiryMapping') ? ' has-error' : '' }}">
            <label for="className" class="control-label">Map to Enquiry Field:</label>
            <select class="form-control field_type" name="enquiryMapping[]" {{$disabled}} >
                <option value="">Select</option>
                <?php
                if(!empty($enquiry_mapping)) {
                    foreach($enquiry_mapping as $key => $val) {
                        $selected = '';
                        if($key == $enquiryMapping) {
                            $selected = 'selected';
                        }
                ?>
                <option value="{{$key}}" {{$selected}}>{{$val}} </option>
                <?php
                    }
                }
                ?>
            </select>
            @include('snippets.errors_first', ['param' => 'enquiryMapping['.$ii.']'])
        </div>
    </div>
    
    <div class="remove_head_row_main">
        <?php if($showRemoveBtn){ ?>
        <a href="javascript:void(0)" class="remove_head_row">X</a>
        <?php } ?>
    </div>

</div>
