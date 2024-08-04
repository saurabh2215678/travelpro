<?php $price_category_elements = $package->packagePriceCategory->priceCategoryElements??[]; ?>
 <?php if(!empty($records) && count($records) > 0){?>
            <div class="table-responsive col-md-12 flightData">           
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>Airline Name</th>
                        <th>Flight Number</th>
                        <th>Origin <i class="fa fa-long-arrow-right" aria-hidden="true"></i> Destination</th>
                        <th>Flight Time</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach($records as $row){ ?>
                        <tr>
                            <td><?php echo $row->airline_name; ?></td>
                            <td><?php echo $row->flight_number; ?></td>
                            <td>{{$row->flight_departure}} <i class="fa fa-long-arrow-right" aria-hidden="true"></i> {{$row->flight_arrival}}</td>
                            <td><?php echo $row->flight_time; ?></td>
                            <td>
                            @if(CustomHelper::checkPermission('packages','edit'))
                            <a href="javascript:void(0)" class="edit" data-id="{{$row->id}}"><i class="fa fa-pencil-square-o"></i> Edit</a> 
                            @endif
                            @if(CustomHelper::checkPermission('packages','delete'))
                                | <a href="javascript:void(0)" class="delete_data" data-id="{{$row->id}}"><i class="fa fa-trash-o"></i> Delete</a></p>
                            @endif
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            <?php }else{ ?>
                <div class="listboxhotal">
                <div class="alert alert-warning">
                    There are no flights (Package) at the present.
                </div>
                </div>
            <?php } ?>
    </div>