<?php
require_once("../fact15/fact_config.php");
require_once("teletrax_lib.php"); // include PHP functions library
if (!isset($_GET['p'])) {$p_message = "NOPE";} else  {$p_message = $_GET['p'] ; } // check messages
if (!isset($_GET['id'])) {$p_fact_id =0; } else  {$p_fact_id = $_GET['id']; } // check id parameter
if (!isset($_GET['fc']))  {$p_funct = 'NOPE' ; }  else  {$p_funct = $_GET['fc']; } // check function setting (edit,create ...)
if (!isset($_GET['dt'])or ($_GET['dt']=='ALL')) {$p_date = $today; $hlp_date=$today;} else  {$p_date = $_GET['dt']; $hlp_date=$p_date; } // check date parameter
if (!isset($_GET['dts'])) {$p_date_start = $today; } else  {$p_date_start = $_GET['dts'] ;} // check date parameter
if (!isset($_GET['dte'])) {$p_date_end = $today; } else  {$p_date_end = $_GET['dte']; } // check date parameter
if (!isset($_GET['tb'])) {  $p_tab = 1 ;} else  {
    $p_tab = $_GET['tb'];

} // check tab setting
$pagetitle[1] = 'Latest Hits' ;
$pagetitle[2] = 'TOP Partners Month ( Usage of ENEX items! ) <strong>'. date("F-Y",strtotime($p_date)).'</strong>' ;
$pagetitle[3] = 'TOP Stories <strong>'. date("F-Y",strtotime($p_date)).'</strong>' ;
$pagetitle[4] = 'TOP Stories latest 20 days ( since '. date('l, d F Y',strtotime("-20 days",strtotime($today))).")" ;
$pagetitle[5] = 'TOP Stories latest week ( since '. date('l, d F Y',strtotime("-7 days",strtotime($today))).")" ;
$pagetitle[6] = 'TOP Partners latest week ( since '. date('l, d F Y',strtotime("-7 days",strtotime($today))).")" ;
$pagetitle[9] = 'TOP Partners latest week chart( since '. date('l, d F Y',strtotime("-7 days",strtotime($today))).")" ;
$pagetitle[7] = 'TOP Partner Stories latest week ( since '. date('l, d F Y',strtotime("-7 days",strtotime($today))).")" ;
$pagetitle[8] = 'TOP 3rd Party Stories latest week ( since '. date('l, d F Y',strtotime("-7 days",strtotime($today))).")" ;
$pagetitle[11] = 'TOP Stories YEAR '. date("Y",strtotime($p_date)) ;
$pagetitle[12] = 'TOP Stories YEAR Partners '. date("Y",strtotime($p_date)) ;
$pagetitle[13] = 'TOP Stories YEAR Sourced '. date("Y",strtotime($p_date)) ;
$pagetitle[15] = 'TOP Partners YEAR ( Usage of ENEX items! )'. date("Y",strtotime($p_date)) ;
$pagetitle[14] = 'Story '.$p_fact_id.' hits (Date range: '.$p_date_start.' ==> '.$p_date_end.' ) ' ;
$pagetitle[17] = 'TOP Stories Yesterday ('. date('l, d F Y',strtotime("-1 days",strtotime($today))).")" ;
$pagetitle[18] = 'TOP Partners (Usage of ENEX items) Yesterday ('. date('l, d F Y',strtotime("-1 days",strtotime($today))).")" ;
$pagetitle[19] = 'TOP Stories <strong>'. date('l, d F Y',strtotime("0 days",strtotime($p_date)))."</strong>" ;
$pagetitle[20] = 'TOP Partners <strong>'. date('l, d F Y',strtotime("0 days",strtotime($p_date)))."</strong>" ;
$pagetitle[22] = 'NO META DATA DETECTIONS ';
$pagetitle[29] = 'Date Benchmark ';

$date_start = date("Y-m-d");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>TRAXMAN:) - ENEX TELETRAX REPORTS</title>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css?v=<?=time();?>" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/zf/dt-1.10.16/datatables.min.css?v=<?=time();?>"/>

</head>
<body>
<ul id="dropdown3" class="dropdown-content">
    <li><a href="index.php?tb=29">Benchmarks</a></li>
    <li><a href="index.php?tb=22">NO META Detections</a></li>
