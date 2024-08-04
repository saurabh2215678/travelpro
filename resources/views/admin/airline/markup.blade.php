@component('admin.layouts.main')

@slot('title')
Admin - {{$title}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
?>
<div class="top_title_admin">
    <div class="title">
        <h2>{{$title}}</h2>
    </div>
</div>

<div class="centersec">
    @include('snippets.errors')
    @include('snippets.flash')
    <div class="bgcolor">
        <div class="table-responsive ">
            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <table class="table table-striped table-bordered">
                    <tr>
                        <td>
                            &nbsp;
                        </td>
                        <td colspan="2" align="center">
                            <strong>Offer Fare</strong>
                        </td>
                        <td colspan="2" align="center">
                            <strong>Online Fare</strong>
                        </td>
                        <td colspan="2" align="center">
                            <strong>Agent Offer Fare</strong>
                        </td>
                    </tr>
                    <tr>
                        <th>Markup</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Type</th>
                        <th>Value</th>
                    </tr>
                    @foreach($records as $record)
                    <tr>
                        <td>
                            {{$record->name}}
                            <input type="hidden" name="airline_codes[]" value="{{$record->code}}">
                        </td>
                        <td>
                            <select name="offer_markup_type[{{$record->code}}]" class="form-control">
                                <option value="fixed" {{($record->offer_markup_type=='fixed')?'selected':''}}>Fixed</option>
                                <option value="percent" {{($record->offer_markup_type=='percent')?'selected':''}}>Percent</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="offer_markup_value[{{$record->code}}]" class="form-control" value="{{$record->offer_markup_value}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                        </td>
                        <td>
                            <select name="online_markup_type[{{$record->code}}]" class="form-control">
                                <option value="fixed" {{($record->online_markup_type=='fixed')?'selected':''}}>Fixed</option>
                                <option value="percent" {{($record->online_markup_type=='percent')?'selected':''}}>Percent</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="online_markup_value[{{$record->code}}]" class="form-control" value="{{$record->online_markup_value}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                        </td>
                        <td>
                            <select name="agent_markup_type[{{$record->code}}]" class="form-control">
                                <option value="fixed" {{($record->agent_markup_type=='fixed')?'selected':''}}>Fixed</option>
                                <option value="percent" {{($record->agent_markup_type=='percent')?'selected':''}}>Percent</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="agent_markup_value[{{$record->code}}]" class="form-control" value="{{$record->agent_markup_value}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                        </td>
                    </tr>
                    @endforeach                    
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="2">
                            <input type="hidden" name="back_url" value="{{CustomHelper::BackUrl()}}">
                            <input type="submit" name="submit" class="btn_admin2" value="Save">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
@slot('bottomBlock')
@endslot
@endcomponent