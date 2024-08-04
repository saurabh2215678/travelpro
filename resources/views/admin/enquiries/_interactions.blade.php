  <form id="interaction_form{{$enquiry->id}}" onSubmit="return validate_interaction('{{$enquiry->id}}');">
    {{ csrf_field() }}
    <div class="form-row">
      <div class="col-md-12">
        <h5>Add Interaction</h5>
      </div>
      <input type="hidden" name="message" id="interaction_message" value="">

      {{--<div class="col-md-3">
        <div class="form-group{{ $errors->has('lead_status') ? ' has-error' : '' }}">
          <label class="control-label required">Lead Status:</label>
          <select name="lead_status" id="interaction_lead_status" class="form-control">
            <option value="">Select Lead Status</option>
            @if($lead_status_arr)
            @foreach($lead_status_arr as $row)
            <option value="{{$row->id}}">{{$row->name}}</option>
            @endforeach
            @endif
          </select>
          @include('snippets.errors_first', ['param' => 'lead_status'])
        </div>
      </div>--}}

      <div class="col-md-3">
        <div class="form-group{{ $errors->has('lead_status') ? ' has-error' : '' }}">
          <label class="control-label required">Lead Status:</label>
          <select name="lead_status" id="interaction_lead_status" class="form-control">
            <option value="">Select Lead Status</option>
            @if($lead_statuses)
            @foreach($lead_statuses as $row)
            <option value="{{$row->id}}">{{$row->description}}</option>
            @endforeach
            @endif
          </select>
          @include('snippets.errors_first', ['param' => 'lead_status'])
        </div>
      </div>

      {{--<div class="col-md-3">
        <div class="form-group{{ $errors->has('lead_sub_status') ? ' has-error' : '' }}">
          <label class="control-label">Lead Sub Status:</label>
          <select name="lead_sub_status" id="interaction_lead_sub_status" class="form-control">
            <option value="">Select Lead Sub Status</option>
          </select>
          @include('snippets.errors_first', ['param' => 'lead_sub_status'])
        </div>
      </div>--}}

      <div class="col-md-3">
        <div class="form-group{{ $errors->has('rating') ? ' has-error' : '' }}">
          <label class="control-label">Rating:</label>
          <select name="rating" id="interaction_rating" class="form-control">
            <option value="">Select Rating</option>
            @if($rating_arr)
            @foreach($rating_arr as $row)
            <option value="{{$row->id}}">{{$row->name}}</option>
            @endforeach
            @endif
          </select>
          @include('snippets.errors_first', ['param' => 'rating'])
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group{{ $errors->has('followup_date') ? ' has-error' : '' }}">
          <label class="control-label">Followup Date:</label>
          <input type="text" name="followup_date" id="interaction_followup_date" class="form-control" value="" maxlength="255" autocomplete="off" />
          @include('snippets.errors_first', ['param' => 'followup_date'])
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
          <label class="control-label">Comment:</label>
          <textarea name="interaction_content" id="interaction_content" class="form-control" rows="1"></textarea>
          @include('snippets.errors_first', ['param' => 'content'])
        </div>
      </div>
      <div class="col-md-12">
        <input type="hidden" name="enquiry_id" value="{{$enquiry->id}}">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
  
<div class="clearfix"></div>
  <div class="article col-md-12">
    <h5>Interaction ({{$enquiry->EnquiryInteraction->count()}}) (Enquiry Id: #{{$enquiry->enquiry_no_id??''}})</h5>
    <table id="user_datatable" class="user-list table table-striped" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>Comment</th>
          <th>Lead Status</th>
          {{--<th>Lead Sub Status</th>--}}
          <th>Rating</th>
          <th>Followup Date</th>
          <th>Updated Date</th>
          <th>Updated By</th>
        </tr>
      </thead>
      <tbody>
        @foreach($enquiry->EnquiryInteraction as $interaction)
        <tr>
          <td>{!!$interaction->comment??''!!}</td>
          <td>{{CustomHelper::showEnquiryMaster($interaction->lead_status??'')}}</td>
          {{--<td>{{CustomHelper::showEnquiryMaster($interaction->lead_sub_status??'')}}</td>--}}

          <td style="color: {{$interaction->GetColor->color_code ?? ''}};">{{CustomHelper::showEnquiryMaster($interaction->rating??'')}}</td>

          <td>
            @if($interaction->followup_date)
            <i class="fa fa-calendar"></i> {{CustomHelper::DateFormat($interaction->followup_date,'d/m/Y','Y-m-d')}}
            @endif
          </td>
          <td>
            @if($interaction->created_at)
            <i class="fa fa-calendar"></i> {{CustomHelper::DateFormat($interaction->created_at,'d/m/Y','Y-m-d')}}
            @endif
          </td>
          <td>
            <i class="fa fa-user-o"></i>
            <span id="name">
            @if($interaction->created_type == 'backend')
            {{CustomHelper::showAdmin($interaction->created_by??'')}}
            @else
            {{ucfirst($interaction->created_type)}}
            @endif
            </span>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  </form>