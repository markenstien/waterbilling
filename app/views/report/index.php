<?php build('content') ?>
<?php
    $grpDProfitPlatform = [
        'labels' => [],
        'data' => [],
        'colors' => []
    ];
    $grpDProfitPlatformBarangay = [
        'labels' => [],
        'data' => [],
        'colors' => []
    ];


    $grpDCustomersPlatform = [
        'labels' => [],
        'data' => [],
        'colors' => []
    ];

    $grpDCustomersBarangay = [
        'labels' => [],
        'data' => [],
        'colors' => []
    ];
?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Report Management</h4>
        </div>        
        <div class="card-body">
        <?php echo $_formCommon->start([
            'method' => 'get'
        ])?>
            <?php
                echo $_formCommon->getCol('start_date');
                echo $_formCommon->getCol('end_date');
            ?>

            <div class="form-group mt-4">
                <?php Form::submit('btn_report', 'Create Report')?>
            </div>
        <?php echo $_formCommon->end()?>
        </div>
    </div> 

    <?php if(!empty($summary)) :?>
        <?php echo wDivider(50)?>
        <div class="card">
            <div class="card-header">
                <div class="text-center">
                    <h4 class="card-title">COMPANY XXX</h4>
                    <p>Report Result</p>
                    <p>as of <?php echo date('Y-m-d');?></p>
                </div>
            </div>

            <?php if(empty($summary['profitReport']['byStations'])) :?>
                <div class="card-body">
                    <p>No Reports to show</p>
                </div>
            <?php else:?>
                <div class="card-body">
                    <h4>Profit Report</h4>
                    <section>
                        <label>Sort : Highest by platform</label>
                        <div class="row">
                            <div class="col-md-5">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>#</th>
                                        <th>Platform</th>
                                        <th>Purok</th>
                                        <th>Profit</th>
                                    <thead>
                                    <tbody>
                                        <?php $counter = 0?>
                                        <?php foreach($summary['profitReport']['byStations'] as $key => $row) :?>
                                            <?php $counter++?>
                                            <?php
                                                $grpDProfitPlatform['labels'][] = $row['platform_name'];
                                                $grpDProfitPlatform['data'][] = $row['profit'];
                                                $grpDProfitPlatform['colors'][] = '#'.random_color();
                                            ?>
                                            <tr>
                                                <td><?php echo $counter?></td>
                                                <td><?php echo $row['platform_name']?>(<?php echo $row['platform_reference']?>)</td>
                                                <td><?php echo $row['barangay']?></td>
                                                <td><?php echo amountHTML($row['profit'])?></td>
                                            </tr>
                                        <?php endforeach?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-7"><canvas id="profitChartPlatform"></canvas></div>
                        </div>
                    </section>

                    <?php echo wDivider(20)?>

                    <section>
                        <label>Sort : Highest by Purok</label>
                        <div class="row">
                            <div class="col-md-5">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>#</th>
                                        <th>Platform</th>
                                        <th>Purok</th>
                                        <th>Profit</th>
                                    <thead>
                                    <tbody>
                                        <?php $counter = 0?>
                                        <?php foreach($summary['profitReport']['byBarangays'] as $key => $row) :?>
                                            <?php $counter++?>
                                            <?php
                                                $grpDProfitPlatformBarangay['labels'][] = $row['barangay'];
                                                $grpDProfitPlatformBarangay['data'][] = $row['profit'];
                                                $grpDProfitPlatformBarangay['colors'][] = '#'.random_color();
                                            ?>
                                            <tr>
                                                <td><?php echo $counter?></td>
                                                <td><?php echo $row['platform_name']?>(<?php echo $row['platform_reference']?>)</td>
                                                <td><?php echo $row['barangay']?></td>
                                                <td><?php echo amountHTML($row['profit'])?></td>
                                            </tr>
                                        <?php endforeach?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-7"><canvas id="profitChartPlatformBarangay"></canvas></div>
                        </div>
                    </section>
                </div>

                <div class="card-body">
                    <h4>Customers Summary</h4>
                    <section>
                        <label>Sort : Highest by Platform</label>
                        <div class="row">
                            <div class="col-md-5">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>#</th>
                                        <th>Platform</th>
                                        <th>Total Customers</th>
                                    <thead>
                                    <tbody>
                                        <?php $counter = 0?>
                                        <?php foreach($summary['customerReport']['byStations'] as $key => $row) :?>
                                            <?php
                                                $counter++;

                                                $grpDCustomersPlatform['labels'][] = $row['name'];
                                                $grpDCustomersPlatform['data'][] = count($row['customers']);
                                                $grpDCustomersPlatform['colors'][] = '#'.random_color();
                                            ?>
                                            <tr>
                                                <td><?php echo $counter?></td>
                                                <td><?php echo $row['name']?>(<?php echo $row['platform_reference']?>)</td>
                                                <td><?php echo count($row['customers'])?></td>
                                            </tr>
                                        <?php endforeach?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-7"><canvas id="customersChartPlatform"></canvas></div>
                        </div>
                    </section>

                    <?php echo wDivider(20)?>

                    <section>
                        <label>Sort : Highest by Barangay</label>
                        <div class="row">
                            <div class="col-md-5">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>#</th>
                                        <th>Platform</th>
                                        <th>Total Customers</th>
                                    <thead>
                                    <tbody>
                                        <?php $counter = 0?>
                                        <?php foreach($summary['customerReport']['byBarangays'] as $key => $row) :?>
                                            <?php
                                                $counter++;

                                                $grpDCustomersBarangay['labels'][] = $row['street_name'];
                                                $grpDCustomersBarangay['data'][] = count($row['customers']);
                                                $grpDCustomersBarangay['colors'][] = '#'.random_color();
                                            ?>
                                            <tr>
                                                <td><?php echo $counter?></td>
                                                <td><?php echo $row['street_name']?>(<?php echo $row['platform_reference']?>)</td>
                                                <td><?php echo count($row['customers'])?></td>
                                            </tr>
                                        <?php endforeach?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-7"><canvas id="customersChartBarangay"></canvas></div>
                        </div>
                    </section>
                </div>
            <?php endif?>
        </div>

        <?php
            $grpDProfitPlatform['labels'] = graphParamImploder($grpDProfitPlatform['labels']);
            $grpDProfitPlatform['data'] = graphParamImploder($grpDProfitPlatform['data']);
            $grpDProfitPlatform['colors'] = graphParamImploder($grpDProfitPlatform['colors']);

            $grpDProfitPlatformBarangay['labels'] = graphParamImploder($grpDProfitPlatformBarangay['labels']);
            $grpDProfitPlatformBarangay['data'] = graphParamImploder($grpDProfitPlatformBarangay['data']);
            $grpDProfitPlatformBarangay['colors'] = graphParamImploder($grpDProfitPlatformBarangay['colors']);

            $grpDCustomersPlatform['labels'] = graphParamImploder($grpDCustomersPlatform['labels']);
            $grpDCustomersPlatform['data'] = graphParamImploder($grpDCustomersPlatform['data']);
            $grpDCustomersPlatform['colors'] = graphParamImploder($grpDCustomersPlatform['colors']);

            $grpDCustomersBarangay['labels'] = graphParamImploder($grpDCustomersBarangay['labels']);
            $grpDCustomersBarangay['data'] = graphParamImploder($grpDCustomersBarangay['data']);
            $grpDCustomersBarangay['colors'] = graphParamImploder($grpDCustomersBarangay['colors']);

        ?>    
    <?php endif?>
