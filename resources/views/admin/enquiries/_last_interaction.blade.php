<?php
$LastInteraction = $enquiry->LastInteraction??[];
if($LastInteraction) {
?>
<tr id="last_interaction-{{$enquiry->id??''}}">
  <td colspan="2">Last Interaction:</td>
  <td colspan="5">{{$LastInteraction->comment??''}}</td>
  <td colspan="3">Created By: {{CustomHelper::showInteractionCreatedBy($LastInteraction)}}
  </td>
  <td colspan="2">Date: {{CustomHelper::DateFormat($LastInteraction->created_at,'d/m/Y h:i A','Y-m-d')}}</td>
</tr>
<?php } ?>