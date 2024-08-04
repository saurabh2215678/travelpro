@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <link href="{{asset('')}}css/palette-color-picker.css" rel="stylesheet" type="text/css" />
    <?php
    $id = (isset($record->id))?$record->id:'';   
    $name = (isset($record->name))?$record->name:'';    
    $status = (isset($record->status))?$record->status:1;
    ?>
<div class="row top_title_admin enquiries-top" style="padding-left:0; padding-right:0;">
<div class="col-md-12">
    <?php 
          $active_menu = "enquiries_master_group";
        ?>
        @include('admin.includes.enquirymastermenu')
        </div>        
</div>
<div class="row top_title_admin enquiries-top" style="padding-left:0; padding-right:0;">

        <div class="col-md-6">
 <h5>{{ $page_heading }} <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
 </div>
        <div class="col-md-6">
        <a href="{{ url($back_url)}}" class="btn_admin btn btn-success btn-sm" style='float: right;'>Back</a><?php } ?></h5>
        </div>
</div>
<div class="row top_title_admin enquiries-top centersec" style="min-height: auto; padding-left:0; padding-right:0;">

<div class="col-md-12">
        <div class="bgcolor">

            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>

            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label required">Name of the Enquiries Master Group data:</label>

                            <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" />

                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>
                
         
                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-6">
                        &nbsp;&nbsp;
                        Show: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                        &nbsp;
                        Hide: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                        @include('snippets.errors_first', ['param' => 'status'])
                   </div>

                </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            
                            <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
                            <button type="submit" class="btn btn-success" title="Submit"><i class="fa fa-save"></i> Submit</button>
                        </div>
                    </div>
              
                    </div>
                    </div>
              
              </div>

                </div>
            </form>
            <div class="clearfix"></div>
        </div>

@slot('bottomBlock')
@endslot 
@endcomponent