<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<style>
.table-responsive.fancybox-content {
    max-height: 560px;
    border-radius: 5px;
    width: 750px;
    height: 380px;
}

.fancybox-content {
    padding: 25px;
}
</style>
<body>
<?php $routeName = CustomHelper::getAdminRouteName(); ?>
    <div class="table-responsive">
        <h5>
            <div id="location_list"></div>
            Locations
            @if(CustomHelper::checkPermission('rental','add')) ({{$bike_city->locations->count()??0}}) -
            {{$bike_city->name??''}}
            <a href="javascript:void(0);" class="btn_admin add_location"><i class="fa fa-plus"></i> Add Location</a>
            @endif
        </h5>
        <div id="location_form" class="hide">
            <input type="hidden" name="message" id="message" value="">
            <table class="table table-striped table-bordered table-hover">
                <td>
                    <label>Location</label>
                    <input type="text" name="name" class="form-control admin_input1">
                </td>
                <td>
                    <label>Sort Order</label>
                    <input type="text" name="sort_order" class="form-control admin_input1">
                </td>
                <td>
                    <label>Status</label>
                    <select name="status" class="form-control admin_input1">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </td>
                <td>
                    <input type="hidden" name="data_id" value="">
                    <input type="button" name="" class="btn_admin2 location_form_submit" value="Submit">
                    <input type="button" name="" class="btn_admin2 location_form_cancel" value="Cancel">
                </td>
            </table>
        </div>
        <div id="locations_table">
			@include('admin.bike_cities._locations_table', ['bike_city' => $bike_city])
        </div>
    </div>

