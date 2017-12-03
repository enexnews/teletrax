<?php
require_once("../fact15/fact_config.php");
require_once("teletrax_lib.php"); // include PHP functions library
if (!isset($_GET['p'])) {$p_message = "NOPE";} else  {$p_message = $_GET['p'] ; } // check messages
if (!isset($_GET['id'])) {$p_fact_id =0; } else  {$p_fact_id = $_GET['id']; } // check id parameter
if (!isset($_GET['fc']))  {$p_funct = 'NOPE' ; }  else  {$p_funct = $_GET['fc']; } // check function setting (edit,create ...)
if (!isset($_GET['dt'])or ($_GET['dt']=='ALL')) {$p_date = 'ALL'; $hlp_date=$today;} else  {$p_date = $_GET['dt']; $hlp_date=$p_date; } // check date parameter
if (!isset($_GET['tb'])) {  $p_tab = 1 ;} else  {
    $p_tab = $_GET['tb'];

} // check tab setting
$pagetitle[1] = 'Latest Hits' ;
$pagetitle[2] = 'TOP Partners '. date("F-Y",strtotime($p_date)) ;
$pagetitle[3] = 'TOP Stories '. date("F-Y",strtotime($p_date)) ;
$pagetitle[4] = 'TOP Stories latest 20 days ( since '. date('l, d F Y',strtotime("-20 days",strtotime($today))).")" ;
$pagetitle[5] = 'TOP Stories latest week ( since '. date('l, d F Y',strtotime("-7 days",strtotime($today))).")" ;
$pagetitle[6] = 'TOP Partners latest week ( since '. date('l, d F Y',strtotime("-7 days",strtotime($today))).")" ;
$date_start = date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>ENEX TELETRAX REPORTS</title>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css?v=<?=time();?>" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />

</head>
<body>
<ul id="dropdown1" class="dropdown-content">
    <li><a href="index.php?tb=2&dt=2017-08-01">TOP Partners Month</a></li>
    <li><a href="index.php?tb=3&dt=2017-08-01">TOP Stories Month </a></li>
    <li><a href="index.php?tb=4">TOP Stories last 20 days </a></li>
    <li><a href="index.php?tb=5">TOP Stories last 7 days </a></li>
    <li><a href="index.php?tb=6">TOP Partners last 7 days </a></li>
    <li class="divider"></li>
    <li><a href="#!">three</a></li>
</ul>
  <nav class="enex_blue2" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="#" class="brand-logo"><img src="css/ENEXlogo.png" style="width:220px; height:45px; margin-top:10px;"></a>
      <ul class="right hide-on-med-and-down">

        <li><a href="index.php">HOME</a></li>
        <li><a class="dropdown-button" href="#!" data-activates="dropdown1">Reports Menu<i class="material-icons right">arrow_drop_down</i></a></li>
      </ul>

      <ul id="nav-mobile" class="side-nav">
          <li><a href="index.php?tb=2&dt=2017-08-01">TOP Partners Month</a></li>
          <li><a href="index.php?tb=3&dt=2017-08-01">TOP Stories Month </a></li>
          <li><a href="index.php?tb=4">TOP Stories last 20 days </a></li>
          <li><a href="index.php?tb=5">TOP Stories last 7 days </a></li>
          <li><a href="index.php?tb=6">TOP Partners last 7 days </a></li>
      </ul>
      <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
    </div>
  </nav>
  <div class="section no-pad-bot" id="index-banner">
    <div class="container enex_blue2text">
      <h4 class="center">Teletrax <?php echo $pagetitle[$p_tab] ;?></h4>
    </div>
  </div>


  <div class="container">
     <div>
         <?PHP
        if ($p_message != "NOPE") { echo "<div class='' style='width:100%; text-align: center;'><span class='  alert-box info radius'>",$p_message,"</span></div>";}
        switch($p_tab) {
            case 1  : ttx_latest(); break;
            case 2  : ttx_top_partners($p_date,'month'); break;
            case 3  : ttx_top_stories_month($p_date,'month'); break;
            case 4  : ttx_top_stories_month($p_date,'20days'); break;
            case 5  : ttx_top_stories_month($p_date,'week'); break;
            case 6  : ttx_top_partners($p_date,'week'); break;
            case 14  : fact15_teletrax($p_fact_id,$hlp_date) ; break;
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
          <h5 class="white-text">Reports</h5>
          <p class="grey-text text-lighten-4">This tool is still under construction. The various reports will appear very soon</p>


        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Partners</h5>
          <ul>
            <li><a class="white-text" href="#!">TOP last 20 days</a></li>
            <li><a class="white-text" href="#!">TOP month</a></li>
            <li><a class="white-text" href="#!">TOP Quarter</a></li>
          </ul>
        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Stories</h5>
          <ul>
            <li><a class="white-text" href="#!">TOP last 20 days</a></li>
            <li><a class="white-text" href="#!">TOP month</a></li>
            <li><a class="white-text" href="#!">TOP quarter</a></li>

          </ul>
        </div>
      </div>
    </div>
    <div class="footer-copyright enex_lightblue">
      <div class="container">
      Made by <a class="orange-text white-text" href="http://materializecss.com">Materialize</a>
      </div>
    </div>
  </footer>


  <!--  Scripts <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>-->
  <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>
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


</script>

</body>
</html>