</ul>
<ul id="dropdown1" class="dropdown-content">
    <li><a href="index.php?tb=18">DAILY</a></li>
    <li><a href="index.php?tb=2">MONTHLY</a></li>
    <li><a href="index.php?tb=15">YEARLY</a></li>
    <li class="divider"></li>
    <li><a href="index.php?tb=6">LAST 7 DAYS</a></li>
    <li><a href="index.php?tb=9">LAST 7 DAYS Chart</a></li>
</ul>
<ul id="dropdown2" class="dropdown-content">
    <li><a href="index.php?tb=17">DAILY</a></li>
    <li><a href="index.php?tb=3">MONTHLY</a></li>
    <li><a href="index.php?tb=11">YEARLY All</a></li>
    <li class="divider"></li>
    <li><a href="index.php?tb=4">LAST 20 DAYS All</a></li>
    <li><a href="index.php?tb=5">LAST WEEK All</a></li>
    <li class="divider"></li>
    <li><a href="index.php?tb=7">LAST WEEK Partners</a></li>
    <li><a href="index.php?tb=8">LAST WEEK Sourced/Pool</a></li>
    <li class="divider"></li>
    <li><a href="index.php?tb=12&dt=2017-01-01">LAST YEAR Partners</a></li>
    <li><a href="index.php?tb=13&dt=2017-01-01">LAST YEAR Sourced/Pool</a></li>

