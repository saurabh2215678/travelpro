@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
       
    @endslot

<?php

$BackUrl = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();
$type = (request('type'))?request('type'):'';

$id = (isset($download->id))?$download->id:'';
$title = (isset($download->title))?$download->title:'';
$brief = (isset($download->brief))?$download->brief:'';
$image = (isset($download->image))?$download->image:'';
$documents = (isset($download->documents))?$download->documents:'';
$status = (isset($download->status))?$download->status:1;

$storage = Storage::disk('public');
$path = 'downloads/';
$documents_path = 'downloads/documents/';

$old_image = $image;
$old_documents = $documents;
$image_req = '';
$link_req = '';
?>

<div class="top_title_admin">
    <div class="title">
    <h2>{{ $page_heading }}</h2>
    </div>
 
    </div>

    <div class="centersec">
	<div class="bgcolor">
            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>
		
            <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}
                <div class="form-group col-md-6{{ $errors->has('title') ? ' has-error' : '' }}">
                    <label for="title" class="control-label required">Title:</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title',$title) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'title'])
                </div>

                <div class="form-group col-md-12{{ $errors->has('brief') ? ' has-error' : '' }}">
                    <label for="brief" class="control-label required">Brief :</label>

                    <textarea name="brief" id="brief" class="form-control" ><?php echo old('brief', $brief); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'brief'])
                </div>

				<div class="clearfix"></div>
            
                <?php
                $image_required = $image_req;
                if($id > 0){
                    $image_required = '';
                }
                ?>
        
                <div class="col-md-12">
                     <div class="col-md-6">
                     	<div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                     		<label for="sort_order" class="control-label {{ $image_required }}">Image:</label>
                     		<input type="file" id="image" name="image"/>
                     		@include('snippets.errors_first', ['param' => 'image'])
                     	</div>
                     	<?php
                     	if(!empty($image)){
                     		if($storage->exists($path.$image))
                     		{
                     			?>
                     			<div class="col-md-2 image_box">
                     				<img src="{{ url('storage/'.$path.'thumb/'.$image) }}" style="width: 100px;"><br>
                     				<a href="javascript:void(0)" data-id="{{ $id }}" data='image' class="del_image">Delete</a>
                     			</div>
                     			<?php        
                     		}
                     		?>
                     		<?php
                     	}
                     	?>
                     	<input type="hidden" name="old_image" value="{{ $old_image }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('documents') ? ' has-error' : '' }}">
                        <label for="sort_order" class="control-label ">Document:</label>

                        <input type="file" id="documents" name="documents"/>

                        @include('snippets.errors_first', ['param' => 'documents'])
                    </div>
                    <table class="table">
                        <tr>
                            <?php
                            if(!empty($documents)){
                                    if($storage->exists($documents_path.$documents))
                                    {
                                        ?>
                                        <div class="col-md-2 pdf_box">
                                            <a target="_blank" href="{{url('/storage/'.$documents_path.$documents)}}">
                                            <img src="{{ url('public/images/documents.jpg') }}" style="width: 100px; height:  100px;"><br>
                                            </a>
                                            <a href="javascript:void(0)" data-id="{{ $id }}" class="del_documents">Delete</a>
                                        </div>
                                        <?php        
                                    }
                                ?>
                                <?php
                            }
                            ?>
                        </tr>
                    </table>

                    <input type="hidden" name="old_documents" value="{{ $old_documents }}">

                </div>

                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                    <label class="control-label">Status:</label>
                    &nbsp;&nbsp;
                    Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                    &nbsp;
                    Deactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                    @include('snippets.errors_first', ['param' => 'status'])
                </div>
				
				 <div class="clearfix"></div>
                <div class="form-group col-md-12">
                    <button class="btn btn-success"><i class="fa fa-save"></i> Save</button>

                    <a href="{{ route('admin.downloads.index') }}" class="btn_admin2" title="Cancel">Cancel</a>
                </div>
				<br/><br/>

            </form>
			</div>
        </div>       
 


@slot('bottomBlock')

<script type="text/javascript">

$(document).ready(function(){
    $(".del_image").click(function(){
        var current_sel = $(this);
        var image_id = $(this).attr('data-id');
        var type = $(this).attr('data');
        //alert(type); return false;

        conf = confirm("Are you sure to Delete this Image?");
        if(conf){
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route($routeName.'.downloads.ajax_delete_image') }}",
                type: "POST",
                data: {image_id , type},
                dataType:"JSON",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                beforeSend:function(){
                 $(".ajax_msg").html("");
             },
             success: function(resp){
                if(resp.success){
                    $(".ajax_msg").html(resp.msg);
                    current_sel.parent('.image_box').remove();
                }
                else{
                    $(".ajax_msg").html(resp.msg);
                }
            }
        });
        }

    });
$(".del_documents").click(function(){

var current_sel = $(this);

var id = $(this).attr('data-id');

conf = confirm("Are you sure to Delete this Documents?");

if(conf){

    var _token = '{{ csrf_token() }}';
    var type = '{{$type}}';

    $.ajax({
        url: "{{ route($routeName.'.downloads.ajax_delete_documents') }}",
        type: "POST",
        data: {id:id, type:type},
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        beforeSend:function(){
           $(".ajax_msg").html("");
        },
        success: function(resp){
            if(resp.success){
                $(".ajax_msg").html(resp.msg);
                current_sel.parent('.pdf_box').remove();
            }
            else{
                $(".ajax_msg").html(resp.msg);
            }
            
        }
    });
}

});


});

</script>

@endslot

@endcomponent