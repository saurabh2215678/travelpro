    	@component('admin.layouts.main')

        @slot('title')
        Admin - {{$page_heading}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
        @endslot
        <?php
        // $BackUrl = CustomHelper::BackUrl();
        $routeName = 'admin';
        $old_name = (request()->has('name'))?request()->name:''; 
        ?>    
        <div class="top_title_admin">
            <div class="title">
               <h2>Manage Sitemap Generate</h2>
           </div>
           <div class="add_button">

           </div>
       </div>
       <div class="centersec">
        @include('snippets.errors')
        @include('snippets.flash')
      <?php /*  <div class="bgcolor">
        <label>Home Site Map</label>
            <div class="table-responsive">
                <a href="{{url($routeName.'/settings/sitemap/home')}}" class="btn_admin2">Generate Home Site Map</a>

            </div>
        </div>
        <div class="bgcolor">
        <label>Packages Site Map</label>
            <div class="table-responsive">
                <a href="{{url($routeName.'/settings/sitemap/packages')}}" class="btn_admin2">Generate Packages Site Map</a>

            </div>
        </div>
        <div class="bgcolor">
        <label>Hotels Site Map</label>
            <div class="table-responsive">
                <a href="{{url($routeName.'/settings/sitemap/hotels')}}" class="btn_admin2">Generate Hotels Site Map</a>

            </div>
        </div>
        <div class="bgcolor">
        <label>Blogs Site Map</label>
            <div class="table-responsive">
                <a href="{{url($routeName.'/settings/sitemap/blogs')}}" class="btn_admin2">Generate Blogs Site Map</a>
                
            </div>
        </div>

        */?>
        <?php
        if(!empty($sitemap_data) && $sitemap_data->count() > 0){
            ?>
            <div class="table-responsive bgcolor">
                <table class="table table-striped table-bordered table-hover" id="sitemap_form">
                    <tr>
                        <th class="">Name</th>
                        <th class="">Last Update</th>    
                        <th class="">Comment</th>    
                        <th class="">Action</th>
                    </tr>
                    <?php
                    foreach ($sitemap_data as $sitemap){
                        ?>
                        <tr>
                            <td>{{$sitemap->name}}</td>
                            <td>{{ CustomHelper::DateFormat($sitemap->updated_at, 'd/m/Y h:i A') }}</td>   
                            <td>{{$sitemap->comment}}</td>
                            <td>

                                <a href="{{url($routeName.'/settings/sitemap/'.$sitemap->slug)}}" class="btn_admin2" id="site_map">Generate</a> 
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            <?php
        }
        else{
            ?>
            <div class="alert alert-warning">There are no Records at the present.</div>
            <?php
        }
        ?>
    </div>

@endcomponent
<script>
$(".btn_admin2").click(function() {
    $(this).html(
        'Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>'
        );
    $(this).attr("disabled", true);
});
</script>