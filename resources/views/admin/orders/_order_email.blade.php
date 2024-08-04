<div style=" margin:0 auto; width:800px; ">
  <table border="0" cellpadding="0" cellspacing="0"  style="font-size:12px; font-family:Arial, Helvetica, sans-serif; width:100%;">
    <thead>
      <tr role="row">
        <th style="border:1px solid #ccc; padding:5px; background:#E0E0E0;">Ref Number</th>
        <th style="border:1px solid #ccc; padding:5px; background:#E0E0E0;">Date</th>
        <th style="border:1px solid #ccc; padding:5px; background:#E0E0E0;">Airline</th>
        <th style="border:1px solid #ccc; padding:5px; background:#E0E0E0;">From</th>
        <th style="border:1px solid #ccc; padding:5px; background:#E0E0E0;">To</th>
        <th style="border:1px solid #ccc; padding:5px; background:#E0E0E0;">Title</th>
        <th style="border:1px solid #ccc; padding:5px; background:#E0E0E0;">First Name</th>
        <th style="border:1px solid #ccc; padding:5px; background:#E0E0E0;">Last Name</th>
        <th style="border:1px solid #ccc; padding:5px; background:#E0E0E0;">Date of Birth</th>
        <th style="border:1px solid #ccc; padding:5px; background:#E0E0E0;">Passenger Contact</th>
        <th style="border:1px solid #ccc; padding:5px; background:#E0E0E0;">PNR</th>
        <th style="border:1px solid #ccc; padding:5px; background:#E0E0E0;">E-Ticket</th>
        <th style="border:1px solid #ccc; padding:5px; background:#E0E0E0;">Agent Name</th>
        <th style="border:1px solid #ccc; padding:5px; background:#E0E0E0;">Contact No</th>
        <th style="border:1px solid #ccc; padding:5px; background:#E0E0E0;">Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($exportArr as $export)
      <tr>
        <td style="border:1px solid #ccc; padding:5px;">{{$export['Ref Number']??''}}</td>
        <td style="border:1px solid #ccc; padding:5px;">{{$export['Date']??''}}</td>
        <td style="border:1px solid #ccc; padding:5px;">{{$export['Airline']??''}}</td>
        <td style="border:1px solid #ccc; padding:5px;">{{$export['From']??''}}</td>
        <td style="border:1px solid #ccc; padding:5px;">{{$export['To']??''}}</td>
        <td style="border:1px solid #ccc; padding:5px;">{{$export['Title']??''}}</td>
        <td style="border:1px solid #ccc; padding:5px;">{{$export['First Name']??''}}</td>
        <td style="border:1px solid #ccc; padding:5px;">{{$export['Last Name']??''}}</td>
        <td style="border:1px solid #ccc; padding:5px;">{{$export['Date of Birth']??''}}</td>
        <td style="border:1px solid #ccc; padding:5px;">{{$export['Passenger Contact']??''}}</td>
        <td style="border:1px solid #ccc; padding:5px;">{{$export['PNR']??''}}</td>
        <td style="border:1px solid #ccc; padding:5px;">{{$export['E-Ticket']??''}}</td>
        <td style="border:1px solid #ccc; padding:5px;">{{$export['Agent Name']??''}}</td>
        <td style="border:1px solid #ccc; padding:5px;">{{$export['Contact No']??''}}</td>
        <td style="border:1px solid #ccc; padding:5px;">{{$export['Status']??''}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>