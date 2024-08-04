@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')

    <link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}"/ >
    @endslot


     <?php
    $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    $routeName = CustomHelper::getAdminRouteName();

    if(empty($back_url)){
        $back_url = $routeName.'/accommodations_facilities';
    }

    //pr($blog->toArray());
    $routeName = CustomHelper::getAdminRouteName();

    $id = (isset($facility_query->id))?$facility_query->id:'';
    $title = (isset($facility_query->title))?$facility_query->title:'';
    $status = (isset($facility_query->status))?$facility_query->status:1; 
    $sort_order = (isset($facility_query->sort_order))?$facility_query->sort_order:0;

    ?>

    
        <?php
        $active_menu = "facility";
        ?>
        @include('admin.includes.accommodationmenu')
        <div class="top_title_admin">
            <div class="title">
                <h2>{{ $page_heading }}</h2>
            </div>
            <div class="add_button">
                <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>

                <a href="{{ url('admin/accommodations/facility')}}" class="btn_admin" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                 Back</a><?php } ?>
             </div>
         </div>
         <div class="centersec">
        <div class="bgcolor p10">
            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>

            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="control-label required">Title:</label>

                            <input type="text" id="title" class="form-control" name="title" value="{{ old('title', $title) }}" />

                            @include('snippets.errors_first', ['param' => 'title'])
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                            <label for="sort_order" class="control-label">Sort Order:
                            </label>

                            <input type="number" id="sort_order" class="form-control" name="sort_order" value="{{ old('sort_order', $sort_order) }}" />

                            @include('snippets.errors_first', ['param' => 'sort_order'])
                        </div>
                    </div>
                
                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                            <label class="control-label">Status:</label>
                            &nbsp;&nbsp;
                            Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                            &nbsp;
                            Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                            @include('snippets.errors_first', ['param' => 'status'])
                        </div>

                
                
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p></p>
                                    <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
                                    <button type="submit" class="btn btn-success" title="Create this new accommodation facility"><i class="fa fa-save"></i> Submit</button>
                                </div>
                            </div>
                        </div>
              
            </form>
            <div class="clearfix"></div>
        </div>
    </div>


@slot('bottomBlock')

@endslot

@endcomponent