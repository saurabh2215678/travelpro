<?php
$getId = isset($row->id) ? $row->id : '';
$PackageDepartureCapacity = $row->PackageDepartureCapacity??[];
$capacity = [];
if($PackageDepartureCapacity) {
  $capacity = $PackageDepartureCapacity->pluck('capacity','package_price_id')->toArray()??[];
}
?>
<div class="row" id="row{{$counter}}">
  <div class="col-sm-2">
    <input id="from_date_{{$counter}}" type="text" name="from_date[{{$counter}}]" class="form-control from_date mycalender" value="{{CustomHelper::DateFormat($row->from_date??'','d/m/Y','Y-m-d')??''}}" placeholder="From" autocomplete="off">
    <span id="from_date_{{$counter}}_error" class="validation_error"></span>
  </div>

  <div class="col-sm-2">
    {{--<input id="to_date_{{$counter}}" type="text" name="to_date[{{$counter}}]" class="form-control to_date mycalender" value="{{CustomHelper::DateFormat($row->to_date??'','d/m/Y','Y-m-d')??''}}" placeholder="To"  autocomplete="off">--}}
    <input id="to_date_{{$counter}}" type="text" name="to_date[{{$counter}}]" class="form-control to_date Nocalender" value="{{CustomHelper::DateFormat($row->to_date??'','d/m/Y','Y-m-d')??''}}" placeholder="To"  autocomplete="off" readonly style="background: #fff;cursor: text;">
    <span id="to_date_{{$counter}}_error" class="validation_error"></span>
  </div>

  @foreach($packagePrices as $pp)
  <div class="col-sm-2">        
    <input id="capacity_{{$counter}}" type="number" name="capacity[{{$counter}}][{{$pp->id}}]" class="form-control capacity" value="{{$capacity[$pp->id]??''}}" placeholder="Capacity">
    @if(CustomHelper::checkPermission('packages','edit'))
    <span><i title="Autofill all dates below with same inventory" style="cursor:pointer" class="fa fa-arrow-down copy_down"></i></span>
    @endif
    <!-- Booked: {{$row->booked??'0'}} --><br>
    <span id="capacity_{{$counter}}_error" class="validation_error"></span>
  </div>
  @endforeach

  <div class="col-sm-1">
    @if(CustomHelper::checkPermission('packages','delete'))
    <input type="hidden" name="counter[]" value="{{$counter}}">
    <input type="hidden" name="old_ids[]" value="{{$getId}}">
    <a href="javascript:void(0);" title="Delete" class="removeArr" data-index="{{$counter}}" data-id="{{$getId}}">X</a>
    <label for="input_value">&nbsp;</label>
    @endif
  </div>
</div>