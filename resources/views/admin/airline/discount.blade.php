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
                        <th>Agent Group</th>
                        <th>Discount</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Type</th>
                        <th>Value</th>
                    </tr>
                    @foreach($agent_groups as $agent_group)
                    <?php 
                    if($records) {
                        $record = (object) [
                            'agent_group_id'=>'',
                            'domestic_offer_type'=>'',
                            'domestic_offer_value'=>'',
                            'domestic_online_type'=>'',
                            'domestic_online_value'=>'',
                            'domestic_agent_type' => '',
                            'domestic_agent_value' => '',
                            'international_offer_type'=>'',
                            'international_offer_value'=>'',
                            'international_online_type'=>'',
                            'international_online_value'=>'',
                            'international_agent_type' => '',
                            'international_agent_value' => '',
                        ];
                        foreach($records as $row) {
                            if($row->agent_group_id == $agent_group->id) {
                                $record = $row;
                                break;
                            }
                        }
                    }
                    ?>
                    <tr>
                        <td rowspan="2">
                            {{$agent_group->name}}
                            <input type="hidden" name="agent_group_id[]" value="{{$agent_group->id}}">
                        </td>
                        <td>
                            Domestic
                        </td>
                        <td>
                            <select name="domestic_offer_type[{{$agent_group->id}}]" class="form-control">
                                <option value="fixed" {{($record->domestic_offer_type=='fixed')?'selected':''}}>Fixed</option>
                                <option value="percent" {{($record->domestic_offer_type=='percent')?'selected':''}}>Percent</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="domestic_offer_value[{{$agent_group->id}}]" class="form-control" value="{{$record->domestic_offer_value}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                        </td>
                        <td>
                            <select name="domestic_online_type[{{$agent_group->id}}]" class="form-control">
                                <option value="fixed" {{($record->domestic_online_type=='fixed')?'selected':''}}>Fixed</option>
                                <option value="percent" {{($record->domestic_online_type=='percent')?'selected':''}}>Percent</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="domestic_online_value[{{$agent_group->id}}]" class="form-control" value="{{$record->domestic_online_value}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                        </td>
                        <td>
                            <select name="domestic_agent_type[{{$agent_group->id}}]" class="form-control">
                                <option value="fixed" {{($record->domestic_agent_type=='fixed')?'selected':''}}>Fixed</option>
                                <option value="percent" {{($record->domestic_agent_type=='percent')?'selected':''}}>Percent</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="domestic_agent_value[{{$agent_group->id}}]" class="form-control" value="{{$record->domestic_agent_value}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            International
                        </td>
                        <td>
                            <select name="international_offer_type[{{$agent_group->id}}]" class="form-control">
                                <option value="fixed" {{($record->international_offer_type=='fixed')?'selected':''}}>Fixed</option>
                                <option value="percent" {{($record->international_offer_type=='percent')?'selected':''}}>Percent</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="international_offer_value[{{$agent_group->id}}]" class="form-control" value="{{$record->international_offer_value}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                        </td>
                        <td>
                            <select name="international_online_type[{{$agent_group->id}}]" class="form-control">
                                <option value="fixed" {{($record->international_online_type=='fixed')?'selected':''}}>Fixed</option>
                                <option value="percent" {{($record->international_online_type=='percent')?'selected':''}}>Percent</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="international_online_value[{{$agent_group->id}}]" class="form-control" value="{{$record->international_online_value}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                        </td>
                        <td>
                            <select name="international_agent_type[{{$agent_group->id}}]" class="form-control">
                                <option value="fixed" {{($record->international_agent_type=='fixed')?'selected':''}}>Fixed</option>
                                <option value="percent" {{($record->international_agent_type=='percent')?'selected':''}}>Percent</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="international_agent_value[{{$agent_group->id}}]" class="form-control" value="{{$record->international_agent_value}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>&nbsp;</td>
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