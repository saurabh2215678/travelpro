@component(config('custom.theme').'.layouts.main')
@slot('title')
    {{ 'Dashboard' }}
@endslot
@slot('headerBlock')
<style>
    .graph_dropdown{position: absolute;display: flex;right: 4%;list-style: none;z-index: 999;}
    .dashmain ul.dropdown-menu {right: 0;}
    .dashmain .graph_dropdown ul.dropdown-menu {left: auto;}
    #chart text {font-weight: 600;}
    .dropdown-menu { display: none; }
    .graph_dropdown .btn { margin-left: auto; }
    .dropdown-menu { display: none; z-index: 1000; min-width: 160px; padding: 5px 0; margin: 2px 0 0; font-size: 14px; text-align: left; list-style: none; background-color: #fff; -webkit-background-clip: padding-box; background-clip: padding-box; border: 1px solid #ccc; border: 1px solid rgba(0, 0, 0, .15); border-radius: 4px; -webkit-box-shadow: 0 6px 12px rgb(0 0 0 / 18%); box-shadow: 0 6px 12px rgb(0 0 0 / 18%); }
    .dropdown-menu a { padding: 4px 14px; display: block; }
.dropdown-menu a:hover { color: #00b2a7; text-decoration: none; background-color: #f5f5f5; }
</style>

@endslot

<section>
    <div class="container-fluid">
        <div class="user_profile_inner">
            @include('user.left_sidebar')
            <div class="right_info">
                <div class="graph_dropdown">
            <div class="dropdown">
                <button class="btn s-btn btn-info sky_blue rounded-full" type="button" data-toggle="dropdown" id="tab_btn">Daily
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0)" onClick="loadSalesData('Daily')">Daily</a></li>
                        <li><a href="javascript:void(0)" onClick="loadSalesData('Weekly')">Weekly</a></li>
                        <li><a href="javascript:void(0)" onClick="loadSalesData('Monthly')">Monthly</a></li>
                    </ul>
                </div>
            </div>
<div id="chart">
     </div>
         </div>                     
        </div>
    </div>
</section>


<div class="row">
      <div class="container mx-auto">


 </div>
            </div>

@slot('bottomBlock')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script type="text/javascript">

    $(document).click(function(){
        $('.dropdown-menu').hide();
    })

    $('[data-toggle]').click(function(e){
        e.stopPropagation();
        $(this).siblings('.dropdown-menu').toggle();
    });

    $(document).ready(function(){
        loadSalesData();
    });


function loadSalesData(dayType='Daily') {
    var _token = '{{ csrf_token() }}';
    $.ajax({
        url: "{{ route('user.loadSalesData') }}",
        type: "POST",
        data: {dayType},
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        beforeSend:function(){
            $(".ajax_msg").html("");
        },
        success: function(resp){
            if(resp.success) {
                renderSalesData(resp.graph);

                $('#tab_btn').html(dayType+'<span class="caret"></span>');
            }
        }
    });
}

function renderSalesData(graph) {
    // console.log('graph=',graph);
    var options = {
          series: [{
          name: 'Total Sales',
          type: 'column',
          data: graph.data
        }
        // , {
        //   name: 'Social Media',
        //   type: 'line',
        //   data: [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16]
        // }
    ],
          chart: {
          height: 350,
          type: 'line',
          toolbar: {
            show: false
          },
          zoom: {
            enabled: false
          }

        },
        stroke: {
          width: [0, 4]
        },
        title: {
          text: 'Reports/Day'
        },
        dataLabels: {
          enabled: true,
          enabledOnSeries: [1]
        },
        labels:  graph.labels,
        fill: {
            colors: ['#00B2A7', '#282658']
            },
        yaxis: [{
          title: {
            text: 'Total Sales',
          },
        
        },
        

        // , {
        //   opposite: true,
        //   title: {
        //     text: 'Social Media'
        //   }
        // }
    ]
    
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        chart.updateOptions(options)
}

</script>
@endslot
@endcomponent