<?php
    function graphParamImploder($data) {
        return implode('","', $data);
    }
?>
<?php endbuild()?>

<?php build('scripts')?>
<script type="text/javascript">
var colors = {
    primary        : "#6571ff",
    secondary      : "#7987a1",
    success        : "#05a34a",
    info           : "#66d1d1",
    warning        : "#fbbc06",
    danger         : "#ff3366",
    light          : "#e9ecef",
    dark           : "#060c17",
    muted          : "#7987a1",
    gridBorder     : "rgba(77, 138, 240, .15)",
    bodyColor      : "#000",
    cardBg         : "#fff"
  }

  var fontFamily = "'Roboto', Helvetica, sans-serif"


  // Bar chart
  if($('#profitChartPlatform').length) {
    var profitChartPlatform = ezChart('#profitChartPlatform', 'Profits', 
        ["<?php echo $grpDProfitPlatform['labels']?>"], 
        ["<?php echo $grpDProfitPlatform['data']?>"],
        ["<?php echo $grpDProfitPlatform['colors']?>"]);
  }

  if($('#profitChartPlatformBarangay').length) {
    var grpDProfitPlatformBarangay = ezChart('#profitChartPlatformBarangay', 'Profits', 
        ["<?php echo $grpDProfitPlatformBarangay['labels']?>"], 
        ["<?php echo $grpDProfitPlatformBarangay['data']?>"],
        ["<?php echo $grpDProfitPlatformBarangay['colors']?>"]);
  }

  if($('#customersChartPlatform').length) {
    var customersChartPlatform = ezChart('#customersChartPlatform', 'Customers', 
        ["<?php echo $grpDCustomersPlatform['labels']?>"], 
        ["<?php echo $grpDCustomersPlatform['data']?>"],
        ["<?php echo $grpDCustomersPlatform['colors']?>"]);
  }

  if($('#customersChartBarangay').length) {
    var customersChartBarangay = ezChart('#customersChartBarangay', 'Customers', 
        ["<?php echo $grpDCustomersBarangay['labels']?>"], 
        ["<?php echo $grpDCustomersBarangay['data']?>"],
        ["<?php echo $grpDCustomersBarangay['colors']?>"]);
  }
  
  function ezChart(element, chartName, labels, data ,backgroundColor) {

    return new Chart($(element), {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          {
            label: chartName,
            data: data,
            backgroundColor: backgroundColor,
          }
        ]
      },
      options: {
        plugins: {
          legend: { display: false },
        },
        scales: {
          x: {
            display: true,
            grid: {
              display: true,
              color: colors.gridBorder,
              borderColor: colors.gridBorder,
            },
            ticks: {
              color: colors.bodyColor,
              font: {
                size: 12
              }
            }
          },
          y: {
            grid: {
              display: true,
              color: colors.gridBorder,
              borderColor: colors.gridBorder,
            },
            ticks: {
              color: colors.bodyColor,
              font: {
                size: 12
              }
            }
          }
        }
      }
    });
  }
</script>

<?php endbuild()?>
<?php loadTo()?>