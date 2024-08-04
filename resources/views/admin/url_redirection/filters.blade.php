<?php
$query_string = (isset($urlcustom->query_string))?$urlcustom->query_string:'';
parse_str($query_string,$filters);
// prd($filters);
$propertytypeid = (isset($filters['propertytypeid'])) ? $filters['propertytypeid'] : '';
$sizeid = (isset($filters['sizeid'])) ? explode(',', $filters['sizeid']) : [];
$budgetmin = (isset($filters['budgetmin'])) ? $filters['budgetmin'] : '';
$budgetmax = (isset($filters['budgetmax'])) ? $filters['budgetmax'] : '';
$possessionstatus = (isset($filters['possessionstatus'])) ? $filters['possessionstatus'] : '';
$area = (isset($filters['area'])) ? $filters['area'] : '';
$bathroom = (isset($filters['bathroom'])) ? $filters['bathroom'] : '';
$amenityid = (isset($filters['amenityid'])) ? explode(',', $filters['amenityid']) : [];

$propertyTypes = CustomHelper::MasterPropertyTypesData();
$BHKTypes = CustomHelper::getPropertyBhkTypes();
$property_price = CustomHelper::getPropertyPrice();
$project_stage = CustomHelper::getProjectStage();
$property_area = CustomHelper::getPropertyArea();
$property_bathroom = CustomHelper::getPropertyBathroom();
$amenities = CustomHelper::ProjectSearchAmenities();
?>
<div class="row">
  <div class="col-md-3">
    <div class="form-group{{ $errors->has('propertytypeid') ? ' has-error' : '' }}">
      <label for="propertytypeid" class="control-label">Property Type:</label><br />
      <select name="propertytypeid" class="form-control">
        <option value="">Select</option>
        <?php foreach ($propertyTypes as $row) { ?>
          <option value="{{$row->id}}" {{($row->id==$propertytypeid)?'selected':''}} >{{$row->name}}</option>
        <?php } ?>
      </select>
      @include('snippets.errors_first', ['param' => 'propertytypeid'])
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group{{ $errors->has('sizeid') ? ' has-error' : '' }}">
      <label for="sizeid" class="control-label">BHK Type:</label><br />
      <select name="sizeid[]" id="sizeid" class="form-control" multiple="multiple">
        <?php foreach ($BHKTypes as $row) { ?>
          <option value="{{$row->id}}" {{(!empty($sizeid) && in_array($row->id,$sizeid))?'selected':''}}>{{$row->name}}</option>
        <?php } ?>
      </select>
      @include('snippets.errors_first', ['param' => 'sizeid'])
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group{{ $errors->has('budgetmin') ? ' has-error' : '' }}">
      <label for="budgetmin" class="control-label">Price Min:</label><br />
      <select name="budgetmin" class="form-control">
        <option value="">Select</option>
        <?php foreach ($property_price as $row) { ?>
          <option value="{{$row->price}}" {{($row->price==$budgetmin)?'selected':''}}>{{$row->name}}</option>
        <?php } ?>
      </select>
      @include('snippets.errors_first', ['param' => 'budgetmin'])
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group{{ $errors->has('budgetmax') ? ' has-error' : '' }}">
      <label for="budgetmax" class="control-label">Price Max:</label><br />
      <select name="budgetmax" class="form-control">
        <option value="">Select</option>
        <?php foreach ($property_price as $row) { ?>
          <option value="{{$row->price}}" {{($row->price==$budgetmax)?'selected':''}}>{{$row->name}}</option>
        <?php } ?>
      </select>
      @include('snippets.errors_first', ['param' => 'budgetmax'])
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group{{ $errors->has('possessionstatus') ? ' has-error' : '' }}">
      <label for="possessionstatus" class="control-label">Possession Status:</label><br />
      <select name="possessionstatus" class="form-control">
        <option value="">Select</option>
        <?php foreach ($project_stage as $row) { ?>
          <option value="{{$row->id}}" {{($row->id==$possessionstatus)?'selected':''}}>{{$row->name}}</option>
        <?php } ?>
      </select>
      @include('snippets.errors_first', ['param' => 'possessionstatus'])
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group{{ $errors->has('area') ? ' has-error' : '' }}">
      <label for="area" class="control-label">Area:</label><br />
      <select name="area" class="form-control">
        <option value="">Select</option>
        <?php foreach ($property_area as $row) { ?>
          <option value="{{$row->area}}" {{($row->area==$area)?'selected':''}}>{{$row->name}}</option>
        <?php } ?>
      </select>
      @include('snippets.errors_first', ['param' => 'area'])
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group{{ $errors->has('bathroom') ? ' has-error' : '' }}">
      <label for="bathroom" class="control-label">Bathroom:</label><br />
      <select name="bathroom" class="form-control">
        <option value="">Select</option>
        <?php foreach ($property_bathroom as $row) { ?>
          <option value="{{$row->bathroom}}" {{($row->bathroom==$bathroom)?'selected':''}}>{{$row->name}}</option>
        <?php } ?>
      </select>
      @include('snippets.errors_first', ['param' => 'bathroom'])
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group{{ $errors->has('amenityid') ? ' has-error' : '' }}">
      <label for="amenityid" class="control-label">Amenities:</label><br />
      <select name="amenityid[]" id="amenityid" class="form-control" multiple="multiple">
        <?php foreach ($amenities as $row) { ?>
          <option value="{{$row->id}}" {{(!empty($amenityid) && in_array($row->id,$amenityid))?'selected':''}}>{{$row->name}}</option>
        <?php } ?>
      </select>
      @include('snippets.errors_first', ['param' => 'amenityid'])
    </div>
  </div>

