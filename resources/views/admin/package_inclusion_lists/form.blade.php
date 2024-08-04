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
        $back_url = $routeName.'/package_inclusion_lists';
    }
    //pr($blog->toArray());
    // $routeName = CustomHelper::getAdminRouteName();
    $id = (isset($package_list->id))?$package_list->id:'';
    $title = (isset($package_list->title))?$package_list->title:'';
    $status = (isset($package_list->status))?$package_list->status:1; 
    $sort_order = (isset($package_list->sort_order))?$package_list->sort_order:0;
    $image = (isset($package_list->image))?$package_list->image:'';
    $slug = (isset($package_list->slug))?$package_list->slug:'';

    // $storage = Storage::disk('public');
    $path = 'inclusion/';
    $old_image = $image;
    $image_req = '';
    $link_req = '';
    ?>
    <div class="centersec">
      <?php
      $active_menu = "packages.lists";
      ?>
      @include('admin.includes.packagemenu')

      <div class="top_title_admin tab-title">
        <div class="title">
            <h2>{{ $page_heading }}</h2>
        </div>
        <div class="add_button">
            <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
            
            <a href="{{ url('admin/'.$segment.'/lists')}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
               Back</a><?php } ?>
           </div>
       </div>
        <div class="bgcolor p10">

            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>
            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row">
                     <?php
                    if(!empty($package_query->id)){
                        ?>
                        <div style="display: none;" class="form-group  col-md-4{{ $errors->has('slug') ? ' has-error' : '' }}">
                            <label for="slug" class="control-label">Slug:</label>

                            <input type="text" name="slug" value="{{ old('slug', $slug) }}" id="slug" class="form-control"  maxlength="255" readonly />
                            @include('snippets.errors_first', ['param' => 'slug'])
                        </div>
                    <?php } ?>
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="control-label required">Title:</label>

                            <input type="text" id="title" class="form-control" name="title" value="{{ old('title', $title) }}" />

                            @include('snippets.errors_first', ['param' => 'title'])
                        </div>
                    </div>

                    <div style="display:none;" class="col-md-4">
                        <div class="form-group{{ $errors->has('identifier') ? ' has-error' : '' }}">
                            <label for="identifier" class="control-label">{{($segment == 'activity')?'Activity':'Package'}} Type Identifier :</label>
                            <select class="form-control" name="identifier" id="identifier">
                             <?php //<option value="">--Select--</option> ?>
                             <option value="package" {{($identifier=='package')?'selected':''}}>Package</option>
                             <option value="activity" {{($identifier=='activity')?'selected':''}}>Activity</option>
                         </select>
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


                    <?php
                $image_required = $image_req;
                if($id > 0){
                    $image_required = '';
                }
                ?>
        
                <div class="image-choose">
                     <div class="col-md-6">
                        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                            <label for="sort_order" class="control-label {{ $image_required }}">Image:</label>
                            <input type="file" id="image" name="image"/>
                            @include('snippets.errors_first', ['param' => 'image'])
                        </div>
                        <?php
                        if(!empty($image)){
                                ?>
                                <div class="col-md-2 image_box">
                                    <img src="{{ url('storage/'.$path.'thumb/'.$image) }}" style="width: 100px;"><br>
                                    <!-- <a href="javascript:void(0)" data-id="{{ $id }}" data='image' class="del_image">Delete</a> -->
                                </div>
                            <?php
                        }
                        ?>
                        <input type="hidden" name="old_image" value="{{ $old_image }}">
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
                                    <button type="submit" class="btn btn-success" title="Create this new package_inclusion_lists"><i class="fa fa-save"></i> Submit</button>
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