<script type="text/javascript">
    $(document).on('click', '.add_location', function() {
        $('#location_form').removeClass('hide');
        $('.btn_admin').addClass('hide');
    });

    $(document).on('click', '.location_form_cancel', function() {
        $('#location_form').addClass('hide');
        $('.btn_admin').removeClass('hide');
        location_form_reset();
    });

    function location_form_reset() {
        $(".validation_error").remove();
        $(".flash-message").remove();
        $('#location_form input').css('border', '1px solid #ccc');
        $('#location_form select').css('border', '1px solid #ccc');
        $('#location_form input[name=data_id]').val('');
        $('#location_form input[name=name]').val('');
        $('#location_form input[name=sort_order]').val('');
        $('#location_form select[name=status]').val('1');
        $('#location_form select[name=show]').val('1');
    }

    $(document).on('click', '.location_edit', function() {
        $('.btn_admin').addClass('hide');
        var data_id = $(this).attr('data-id');
        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ route($routeName.'.bike_cities.locations_get') }}",
            type: "POST",
            data: {
                data_id
            },
            dataType: "JSON",
            headers: {
                'X-CSRF-TOKEN': _token
            },
            cache: false,
            beforeSend: function() {
                location_form_reset();
            },
            success: function(resp) {
                if (resp.success) {
                    if (resp.message) {
                        $('#location_form #message').after(
                            '<div class="flash-message"><div class="alert alert-success"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>' +
                            resp.message + '</div></div>');
                    }

                    if (resp.location) {
                        var location = resp.location;
                        $('#location_form input[name=data_id]').val(location.id);
                        $('#location_form input[name=name]').val(location.name);
                        $('#location_form input[name=sort_order]').val(location.sort_order);
                        $('#location_form select[name=status]').val(location.status);
                        $('#location_form').removeClass('hide');
                    }
                } else {
                    if (resp.message) {
                        $('#location_form #message').after(
                            '<div class="flash-message"><div class="alert alert-danger"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>' +
                            resp.message + '</div></div>');
                    }
                }
            }
        });

    });


    $(document).on('click', '.location_form_submit', function() {
        $(".validation_error").remove();
        $(".flash-message").remove();
        var bike_city_id = '{{$bike_city->id}}';
        var data_id = $('#location_form input[name=data_id]').val();
        var name = $('#location_form input[name=name]').val();
        var sort_order = $('#location_form input[name=sort_order]').val();
        var status = $('#location_form select[name=status]').val();
        var _token = '{{ csrf_token() }}';
        $('.location_form_submit').val('Please Wait...');
        $(".location_form_submit").attr("disabled", true);
        $.ajax({
            url: "{{ route($routeName.'.bike_cities.locations_add') }}",
            type: "POST",
            data: {
                bike_city_id,
                data_id,
                name,
                sort_order,
                status
            },
            dataType: "JSON",
            headers: {
                'X-CSRF-TOKEN': _token
            },
            cache: false,
            beforeSend: function() {
                $('#location_form input').css('border', '1px solid #ccc');
                $('#location_form select').css('border', '1px solid #ccc');
            },
            success: function(resp) {
                $('.location_form_submit').val('Submit');
                $(".location_form_submit").attr("disabled", false);
                if (resp.success) {
                    if (resp.htmlData) {
                        $('#location_count').html(resp.location_count);
                        $('#locations_table').html(resp.htmlData);
                        location_form_reset();
                    }
                    if (resp.message) {
                        $('#location_form #message').after(
                            '<div class="flash-message"><div class="alert alert-success"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>' +
                            resp.message + '</div></div>');
                    }
                } else if (resp.err == true) {
                    $('#location_form #message').after(
                        '<div class="flash-message"><div class="alert alert-danger"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>' +
                        resp.errMsg + '</div></div>');
                } else {
                    if (resp) {
                        parseLocationFormErrorMessages(resp);
                    }
                }
            },
            error: function(e) {
                $('.location_form_submit').val('Submit');
                $(".location_form_submit").attr("disabled", false);
                var response = e.responseJSON;
                if (response) {
                    parseLocationFormErrorMessages(response);
                }
            }
        });

    });

    function parseLocationFormErrorMessages(response) {
        if (response) {
            if (response.message) {
                $('#location_form #message').after(
                    '<div class="flash-message"><div class="alert alert-danger"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>' +
                    response.message + '</div></div>');
            } else {
                $('#location_form #message').after(
                    '<div class="flash-message"><div class="alert alert-danger"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>Invalid request, please check errors below!</div></div>'
                    );
            }
            if (response.errors) {
                $.each(response.errors, function(i, item) {
                    $("#location_form select[name='" + i + "']").css('border', '1px solid #ff0000');
                    $("#location_form select[name='" + i + "']").after('<span class="validation_error">' +
                        item + '</span>');

                    $("#location_form input[name='" + i + "']").css('border', '1px solid #ff0000');
                    $("#location_form input[name='" + i + "']").after('<span class="validation_error">' + item +
                        '</span>');
                });
            }
        }
    }

    $(document).on('click', '.location_delete', function() {
        var data_id = $(this).attr('data-id');
		var bike_city_id = '{{$bike_city->id??0}}';
        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ route($routeName.'.bike_cities.location_delete') }}",
            type: "POST",
            data: {
                data_id,
				bike_city_id
            },
            dataType: "JSON",
            headers: {
                'X-CSRF-TOKEN': _token
            },
            cache: false,
            beforeSend: function() {
                location_form_reset();
            },
            success: function(resp) {
                if (resp.success) {
                    if (resp.htmlData) {
                        $('#location_count').html(resp.location_count);
                        $('#locations_table').html(resp.htmlData);
                        location_form_reset();
                    }
                    if (resp.message) {
                        $('#location_list').html(
                            '<div style="font-size: 14px;" class="flash-message"><div class="alert alert-success"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>' +
                            resp.message + '</div></div>');
                    }
                } else {
                    if (resp.message) {
                        $('#location_list').html(
                            '<div style="font-size: 14px;" class="flash-message"><div class="alert alert-success"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>' +
                            resp.message + '</div></div>');
                    }
                }
            }
        });

    });
    </script>


</body>

</html>