</ul>
  <nav class="enex_blue2" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="#" class="brand-logo"><img class='hide-on-med-and-down' src="css/ENEXlogo.png" style="width:220px; height:45px; margin-top:10px;"><img  src="css/traxman.png" style="margin-top:10px;"></a>
      <ul class="right hide-on-med-and-down">

        <li><a href="index.php">Latest Hits</a></li>
        <li><a class="dropdown-button" href="#!" data-activates="dropdown3">Special Reports<i class="material-icons right">arrow_drop_down</i></a></li>
        <li><a class="dropdown-button" href="#!" data-activates="dropdown1">Top Partners Usage<i class="material-icons right">arrow_drop_down</i></a></li>
        <li><a class="dropdown-button" href="#!" data-activates="dropdown2">Top Stories Hit<i class="material-icons right">arrow_drop_down</i></a></li>
      </ul>

      <ul id="nav-mobile" class="side-nav">
          <li><a style="color:#336699;">REPORTS</a></li>
          <li><a href="index.php?tb=29">BENCHMARK</a></li>
          <li class="divider"></li>
          <li><a style="color:#336699;">PARTNERS</a></li>
          <li class="divider"></li>
          <li><a href="index.php?tb=18">DAILY</a></li>
          <li><a href="index.php?tb=2">MONTHLY</a></li>
          <li><a href="index.php?tb=15">YEARLY</a></li>
          <li class="divider"></li>
          <li><a href="index.php?tb=6">LAST 7 DAYS</a></li>
          <li><a href="index.php?tb=9">LAST 7 DAYS Chart</a></li>
          <li class="divider"></li>
          <li><a style="color:#336699;">STORIES</a></li>
          <li class="divider"></li>
          <li><a href="index.php?tb=17">DAILY</a></li>
          <li><a href="index.php?tb=3">MONTHLY</a></li>
          <li><a href="index.php?tb=11">YEARLY All</a></li>
          <li class="divider"></li>
          <li><a href="index.php?tb=4">LAST 20 DAYS All</a></li>
          <li><a href="index.php?tb=5">LAST WEEK All</a></li>
          <li class="divider"></li>
          <li><a href="index.php?tb=7">LAST WEEK Partners</a></li>
          <li><a href="index.php?tb=8">LAST WEEK Sourced/Pool</a></li>
          <li class="divider"></li>
          <li><a href="index.php?tb=12&dt=2017-01-01">LAST YEAR Partners</a></li>
          <li><a href="index.php?tb=13&dt=2017-01-01">LAST YEAR Sourced/Pool</a></li>
        </ul>
      <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
    </div>
  </nav>
  <div class="section no-pad-bot" id="index-banner">
    <div class="container enex_blue2text">
      <h5 class="center">Teletrax <?php echo $pagetitle[$p_tab] ;?></h5>
    </div>
  </div>


  <div class="container">
     <div>
         <?PHP
        if ($p_message != "NOPE") { echo "<div class='' style='width:100%; text-align: center;'><span class='  alert-box info radius'>",$p_message,"</span></div>";}
        switch($p_tab) {
            case 1  : ttx_latest(); break;
            case 2  : ttx_top_partners($p_date,'month'); break;
            case 3  : ttx_top_stories_month($p_date,'month','',100); break;
            case 4  : ttx_top_stories_month($p_date,'20days','',50); break;
            case 5  : ttx_top_stories_month($p_date,'week','',50); break;
            case 6  : ttx_top_partners($p_date,'week'); break;
            case 7  : ttx_top_stories_month($p_date,'week','PARTNERS',20); break;
            case 8  : ttx_top_stories_month($p_date,'week','3RDPARTY',20); break;
            case 9  : ttx_chart(); break ;
            case 11  : ttx_top_stories_month($p_date,'year','',100); break;
            case 12  : ttx_top_stories_month($p_date,'year','PARTNERS',50); break;
            case 13  : ttx_top_stories_month($p_date,'year','3RDPARTY',50); break;
            case 14  : ttx_item_hits($p_fact_id,$p_date_start,$p_date_end) ; break;
            case 15  : ttx_top_partners($p_date,'year'); break;
            case 17  : ttx_top_stories_month($p_date,'yesterday','',150); break;
            case 18  : ttx_top_partners($p_date,'yesterday'); break;
            case 19  : ttx_top_stories_month($p_date,'anyday','',150); break;
            case 20  : ttx_top_partners($p_date,'anyday'); break ;
            case 21  : ttx_benchmark_list($p_date); break ;
            case 22  : ttx_nometa($p_date); break ;
            case 29  : ttx_benchmark_calc($p_date); break ;

            default :
                ttx_latest() ; break;
        }
        ?>
     </div>
    <div class="section">

      <!--   Icon Section   -->


    </div>

  </div>

  <footer class="page-footer enex_blue2">
    <div class="container enex_blue2">
      <div class="row">
        <div class="col l6 s12">
          <h5 class="white-text">Info</h5>
          <p class="grey-text text-lighten-4">This tool is still under construction. More features coming up soon. Stay tuned.  R. </p>


        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Partners</h5>
          <ul style="list-style-type:square" >
              <li><a class="white-text" href="index.php?tb=18">DAILY</a></li>
              <li><a class="white-text" href="index.php?tb=2">MONTHLY</a></li>
              <li><a class="white-text" href="index.php?tb=15">YEARLY</a></li>
              <li><a class="white-text" href="index.php?tb=6">LAST 7 DAYS</a></li>
              <li><a class="white-text" href="index.php?tb=9">LAST 7 DAYS Chart</a></li>
          </ul>
        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Stories</h5>
          <ul style="list-style-type:square" >
              <li><a class="white-text" href="index.php?tb=17">DAILY</a></li>
              <li><a class="white-text" href="index.php?tb=3">MONTHLY</a></li>
              <li><a class="white-text" href="index.php?tb=11">YEARLY All</a></li>
              <li><a class="white-text" href="index.php?tb=4">LAST 20 DAYS All</a></li>
              <li><a class="white-text" href="index.php?tb=5">LAST WEEK All</a></li>
              <li><a class="white-text" href="index.php?tb=7">LAST WEEK Partners</a></li>
              <li><a class="white-text" href="index.php?tb=8">LAST WEEK Sourced/Pool</a></li>
              <li><a class="white-text" href="index.php?tb=12&dt=2017-01-01">LAST YEAR Partners</a></li>
              <li><a class="white-text" href="index.php?tb=13&dt=2017-01-01">LAST YEAR Sourced/Pool</a></li>

          </ul>
        </div>
      </div>
    </div>
    <div class="footer-copyright enex_lightblue">
      <div class="container">
      V.1.1 / 06.02.2018 - Made by Ralph Joachim/ENEX using the Template from <a class="orange-text white-text" href="http://materializecss.com">Materialize</a>
      </div>
    </div>
  </footer>
<!-- Modal Structure -->
<div id="modal1" class="modal" style="width:46%; padding: 5px;">
    <div class="modal-content">
        <h4>Modal content</h4>
        <p>A bunch of text</p>
    </div>
    <div class="modal_dynamic_content"></div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn">Close</a>
    </div>
</div>


  <!--  Scripts <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>-->
  <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/zf/dt-1.10.16/datatables.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/1.0.0/js/dataTables.responsive.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js"></script>
