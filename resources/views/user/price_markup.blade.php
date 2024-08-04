@component(config('custom.theme').'.layouts.main')
@slot('title')
    {{ $meta_title ?? ''}}
@endslot
@slot('headerBlock')
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
    .crop_change { padding-bottom:35px; }
    .user_profile_inner .right_info .btn2{font-size: 13px; padding: 8px 25px 8px;text-transform: none;}
</style>
@endslot

<section>
    <div class="container-fluid">
        <div class="user_profile_inner">
            @include('user.left_sidebar')
            <div class="right_info">
                <div class="top_info">
                    <div class="left">
                        <div class="theme_title">
                            <h1 class="title">Airline Markup</h1>
                        </div>
                    </div>                 
                </div>
                @include('snippets.front.flash')
                <div class="table_scroll">
                    <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <table class="table table-striped table-bordered table-hover text-xs cab-pricelight-box">
                        <tr>
                            <td>&nbsp;</td>
                            <td colspan="2"><strong>Offer Fare</strong></td>
                            <td colspan="2"><strong>Online Fare</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Markup</strong></td>
                            <td><strong>Type</strong></td>
                            <td><strong>Value</strong></td>
                            <td><strong>Type</strong></td>
                            <td><strong>Value</strong></td>
                        </tr>
                        <?php if(!empty($airlines)){ foreach($airlines as $airline){

                            $record = (object) array(
                                'offer_markup_type' => 'fixed',
                                'offer_markup_value' => '',
                                'online_markup_type' => 'fixed',
                                'online_markup_value' => '',
                            );
                            if($records) {
                                foreach($records as $row) {
                                    if($row->code == $airline->code) {
                                        $record = $row;
                                        break;
                                    }
                                }
                            }
                         ?>
                            <tr>
                                <td>
                                    <strong>{{$airline->name}}</strong>
                                    <input type="hidden" name="airline_codes[]" value="{{$airline->code}}">
                                </td>
                                <td>
                                    <select name="offer_markup_type[{{$airline->code}}]" class="form-control bg-transparent">
                                        <option value="fixed" {{($record->offer_markup_type=='fixed')?'selected':''}}>Fixed</option>
                                        <option value="percent" {{($record->offer_markup_type=='percent')?'selected':''}}>Percent</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="offer_markup_value[{{$airline->code}}]" class="form-control" value="{{$record->offer_markup_value}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                </td>
                                <td>
                                    <select name="online_markup_type[{{$airline->code}}]" class="form-control bg-transparent">
                                        <option value="fixed" {{($record->online_markup_type=='fixed')?'selected':''}}>Fixed</option>
                                        <option value="percent" {{($record->online_markup_type=='percent')?'selected':''}}>Percent</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="online_markup_value[{{$airline->code}}]" class="form-control" value="{{$record->online_markup_value}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                </td>
                            </tr>
                        <?php } } ?>
                        <tr>
                            <td></td>
                            <td colspan="2">
                                <input type="hidden" name="back_url" value="{{CustomHelper::BackUrl()}}">
                                <input type="submit" name="submit" class="btn s-btn rounded-full" value="Save">
                            </td>
                        </tr>
                    </table>
                    </form>
                </div>
            </div>               
        </div>
    </div>
</section>

@slot('bottomBlock')
@endslot

@endcomponent


