    @component('admin.layouts.main')
    @slot('title')
    Admin - Crons - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot
    <div class="top_title_admin">
        <div class="title">
            <h2>Crons</h2>
        </div>
    </div>
    <div class="centersec">
        @include('snippets.errors')
        @include('snippets.flash')
        <?php if(!empty($records) && $records->count() > 0){ ?>
        <div class="table-responsive bgcolor">
            <table class="table table-striped table-bordered table-hover">
                <tr>
                    <th class="">Title</th>
                    <th class="">URL</th>    
                    <th class="">Frequency</th>
                </tr>
                <?php foreach ($records as $record) { ?>
                <tr>
                    <td>{{$record->title}}</td>
                    <td>{{$record->url}}</td>
                    <td>{{$record->frequency}}</td>
                </tr>
                <?php } ?>
            </table>
        </div>
        <?php } else{ ?>
        <div class="alert alert-warning">There are no Records at the present.</div>
        <?php } ?>
    </div>
    @endcomponent