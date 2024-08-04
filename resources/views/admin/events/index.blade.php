@component('admin.layouts.main')

    @slot('title')
        Admin - Manage Events - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php 
    $back_url=CustomHelper::BackUrl(); 
    $routeName = CustomHelper::getAdminRouteName();
    ?>
    <div class="row">

        <div class="col-md-12">

            <h2>Manage Events

                @if(CustomHelper::checkPermission('events','add'))
                <a href="{{ route($routeName.'.events.add').'?back_url='.$back_url }}" class="btn btn-sm btn-success pull-right"><i class="fa fa-plus"></i> Add Event</a>
                @endif

            </h2>

            @include('snippets.errors')
            @include('snippets.flash')

            <?php
            if(!empty($events) && count($events) > 0){
                ?>

                <div class="table-responsive">

                    <table class="table table-striped">
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Featured</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        <?php
                        foreach($events as $event){
                            $brief = CustomHelper::wordsLimit($event->brief,35);
                            ?>
                        
                            <tr>
                                <td><?php echo $event->title; ?></td>
                                <td>{{ strip_tags($brief) }}</td>
                                <td>{{ CustomHelper::getStatusStr($event->featured) }}</td>
                                <td>{{ CustomHelper::getStatusStr($event->status) }}</td>

                                <td>
                                    @if(CustomHelper::checkPermission('events','edit'))
                                    <a href="{{ route($routeName.'.events.edit', $event->id.'?back_url='.$back_url) }}" title="Edit Blog"><i class="fas fa-edit"></i></a>
                                    @endif

                                    @if(CustomHelper::checkPermission('events','delete'))
                                    <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$event->id}}" title="Delete Blog"><i class="fas fa-trash-alt"></i></i></a>
                                
                                    <form method="POST" action="{{ route($routeName.'.events.delete', $event->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this Event?');" id="delete-form-{{$event->id}}">
                                        {{ csrf_field() }}
                                        {{ method_field('POST') }}
                                        <input type="hidden" name="banner_id" value="<?php echo $event->id; ?>">

                                    </form>
                                    @endif
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
                 {{ $events->appends(request()->query())->links() }}

            
            <?php
        }
        else{
            ?>
            <div class="alert alert-warning">No Events found.</div>
            <?php
        }
            ?>

        </div>

    </div>

@slot('bottomBlock')
<script type="text/javaScript">
    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });
</script>
@endslot

@endcomponent

