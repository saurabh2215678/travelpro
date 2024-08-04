@component('admin.layouts.main')

    @slot('title')
        Admin - Accommodation Room(Accommodation) - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
        $BackUrl = CustomHelper::BackUrl();
        $routeName = CustomHelper::getAdminRouteName();
    ?>
    <div class="top_title_admin">
    <div class="title">
    <h2>Accommodation Room</h2>
    </div>
    <div class="add_button">
    <a href="{{ route('admin.accommodations.room_add', $accommodation_id) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Accommodation Room</a>
    </div>
    </div>
    

            @include('snippets.errors')
            @include('snippets.flash')

            <?php if(!($accommodation_rooms->isEmpty())){?>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">

                        <tr>
                            <th width="20%" class="text-center">Hotel</th>
                            <th width="20%" class="text-center">Room Type</th>
                            <th>Qty</th>
                            <th width="20%" class="text-center">Status</th>
                            <th width="20%" class="text-center">Date Created</th>
                            <th width="15%" class="text-center">Action</th>
                        </tr>

                        <?php
                        foreach($accommodation_rooms as $room){
                        ?>
                            <tr>
                                <td>{{ $room->roomAccommodation->name }}</td>
                                <td>{{ $room->roomAccommodationType->title }}</td>
                                <td>{{ $room->quantity }}</td>
                                <td>{{ CustomHelper::getStatusStr($room->status) }}</td>
                                <td>{{ CustomHelper::DateFormat($room->created_at, 'd/m/Y') }}</td>

                                <td>

                                    <a href="{{route($routeName.'.accommodations.room_view',[$room['id'], 'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>

                                    <a href="{{ route($routeName.'.accommodations.room_edit', ['id'=>$accommodation_id,'room_id'=>$room->id, 'back_url'=>$BackUrl]) }}" class="" title="Edit"><i class="fas fa-edit"></i> </a>

                                    <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete"><i class="fas fa-trash-alt"></i></a>

                                    <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.accommodations.room_delete', ['id'=>$accommodation_id,'room_id'=>$room->id, 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this?');" class="delForm">
                                    {{ csrf_field() }}
                                    </form>
                                </td>

                            </tr>
                        <?php } ?>
                    </table>
                </div>
                {{ $accommodation_rooms->appends(request()->query())->links('vendor.pagination.default') }}
           <?php
       }else{
        ?>
        <div class="alert alert-warning">There are no Accommodation Room Block(Accommodation) at the present.</div>
        <?php } ?>
        </div>

    </div>

    @slot('bottomBlock')

    <script type="text/javascript">
        $(document).on("click", ".sbmtDelForm", function(e){
            e.preventDefault();

            $(this).siblings("form.delForm").submit();                
        });
    </script>
    
    @endslot

@endcomponent