</div>
<?php /*
<div class="mshow">
  <div class="dropdown">
    <div class="select-area">
      <span class="hida" <?php if ($property_type_id) { echo "style='display:none;'";} ?>>Property Type</span>
      <span class="multiSel">
        <?php 
        foreach ($propertyTypes as $propertyType) {
          if ($propertyType->id == $property_type_id) { 
            ?>
            <span title="{{$propertyType->name}}">{{$propertyType->name}}</span>
          <?php } } ?>
        </span>
        <input type="hidden" id="propertyType" name="propertytypeid" value="{{$property_type_id}}" class="invalue">
      </div>
      <div class="select-drop">
        <div class="mutliSelect">
          <ul>
            <?php foreach ($propertyTypes as $propertyType) { ?>
              <li>
                <input type="radio" value="{{$propertyType->name}}" data-id="{{$propertyType->id}}" id="propertyType_{{$propertyType->id}}" data-title="property_type" <?php if ($propertyType->id == $property_type_id) { echo "checked";} ?> />
                <label for="propertyType_{{$propertyType->id}}"><i><img src="{{asset('assets/images/svg/home.svg')}}" class="img-fluid" alt="{$propertyType->name}}"></i><span>{{$propertyType->name}}</span></label>
              </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
    <div class="dropdown">
      <div class="select-area">
        <span class="hida" <?php if (!empty($old_bhktype)) { echo "style='display:none';"; } ?>> BHK Type </span>
        <span class="multiSel">
          <?php
          if ($old_bhktype) {
            $old_bhktypeArr = explode(",", $old_bhktype);
            $bhkArr = array();
            foreach ($BHKTypes as $key => $BHKType) {
              if (in_array($BHKType->id, $old_bhktypeArr)) {
                ?>
                <span title="{{$BHKType->name}}, ">{{$BHKType->name}}<?php if ($key > 1) { echo ", ";} ?> </span>
                <?php
              }
            }
            if (!empty($bhkArr)) {
              echo implode(',', $bhkArr);
            }
          }
          ?>
        </span>
        <input type="hidden" value="{{$old_bhktype}}" name="sizeid" class="invalue" id="bhk_type">
      </div>
      <div class="select-drop">
        <div class="mutliSelect">
          <ul>
            <?php foreach ($BHKTypes as $BHKType) { ?>
              <li>
                <input type="checkbox" value="{{$BHKType->name}}" data-id="{{$BHKType->id}}" id="bhktype_{{$BHKType->id}}" data-title="bhk_type" <?php if (in_array($BHKType->id, $old_bhktypeArr)) { echo "checked"; } ?> />
                <label for="bhktype_{{$BHKType->id}}"><i><img src="{{asset('assets/images/svg/home.svg')}}" class="img-fluid" alt="{{$BHKType->name}}"></i><span>{{$BHKType->name}}</span></label>
              </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
    <div class="dropdown priceSearch">
      <div class="select-area">
        <span class="hida"><?php if (empty($old_pricemin) && empty($old_pricemax)) { echo "Price Range"; } ?></span>
        <span class="multiSel"><?php if ($old_pricemin || $old_pricemax) { echo "Price Range"; } ?></span>
      </div>
      <div class="select-drop">
        <div class="price-value">
          <div class="pricebox">
            <div class="pinput">
              <input class="form-control price-label" placeholder="Min" data-dropdown-id="price-min" name="budgetmin" value="{{$old_pricemin}}" id="price-label" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" autocomplete="off"/>
              <div id="price-min" class="price-range hide">
                <ul>
                  <?php foreach($property_price as $row){ ?>
                    <li data-value="{{$row->price}}" <?php echo ($row->price==$old_pricemin)?'class="active"':'';?> >{{$row->name}}</li>
                  <?php } ?>
                </ul>
              </div>
            </div>
            <span> </span>
            <div class="pinput">
              <input class="form-control price-label" placeholder="Max" data-dropdown-id="price-max" name="budgetmax" value="{{$old_pricemax}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" autocomplete="off"/>
              <div id="price-max" class="price-range hide">
                <ul>
                  <?php foreach($property_price as $row){ ?>
                    <li data-value="{{$row->price}}" <?php echo ($row->price==$old_pricemax)?'class="active"':'';?> >{{$row->name}}</li>
                  <?php }  ?>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="dropdown">
      <div class="select-area">
        <span class="hida"><?php if (empty($old_project_stage)) { echo "Possession Status"; } ?>
      </span>
      <span class="multiSel">
        <?php if (!empty($old_project_stageArr)) {
          $project_stage_name_arr = [];
          foreach ($project_stage as $key => $value) {
            foreach ($old_project_stageArr as $project_stageRow) {
              if ($value->id == $project_stageRow) {
                $project_stage_name_arr[] = $value->name;
              }
            }
          }
          echo implode(',', $project_stage_name_arr);
        } ?>
      </span>
      <input type="hidden" name="possessionstatus" value="{{$old_possessionstatus}}" class="invalue" id="project_stage">
    </div>
    <div class="select-drop">
      <div class="mutliSelect">
        <ul>
          <?php foreach ($project_stage as $key => $value) { ?>
            <li>
              <input type="radio" value="{{$value->name}}" data-id="{{$value->id}}" id="{{$value->id}}" data-title="possession_status" {{(in_array($value->id,$old_project_stageArr))?'checked':''}} />
              <label for="{{$value->id}}"><span>{{$value->name}}</span></label>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="dropdown">
    <div class="select-area">
      <span class="hida">
        <?php if (empty($old_area)) { echo "Area (sq. ft.)";} ?>
      </span>
      <span class="multiSel">
        <?php if ($old_area) { echo $old_area; } ?>
      </span>
      <input type="hidden" id="area" name="area" value="{{$old_area}}" class="invalue">
    </div>
    <div class="select-drop">
      <div class="mutliSelect">
        <ul>
          <?php foreach($property_area as $row){ ?>
            <li>
              <input type="radio" value="{{$row->area}}" data-id="{{$row->area}}" id="{{$row->area}}" data-title="area" name="{{$row->area}}" {{($row->area==$old_area)?'checked':''}} />
              <label for="{{$row->area}}"><span>{{$row->name}}</span></label>
            </li>
          <?php } ?>



        </ul>
      </div>
    </div>
  </div>
</div>
<ul>
  <li>
    <div class="fltitle">Bathroom</div>
    <div class="fl-data">
      <?php foreach($property_bathroom as $row){ ?>
        <input type="radio" name="bathroom" value="{{$row->bathroom}}" id="bathroom_{{$row->bathroom}}" {{($row->bathroom == $bathroom)?'checked':''}} >
        <label for="bathroom_{{$row->bathroom}}">
          <span>{{$row->bathroom}}+</span>
        </label>
      <?php } ?>
    </div>
  </li>
  <li>
    <div class="fltitle">Amenities</div>
    <div class="fl-data">
      <?php foreach ($amenities as $key => $amenity) { ?>
        <input type="checkbox" name="amenityid" value="{{$amenity->id}}" id="{{$amenity->name}}" {{(in_array($amenity->id,$amenityid))?'checked':''}} />
        <label for="{{$amenity->name}}">
          <span>
            <i class="icons {{$amenity->icon}}"></i>
            {{$amenity->name}}
          </span>
        </label>
      <?php } ?>
    </div>
  </li>
</ul>
*/?>