@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .more-options-box .choicebox {max-width: 100px;width: auto;padding-right: 0;}
            .more-options-box .choicebox a {right: -5px;}
            .more-options-box .choicebox input.form-control {padding-right: 15px;padding-left: 5px;}
        </style>
    @endslot

<?php
// $BackUrl = (request()->has('back_url'))?request()->input('back_url'):'';
// $routeName = CustomHelper::getAdminRouteName();

$id = (isset($price_category->id))?$price_category->id:'';
$name = (isset($price_category->name))?$price_category->name:'';
$display_title = (isset($price_category->display_title))?$price_category->display_title:'';
$status = (isset($price_category->status))?$price_category->status:0;

if(empty($id)){
    $status = 1;
}

$categoryElements = (!empty($price_category) && ($price_category->priceCategoryElements->count() > 0))?$price_category->priceCategoryElements:'';
?>

<div class="top_title_admin">
    <div class="title">
    <h2>{{ $page_heading }}</h2>
    </div>
</div>

<div class="centersec d-flex">
    <div class="bgcolor w-100">
        @include('snippets.errors')
        @include('snippets.flash')

        <div class="ajax_msg"></div>
		<div class="bgcolor">
        <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}

            <div class="form-group col-md-4{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="control-label required">Name:</label>
                <input type="text" name="name" id="name" class="form-control" required value="{{ old('name',$name) }}" autocomplete="off">  
                @include('snippets.errors_first', ['param' => 'name'])
            </div>
            
            <div class="form-group col-md-4{{ $errors->has('display_title') ? ' has-error' : '' }}">
                <label for="display_title" class="control-label required">Display Title:</label>
                <input type="text" name="display_title" id="display_title" class="form-control" required value="{{ old('display_title',$display_title) }}" autocomplete="off">  
                @include('snippets.errors_first', ['param' => 'display_title'])
            </div>

            <div class="form-group col-sm-2">
                <a href="javascript:void(0);" class="btn btn-primary addelement btn_admin mt20" ><i aria-hidden="true" class="fa fa-plus"></i> Add Element</a>
            </div>

            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                <label class="control-label">Status:</label>
                &nbsp;&nbsp;
                Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?>>
                &nbsp;
                Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >
                @include('snippets.errors_first', ['param' => 'status'])
            </div>
			
			<div class="clearfix"></div>
                <div class="elements-box">
                <div class="row_position">
                <?php
                if(!empty($categoryElements)) {
                    foreach ($categoryElements as $elKey => $element) {
                ?>
                <div class="col-sm-11 pcBox">
                    <div class="col-sm-12 mformsec">
                        <?php if(empty($price_category_used)){ ?>
                        <a class="crossbox" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        <?php } ?>
                        <div class="row form-group">
                            <div class="col-sm-4">
                                <label for="price_label"><span class="rows handle"><span style="cursor:pointer; position: absolute;display: inline-block;left: 0;right: 0;"><i class="fa fa-arrows-v" style="font-size:15px;color: #00b2a7;"></span></i> </span> Price Label <span class="required">*</span></label>
                                <input type="text" name="price_label[{{$element->id}}]" required class="form-control price_label error_remove" value="{{ $element->price_label }}">
                                <span class="validation_error"></span>
                                <input type="hidden" name="old_element[]" value="{{$element->id}}">
                            </div>
                            <div class="col-sm-4">
                                <label for="input_label">Input Label <span class="required">*</span></label>
                                <input type="text" name="input_label[{{$element->id}}]" required class="form-control input_label error_remove" value="{{ $element->input_label }}">
                                <span class="validation_error"></span>
                            </div>
                            <div class="col-sm-4">
                                <label for="input_type">Input Type</label>
                                <select class="form-control" onchange="inputOption(this)" name="input_type[{{$element->id}}]">
                                    <option value="select" <?php echo ($element->input_type == "select") ? "selected":""; ?>>Dropdown Select</option>
                                    <option value="input" <?php echo ($element->input_type == "input") ? "selected":""; ?>>Input Box</option>
                                </select>
                            </div>
                        </div>

                        <div class="row more-options-box" <?php echo ($element->input_type == "input") ? "style='display:none;'":""; ?>>
                            <div class="col-sm-12">
                                <label for="min_value">Options 
                                    <?php if(empty($price_category_used)){ ?>
                                    <a class="btn btn-primary btn-sm addMoreOption" href="javascript:void(0);">Add more option</a>
                                    <?php } ?>
                                </label>
                            </div>
                            <?php
                            $input_choice_option = "";
                            if(!empty($element->input_choices)){
                                $input_choice_option = json_decode($element->input_choices,true);
                            }
                            if(!empty($input_choice_option)){
                                foreach($input_choice_option as $option){
                                    ?>
                            <div class="col-sm-1 choicebox">
                                <input type="number" name="input_choices[{{$element->id}}][]" required class="form-control" value="{{ $option }}"> 
                                <?php if(empty($price_category_used)){ ?>
                                <a class="btn btn-secondary removeOptionCross" data-toggle="tooltip" data-placement="right" title="Remove Choice" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-remove"></i></a>
                                <?php } ?>
                            </div>
                            <?php } } else{ ?>
                                 <div class="col-sm-1 choicebox">
                                    <input type="number" name="input_choices[{{$element->id}}][]" required class="form-control" > 
                                    <?php if(empty($price_category_used)){ ?>
                                    <a class="btn btn-secondary removeOptionCross" data-toggle="tooltip" data-placement="right" title="Remove Choice" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-remove"></i></a>
                                    <?php } ?>
                                </div>
                           <?php  } ?>
                        </div>
                   </div>
                </div>
                <?php } } ?>
            </div>
        </div>
            <div class="form-group col-md-12">
                <button class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                <a href="{{ route('admin.price_category.index') }}" class="btn_admin2" title="Cancel">Cancel</a>
            </div>
        </form>
		</div>
    </div>
