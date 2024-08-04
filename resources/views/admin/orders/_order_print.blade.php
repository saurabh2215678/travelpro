<link href="http://flightbooking.ii.com/manage/assets/css/bootstrap.min.css" rel="stylesheet">

<style>
table.table thead tr th {background: #18afa6;color: #fff;font-family: sans-serif;font-size: 16px;}
table.table tr td {font-size: 13px;font-family: sans-serif;}
table {background-color: transparent}
th {text-align: left}
.table {width: 100%;max-width: 100%; margin-bottom: 20px}
.table>tbody>tr>td,.table>tbody>tr>th,.table>tfoot>tr>td,.table>tfoot>tr>th,.table>thead>tr>td,.table>thead>tr>th {
    padding: 8px;
    line-height: 1.42857143;
    vertical-align: top;
    border-top: 1px solid #ddd
}
.table>thead>tr>th {vertical-align: bottom;border-bottom: 2px solid #ddd}
.table>caption+thead>tr:first-child>td,.table>caption+thead>tr:first-child>th,.table>colgroup+thead>tr:first-child>td,.table>colgroup+thead>tr:first-child>th,.table>thead:first-child>tr:first-child>td,.table>thead:first-child>tr:first-child>th {
    border-top: 0
}
.table>tbody+tbody { border-top: 2px solid #ddd}
.table .table {background-color: #fff}
.table-condensed>tbody>tr>td,.table-condensed>tbody>tr>th,.table-condensed>tfoot>tr>td,.table-condensed>tfoot>tr>th,.table-condensed>thead>tr>td,.table-condensed>thead>tr>th {
    padding: 5px
}
.table-bordered {border: 1px solid #ddd}
.table-bordered>tbody>tr>td,.table-bordered>tbody>tr>th,.table-bordered>tfoot>tr>td,.table-bordered>tfoot>tr>th,.table-bordered>thead>tr>td,.table-bordered>thead>tr>th {
    border: 1px solid #ddd
}
.table-bordered>thead>tr>td,.table-bordered>thead>tr>th {border-bottom-width: 2px}

.table-striped>tbody>tr:nth-of-type(odd) {background-color: #f9f9f9}

.table-hover>tbody>tr:hover {background-color: #f5f5f5}
</style>  
  <table id="dataTables-example" class="table table-bordered dataTable" style="background:#fff;" role="grid" aria-describedby="dataTables-example_info">
    <thead>
      <tr role="row">
        <th>Date</th>	
        <th>Airline</th>
        <th>From</th>
        <th>To</th>
        <th>Passenger Detail</th>
        <th>PNR</th>
        <th>E-Ticket</th>
        <th>Agent Name</th>
        <th>Contact No</th>
      </tr>
    </thead>
    <tbody>
      @foreach($orders as $order)
      <?php
      $booking_details = json_decode($order->booking_details,true);
      $airline_name = $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'][0]['fD']['aI']['name']??'';
      $source = $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'][0]['da']['city']??'';
      $destination = $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'][count($booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'])-1]['aa']['city']??'';
      $OrderTraveller = $order->OrderTraveller??[];
      $pnrDetails = $OrderTraveller[0]->pnrDetails??'';
      $airline_ticket_no = $OrderTraveller[0]->airline_ticket_no??'';
      ?>
      <tr>
        <td>{{CustomHelper::DateFormat($order->trip_date,'j F, Y')??''}}</td>
        <td>{{$airline_name}}</td>
        <td>{{$source}}</td>
        <td>{{$destination}}</td>
        <td>
          <table width="100%" border="0">
            <tr>
              <td>Name</td>
              <td>Type</td>
              <td>Gender</td>
            </tr>
            @foreach($OrderTraveller as $traveller)
            <tr>
              <td>{{$traveller->title??''}} {{$traveller->first_name??''}}  {{$traveller->last_name??''}}</td>
              <td>{{$traveller->pax_type}}</td>
              <td>{{($traveller->title=='Mr' || $traveller->title=='Master')?'Male':'Female'}}</td>
            </tr>
            @endforeach
          </table>
        </td>
        <td>{{$pnrDetails}}</td>
        <td>{{$airline_ticket_no}}</td>      
        <td>{{$order->supplierInfo->company_name??''}}</td>
        <td>{{$order->supplierInfo->User->phone??''}}</td>
      </tr>
      @endforeach
  </tbody>
</table>
<script>
  print();
</script>