<script>
    var ctx = document.getElementById("myChart");
    <?php
    require('../fact15/fact_config.php');
    $yesterday = date('Y-m-d',strtotime("-1 days",strtotime($p_date))) ;
    if ($p_date == $today) { $p_date = date('Y-m-d', strtotime('-1 day'));}
    $CoID = new mysqli($config['dbhost'], $config['dblogin'], $config['dbpass']);
    $CoID->select_db($config['dbname']);
    $bench_start_date = date('Y-m-d',strtotime("-8 days",strtotime($p_date))) ;
    $bench_end_date = $p_date ;//date('Y-m-d') ;
    $sql = "SELECT * FROM teletrax_benchmark WHERE tt_bench_date >= '".$bench_start_date."' and tt_bench_date <= '".$bench_end_date."' ORDER BY tt_bench_date";
    // echo $sql;
    $query = $CoID->query($sql);
    $glabels = "";
    $growvalues = "";
    while ( $row = $query->fetch_array()) {
        $glabels.= '"'.$row['tt_bench_date'].'",';
        $gpublished.= ''.$row['tt_bench_published'].',';
        $gwatermarked.= ''.$row['tt_bench_watermarked'].',';
        $gdetections.= ''.$row['tt_bench_detections'].',';
        $gnometa.= ''.$row['tt_bench_nometa'].',';
    }
    $bench_start_date = date('Y-m-d',strtotime("-15 days",strtotime($p_date))) ;
    $bench_end_date = $p_date ;//date('Y-m-d') ;
    $sql = "SELECT * FROM teletrax_benchmark WHERE tt_bench_date >= '".$bench_start_date."' and tt_bench_date <= '".$bench_end_date."' ORDER BY tt_bench_date";
    // echo $sql;
    $query = $CoID->query($sql);
    $g2labels = "";
    $growvalues = "";
    while ( $row = $query->fetch_array()) {
        $g2labels.= '"'.$row['tt_bench_date'].'",';
        $g2published.= ''.$row['tt_bench_published'].',';
        $g2watermarked.= ''.$row['tt_bench_watermarked'].',';
        $g2detections.= ''.$row['tt_bench_detections'].',';
        $g2nometa.= ''.$row['tt_bench_nometa'].',';
    }
    $bench_end_date = $p_date ;//date('Y-m-d') ;
    $sql = "SELECT tt_partner, COUNT(DISTINCT tt_asset) AS nitems FROM teletrax_hits WHERE source_date = '".$p_date."' AND source_id <> 1 GROUP BY tt_partner ORDER BY tt_partner";
    // echo $sql;
    $query = $CoID->query($sql);
    $g3labels = "";
    $growvalues = "";
    while ( $row = $query->fetch_array()) {
        $g3labels.= '"'.$row['tt_partner'].'",';
        $g3detections.= ''.$row['nitems'].',';
    }
    $CoID->close();
    ?>
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php echo $glabels;?> ],
                datasets: [{
                    label: 'Published',
                    backgroundColor: '#26a69a',
                    data: [<?php echo $gpublished ;?>]
                },
                {
                    label: 'Watermarked',
                    backgroundColor: '#448aff',
                    data: [<?php echo $gwatermarked ;?>]
                },
                {
                    label: 'Detections',
                    backgroundColor: '#e57373',
                    data: [<?php echo $gdetections ;?>]
                },
                    {
                        label: 'No Meta',
                        backgroundColor: '#afb42b',
                        data: [<?php echo $gnometa ;?>]
                    }]
        }, options: {
            title:{
                display:true,
                text:"Teletrax Benchmarked"
            },
            tooltips: {
                mode: 'index',
                intersect: false
            },
            responsive: false,
            scales: {
                xAxes: [{
                    stacked: false,
                }],
                yAxes: [{
                    stacked: false,
                }]
            }
        }
    });
    var ctxline = document.getElementById("myChartline");
    var myChartline = new Chart(ctxline, {
        type: 'line',
        data: {
            labels: [<?php echo $g2labels;?> ],
            datasets: [{
                label: 'Published',
                borderColor: '#26a69a',
                data: [<?php echo $g2published ;?>]
            },
                {
                    label: 'Watermarked',
                    borderColor: '#448aff',
                    data: [<?php echo $g2watermarked ;?>]
                },
                {
                    label: 'Detections',
                    borderColor: '#e57373',
                    data: [<?php echo $g2detections ;?>]
                },
                {
                    label: 'No Meta',
                    borderColor: '#ffee58',
                    data: [<?php echo $g2nometa ;?>]
                }]
        }, options: {
            title:{
                display:true,
                text:"Benchmark history last 2 weeks"
            },
            tooltips: {
                mode: 'index',
                intersect: false
            },
            responsive: false,
            scales: {
                xAxes: [{
                    stacked: false,
                }],
                yAxes: [{
                    stacked: false,
                }]
            }
        }
    });
    var ctxbar = document.getElementById("myChartcake");
    var myChartcake = new Chart(ctxbar, {
        type: 'bar',
        data: {
            labels: [<?php echo $g3labels;?> ],
            datasets: [
                {
                    label: 'Detections by Partner',
                    backgroundColor: '#e57373',
                    data: [<?php echo $g3detections ;?>]
                }]
        }, options: {
            title:{
                display:true,
                text:("Nr of stories published on <?php echo date('l, d F Y',(strtotime(($p_date)))); ?> detected per station "),
            },
            tooltips: {
                mode: 'index',
                intersect: false
            },
            responsive: true,
            scales: {
                xAxes: [{
                    stacked: false,
                    ticks: {
                        autoSkip: false
                    }
                }],
                yAxes: [{
                    stacked: false,
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });


</script>
<script>
    $('.modal').modal();
    $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15, // Creates a dropdown of 15 years to control year,
        formatSubmit: 'yyyy-mm-dd',
        today: 'Today',
        clear: 'Clear',
        close: 'Ok',
        onSet: function( event ) {
            if ( event.select ) {
                //alert('TEST'+ this.get( 'select', 'yyyy-mm-dd' ));
                document.location='index.php?tb=19&dt='+this.get( 'select', 'yyyy-mm-dd' )+'';
            }
        },
        closeOnSelect: false // Close upon selecting a date,
    }).css('cursor', 'pointer');

    $('.datepickerbench').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15, // Creates a dropdown of 15 years to control year,
        formatSubmit: 'yyyy-mm-dd',
        today: 'Today',
        clear: 'Clear',
        close: 'Ok',
        onSet: function( event ) {
            if ( event.select ) {
                //alert('TEST'+ this.get( 'select', 'yyyy-mm-dd' ));
                document.location='index.php?tb=29&dt='+this.get( 'select', 'yyyy-mm-dd' )+'';
            }
        },
        closeOnSelect: false // Close upon selecting a date,
    }).css('cursor', 'pointer');
    $('.datepickerpartners').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15, // Creates a dropdown of 15 years to control year,
        formatSubmit: 'yyyy-mm-dd',
        today: 'Today',
        clear: 'Clear',
        close: 'Ok',
        onSet: function( event ) {
            if ( event.select ) {
                //alert('TEST'+ this.get( 'select', 'yyyy-mm-dd' ));
                document.location='index.php?tb=20&dt='+this.get( 'select', 'yyyy-mm-dd' )+'';
            }
        },
        closeOnSelect: false // Close upon selecting a date,
    }).css('cursor', 'pointer');

    $('#topstories_monthtable').dataTable({
        "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
        "order": [ 1, 'desc' ],
        bLengthChange: true,
        "pageLength": 24
    } );
    $('#ttdetails').dataTable({
        "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
        "order": [ 0, 'desc' ],
        bLengthChange: true,
        "pageLength": 24
    } );
    $('.dropdown2').dropdown({
            belowOrigin: true, // Displays dropdown below the button
        }
    );
    $('a.modaltrig').on('click', function() {
        var sourceid = $(this).parent().parent().find('span').text();
        var storytitle = $(this).parent().parent().find('.table_sourcetitle').text();
        var storydate = $(this).parent().parent().find('.table_sourcedate').text();
        var sdate = $(this).data("sdate") ;
        var edate = $(this).data("edate") ;
        $('div.modal-content').html('<h6> STEP ID: '+sourceid+' ('+storydate+')</h6><h5>'+storytitle+'</h5>');
        // .modal_dynamic_content
        $('.modal_dynamic_content').load("teletrax_pload.php",{ sourceid:sourceid, sdate:sdate,edate:edate });
        $('#modal1').modal('open');
    });

    $('a.partnertrig').on('click', function() {
        var partnerid = $(this).parent().parent().find('span').text();
        var sdate = $(this).data("sdate") ;
        var edate = $(this).data("edate") ;
        $('div.modal-content').html('<h5> ENEX items detected on '+partnerid+'</h5>');
        $('.modal_dynamic_content').load("teletrax_pload.php",{ partnerid:partnerid, sdate:sdate,edate:edate });
        $('#modal1').modal('open');
    });
</script>

</body>
</html>