</div>

@slot('bottomBlock')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    var elementLength = <?php echo (!empty($categoryElements))?count($categoryElements):0; ?>;
    function addElement(){
        // var elementLength = $('.elements-box .pcBox').length;
        elementLength = elementLength+1;

        myForm = `<div class="col-sm-11 pcBox">
   <div class="col-sm-12 mformsec">
      <a class="crossbox" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
      <div class="row form-group">
         <div class="col-sm-4"><label for="price_label"><span class="rows"><span style="cursor:pointer; position: absolute;display: inline-block;left: 0;right: 0;""><i class="fa fa-arrows-v" style="font-size:15px;color: #00b2a7;"></span></i> </span> Price Label <span class="required">*</span></label><input type="text" name="price_label[${elementLength}]" required data-id="" class="form-control price_label error_remove" value=""><span class="validation_error"></span></div>
         <div class="col-sm-4"><label for="input_label">Input Label <span class="required">*</span></label><input type="text" name="input_label[${elementLength}]" required data-id="" class="form-control input_label error_remove" value=""><span  class="validation_error"></span></div>
         <div class="col-sm-4">
            <label for="input_type">Input Type</label>
            <select class="form-control" onchange="inputOption(this)" name="input_type[${elementLength}]">
               <option value="select">Dropdown Select</option>
               <option value="input">Input Box</option>
            </select>
         </div>
      </div>
      <div class="row more-options-box">
         <div class="col-sm-12"><label for="min_value">Options <a class="btn btn-primary btn-sm addMoreOption" href="javascript:void(0);">Add more option</a></label></div>
         <div class="col-sm-1 choicebox"><input type="number" name="input_choices[${elementLength}][]" required class="form-control" value=""> <a class="btn btn-secondary removeOptionCross" data-toggle="tooltip" data-placement="right" title="Remove Choice" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-remove"></i></a></div>
      </div>
   </div>
 </div>`;
        /* $(myForm).appendTo('.elements-box'); */
        $(myForm).appendTo('.row_position');
    }
    <?php if(empty($categoryElements)){ ?>
        addElement();
    <?php } ?>
    $('.addelement').click(function(){
        addElement();

        /*var str = $('.pcBox:nth-last-child(2)').find('.choicebox').children('input').attr('name')
        var lastElementValue = parseInt(str.match(/(\d+)/)[1])

        setTimeout(()=>{
        $('.pcBox:nth-last-child(1)').find('.choicebox').children('input').attr('name', `input_choices[${lastElementValue + 1}][]`)
        },10);*/
       
        
    });
    $(document).click(function(e){
        var element = $(e.target);
        if(element.hasClass("crossbox")){
            element.closest('.col-sm-11').remove();
            console.log(element)
        }
        if(element.hasClass("addMoreOption")){            
            var mainElement = $(element.closest('.col-sm-12').siblings('.choicebox')[0]).clone()
            mainElement.find('input').val('')
            $(mainElement).appendTo(element.closest('.row'))
            // console.log(mainElement)
        }
        if(element.hasClass('removeOptionCross')){
            element.closest('.choicebox').remove();
        }
    });

    // function inputOption(input_type) {
    //     if($(input_type).val() == "input") {
    //         $(input_type).parent().parent().siblings('.more-options-box').hide();
    //         $.each($(input_type).parent().parent().siblings('.more-options-box').find('.form-control'),function(i, row){
    //             //console.log('row=',row);
    //             if($(this).val()==''){
    //                 $(this).parent().hide();
    //             }
    //         });
    //     } else {
    //         $(input_type).parent().parent().siblings('.more-options-box').show();
    //     }
    // }

    function inputOption(input_type) {
        if($(input_type).val() == "input") {
            $(input_type).closest('.pcBox').find('.more-options-box').find('.choicebox input').prop('required', false);
            $(input_type).closest('.pcBox').find('.more-options-box').hide();
        } else {
            $(input_type).closest('.pcBox').find('.more-options-box').find('.choicebox input').prop('required', true);
            $(input_type).closest('.pcBox').find('.more-options-box').show();
        }
    }

    $( ".row_position" ).sortable({
        delay: 150,
        handle: ".handle",
    });  

</script>

@endslot

@endcomponent