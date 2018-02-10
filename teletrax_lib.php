<?php
/**
 * Created by PhpStorm.
 * User: Ralph
 * Date: 01.12.17
 * Time: 21:51
 */
function killsession() {
    if(session_id() == '') {
        session_start();
    }
    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 42000, '/');
    }
    session_destroy();
}
/*****************************************************************/
function ctx_real_datestr($param_date)
// This function creates a natural format of a date in YYYY-MM-DD - format
{
    $real_date_string = date('l, d F Y',(strtotime(($param_date)))) ;

    return $real_date_string ;
}
//********************************************
function ttx_chart() {
    ?>
    <canvas id="myChart" width="300" height="300"></canvas>
    <?php
}
// *******************************************
function ttx_top_partners($p_date,$ttx_type) {
    require('../fact15/fact_config.php');
    $CoID = new mysqli($config['dbhost'], $config['dblogin'], $config['dbpass']);
    $CoID->select_db($config['dbname']);
    if ($ttx_type=='month') {
        echo "<div align='center'>
        <a href='index.php?tb=2&dt=", (substr(date('Y-m-d', strtotime("-3 month", strtotime($p_date))), 0, 8) . "01"), "' class='waves-effect waves-light btn hide-on-med-and-down'>", date('F y', strtotime("-3 month", strtotime($p_date))), "</a>
        <a href='index.php?tb=2&dt=", (substr(date('Y-m-d', strtotime("-2 month", strtotime($p_date))), 0, 8) . "01"), "' class='waves-effect waves-light btn hide-on-med-and-down'>", date('F y', strtotime("-2 month", strtotime($p_date))), "</a>
        <a href='index.php?tb=2&dt=", (substr(date('Y-m-d', strtotime("-1 month", strtotime($p_date))), 0, 8) . "01"), "' class='waves-effect waves-light btn'>", date('F y', strtotime("-1 month", strtotime($p_date))), "</a>
        <a href='index.php?tb=2&dt=", (substr(date('Y-m-d', strtotime("-0 month", strtotime($p_date))), 0, 8) . "01"), "' class='waves-effect waves-light btn blue'>", date('F y', strtotime("-0 month", strtotime($p_date))), "</a>";
        if (substr($p_date, 0, 8)!= substr($today, 0, 8)) {
         echo " <a href='index.php?tb=2&dt=", (substr(date('Y-m-d', strtotime("-0 month", strtotime($today))), 0, 8) . "01"), "' class='waves-effect waves-light btn grey'>", date('F y', strtotime("-0 month", strtotime($today))), "</a>";
        }
        echo "</div>";
        $bench_start_date = substr($p_date, 0, 8) . "01";
        $bench_end_date = substr(date('Y-m-d', strtotime("+1 month", strtotime($bench_start_date))), 0, 8) . "01";
    }
    elseif($ttx_type=='yesterday') {
        // Yesterday
        ?>
        <div align='center'> <button class="datepickerpartners waves-effect waves-light btn enex_lightblue"><i class="material-icons left">date_range</i>SWITCH DATE</button></div>
        <?php
        $bench_start_date = date('Y-m-d',strtotime("-1 days",strtotime($p_date))) ;
        $bench_end_date = date('Y-m-d',strtotime("-0 days",strtotime($p_date))) ;
    }
    elseif($ttx_type=='anyday') {
        // Any other single day
        ?>
        <div align='center'> <button class="datepickerpartners waves-effect waves-light btn enex_lightblue"><i class="material-icons left">date_range</i>SWITCH DATE</button></div>
        <?php
        $bench_start_date = date('Y-m-d',strtotime("0 days",strtotime($p_date))) ;
        $bench_end_date = date('Y-m-d',strtotime("+1 days",strtotime($p_date))) ;
    }
    elseif($ttx_type=='year') {
        echo "<div align='center'>
        <a href='index.php?tb=15&dt=", (substr(date('Y-m-d', strtotime("-1 year", strtotime($p_date))), 0, 5) . "01-01"), "' class='waves-effect waves-light btn'>", date('Y', strtotime("-1 year", strtotime($p_date))), "</a>
        <a href='index.php?tb=15&dt=", (substr(date('Y-m-d', strtotime("-0 month", strtotime($p_date))), 0, 5) . "01-01"), "' class='waves-effect waves-light btn blue'>", date('Y', strtotime("0 year", strtotime($p_date))), "</a>";
        if (substr($p_date, 0, 5)!= substr($today, 0, 5)) {
            echo " <a href='index.php?tb=15&dt=", (substr(date('Y-m-d', strtotime("-0 month", strtotime($today))), 0, 5) . "01-01"), "' class='waves-effect waves-light btn grey'>", date('Y', strtotime("-0 month", strtotime($today))), "</a>";
        }
        echo "</div>";
        $bench_start_date = substr($p_date,0,5)."01-01" ;
        $bench_end_date = substr(date('Y-m-d',strtotime("+1 year",strtotime($bench_start_date))),0,8)."01" ;
    }
    else {
        // latest 7 days
        $bench_start_date = date('Y-m-d',strtotime("-7 days",strtotime($today))) ;
        $bench_end_date = date('Y-m-d') ;
    }
    ?>
    <table class='display striped' id='topstories_monthtable' style='font-size:85%;'>
                    <thead>
                    <tr>
                        <th>Partner</th><th>Items</th><th>Hits</th>
                    </tr>
                    </thead> <tbody>
                    <?php
                    $sql = "SELECT tt_partner, COUNT(DISTINCT(tt_asset)) AS storycount,COUNT(tt_asset) AS hitcount FROM teletrax_hits WHERE tt_detection_start >= '".$bench_start_date."' AND tt_detection_start < '".$bench_end_date."' GROUP BY tt_partner ORDER BY storycount DESC";

                    $query = $CoID->query($sql);
                    while ( $row = $query->fetch_array()) {
                        ?>
                        <tr>
                            <td class="partnerid"><span><?php echo $row['tt_partner'];?></span>
                                <a style="cursor: pointer;" class="partnertrig tooltipped" style='cursor: pointer;' data-position='top' data-delay='20' data-tooltip='<?php echo $row['storycount']; ?> stories detected!' data-sdate="<?php echo $bench_start_date; ?>" data-edate="<?php echo $bench_end_date; ?>" ><i class="material-icons">announcement</i></a>
                            </td>
                            <td class="storycount" ><?php echo $row['storycount'] ;?></td>
                            <td><?php echo $row['hitcount']; ?></td>

                        </tr>
                        <?php
                    }

                    ?>
    </tbody>
    </table>
    <?php
    echo "<pre style='color:#999;'>",$sql,"</pre>";
    $CoID->close();
}
//********************************************
function ttx_item_hits($ttx_id, $bench_start_date,$bench_end_date) {
    require('../fact15/fact_config.php');
    $CoID = new mysqli($config['dbhost'], $config['dblogin'], $config['dbpass']);
    $CoID->select_db($config['dbname']);
    //$sqlpan1 = "SELECT * FROM teletrax_hits ORDER BY tt_detection_start desc limit 60 ";
    $sqlpan1 = "SELECT * FROM teletrax_hits WHERE source_id = $ttx_id and (tt_detection_start >= '" . $bench_start_date . " 00:00:00' AND tt_detection_start < '" . $bench_end_date . " 23:59:59' ) ORDER BY tt_detection_start asc ";
    ?>
    <table class='display striped' id='ttdetails' style='font-size:70%;'>
        <thead>
        <tr>
            <th>Hit Time</th><th>Story Date</th><th>Partner</th><th>Program</th><th>Duration</th><th>Asset</th><th>Source</th><th>Story</th><th>STEP ID</th>
        </tr>
        </thead> <tbody>
        <?php
        //echo $sqlpan1 ;
        $query = $CoID->query($sqlpan1);
        while ( $row = $query->fetch_array()) {
            if ($row['source_id']!= '-1') {
                $sqlsub = "select * from pex_story where story_step_id = '".$row['source_id']."'";
                $subquery = $CoID->query($sqlsub) ;
                if ($subrow =$subquery->fetch_array()) {$mstorydate = $subrow['storydate'];} else { $mstorydate = 'NO META REF!'; }
            }
            else { $mstorydate = 'NO META REF';}

            ?>
            <tr>
                <td><?php echo $row['tt_detection_start'] ;?></td>
                <td><?php echo $mstorydate; ?></td>
                <td><?php echo $row['tt_partner'] ;?></td>
                <td><?php echo $row['tt_program']; ?></td>
                <td><?php echo substr($row['tt_duration'],3); ?></td>
                <td><a class='tooltipped' style='cursor: pointer;' data-position='top' data-delay='20' data-tooltip='<?php echo $row['tt_asset']; ?>'><?php echo substr($row['tt_asset'],0,25); ?>...</a></td>
                <td><?php echo $row['source_partner']; ?></td>
                <td><strong><?php echo $row['source_title']; ?></strong></td>
                <td><?php echo $row['source_id']; ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php

    $CoID->close();
}
//********************************************
function ttx_benchmark_calc($bench_date) {
    // added 6.2.2018
    require('../fact15/fact_config.php');
    $CoID = new mysqli($config['dbhost'], $config['dblogin'], $config['dbpass']);
    $CoID->select_db($config['dbname']);
    if ($bench_date == $today) { $bench_date = date('Y-m-d', strtotime('-1 day'));}
    $bench = array();
    // cal all
    $sql = "SELECT count(*) as nritems  FROM pex_story where storyoutlook_status ='AVAILABLE' and storydate = '$bench_date' ";
    //echo $sql;
    $query = $CoID->query($sql);
    $row = $query->fetch_array();
    $bench['published'] = $row['nritems'];

    $sql = "SELECT count(*) as nritems  FROM pex_story where storyoutlook_status ='AVAILABLE' and storydate = '$bench_date' and story_teletrax_watermark = 1 ";
    //echo $sql;
    $query = $CoID->query($sql);
    $row = $query->fetch_array();
    $bench['watermarked'] = $row['nritems'];

    $sql = "SELECT count(*) as nritems  FROM pex_story where storyoutlook_status ='AVAILABLE' and storydate = '$bench_date' and story_teletrax = 1 ";
    //echo $sql;
    $query = $CoID->query($sql);
    $row = $query->fetch_array();
    $bench['detections'] = $row['nritems'];

    //$sql = "SELECT count(*) as nritems FROM pex_story where storyoutlook_status <>'AVAILABLE' and storydate = '$bench_date' and story_teletrax = 1 ";
    $bench['nometa'] = 0;
    //$sql = "SELECT count() as nritems FROM teletrax_hits where source_id = '-1' and tt_detection_start >= '".$bench_date." 00:00:00' AND tt_detection_start < '".$bench_date." 23:59:59' group by tt_asset";
    $sql= "SELECT COUNT(DISTINCT(tt_asset)) as nritems FROM teletrax_hits WHERE source_id = '-1' AND tt_detection_start >= '".$bench_date." 00:00:00' AND tt_detection_start < '".$bench_date." 23:59:59'";
    // SELECT COUNT(DISTINCT(tt_asset)) FROM teletrax_hits WHERE source_id = '-1' AND tt_detection_start > "2018-02-07"
    //echo $sql;
    $query = $CoID->query($sql);
    $row = $query->fetch_array();
    $bench['nometa'] = $row['nritems'];
    $sql = "SELECT count(*) as nritems FROM pex_story where storyoutlook_status <>'AVAILABLE' and storydate = '$bench_date' and story_teletrax = 1 ";
    $query = $CoID->query($sql);
    $row = $query->fetch_array();
    $bench['nometa'] = $bench['nometa']+$row['nritems'];

    // $sql = "delete from teletrax_benchmark where tt_bench_date='".$bench_date."'";
    $query = $CoID->query($sql);
    $sql = "insert into teletrax_benchmark (tt_bench_date,tt_bench_published,tt_bench_watermarked,tt_bench_detections,tt_bench_nometa) values ('".$bench_date."','".$bench['published']."','".$bench['watermarked']."','".$bench['detections']."','".$bench['nometa']."') 
    on duplicate key update tt_bench_published='".$bench['published']."',tt_bench_watermarked='".$bench['watermarked']."',tt_bench_detections='".$bench['detections']."' ,tt_bench_nometa='".$bench['nometa']."'";
    //echo $sql;
    $query = $CoID->query($sql);

    ?>
    <div class="center">
        <div align='center'> <button class="datepickerbench waves-effect waves-light btn enex_lightblue"><i class="material-icons left">date_range</i>SWITCH DATE</button></div>
        <h5><?php echo ctx_real_datestr($bench_date); ?></h5>
        <p>( Graphs below: Total nr of published ENEX items, Watermarked published items, Items detected on partner side, Detected non published items without Metadata)</p>
    </div>
    <div class="center" style="display: table;  margin: auto;">
        <div class="card-panel teal lighten-2 left"><h5 class="text-lighten-1">Published</h5><br> <h4 class="white-text"><?php echo $bench['published']; ?></h4></div>
        <div class="card-panel blue accent-2 left"><h5 class="text-lighten-1">Watermarked</h5><br><h4 class="white-text"><?php echo $bench['watermarked']; ?></h4></div>
        <div class="card-panel red lighten-1 left"><h5 class="text-lighten-1">Detections</h5><br><h4 class="white-text"><?php echo $bench['detections']; ?></h4></div>
        <div class="card-panel lime darken-1 left"><h5 class="text-lighten-1">No Meta</h5><br><h4 class="white-text"><?php echo $bench['nometa']; ?></h4></div>
    </div>
    <div class='clearfix'></div>
    <h5 class="center">Previous days </h5>
    <div class="center">
        <?php
        $sql = "SELECT * from teletrax_benchmark where tt_bench_date < '$bench_date' order by tt_bench_date desc limit 3 ";
        $query = $CoID->query($sql);
        while ( $row = $query->fetch_array()) {
            echo ctx_real_datestr($row['tt_bench_date'])," => [",$row['tt_bench_published'],"] [",$row['tt_bench_watermarked'],"] [",$row['tt_bench_detections'],"] <br>" ;
        }
        ?>
    </div>
    <div class="center" style="display: table;  margin: auto;" ><canvas id="myChart" width="1100" height="450"></canvas></div>
    <div class="clearfix"></div>
    <div class="center" style="display: table;  margin: auto;" ><canvas id="myChartline" width="1100" height="450"></canvas></div>
    <div class="center" style="display: table;  margin: auto;" ><canvas id="myChartcake" width="1100" height="450"></canvas></div>
    <!--Div that will hold the GOOGLE pie chart
    <div class="center" style="display: table;  margin: auto;" id="chart_div"></div>-->
    <h6 class="center">All detections on <?php echo ctx_real_datestr($bench_date); ?> including NO META (Global)</h6>
    <div class="center" id="regions_div" style="width: 900px; height: 500px; display: table;  margin: auto;"></div>
    <h6 class="center">All detections on <?php echo ctx_real_datestr($bench_date); ?> including NO META (Europe)</h6>
    <div class="center" id="europe_div" style="width: 900px; height: 500px; display: table;  margin: auto;"></div>
    <div class="center"><h5 >Detected stories published on <?php echo ctx_real_datestr($bench_date); ?></h5></div>
    <table class='display striped' id='ttdetails' style='font-size:80%;'>
    <thead>
    <tr>
        <th>Part.</th><th>Hits</th><th>Story</th><th>Source Date</th><th>Asset</th><th>Source</th><th>STEP ID</th>
    </tr>
    </thead> <tbody>
    <?php
    $tomorrow = date('Y-m-d',strtotime("+1 days",strtotime($bench_date))) ;
    $sql = "SELECT *,source_title,COUNT(DISTINCT(tt_partner)) AS topstation, COUNT(DISTINCT tt_asset) as storyhits, items.storyrecnr FROM teletrax_hits,pex_story items WHERE tt_detection_start >= '".$bench_date." 00:00:00' AND 
                            tt_detection_start < '".$tomorrow." 23:59:59'  AND source_id <> '-1' and source_date ='".$bench_date."' and source_id = items.story_step_id and items.storyoutlook_status='AVAILABLE' and items.story_teletrax=1 group by tt_asset ORDER BY topstation DESC";

    $query = $CoID->query($sql);
    while ( $row = $query->fetch_array()) {

        ?>
        <tr>
            <td><?php echo $row['topstation']; ?></td>
            <td><?php echo $row['storyhits']; ?></td>
            <td class="table_sourcetitle"><strong><a href='https://enex.lu/members/dopedetail/<?php echo $row['storyrecnr']; ?>' target='_blank'><?php echo $row['source_title']; ?></strong></a></strong>
                <a style="cursor: pointer;" class="modaltrig tooltipped" style='cursor: pointer;' data-position='top' data-delay='20' data-tooltip='<?php echo $row['topstation']; ?> Partners detected!' data-sdate="<?php echo $bench_date; ?>" data-edate="<?php echo $bench_date; ?>" ><i class="material-icons">announcement</i></a></td>
            <td class="table_sourcedate"><?php echo $row['source_date']; ?></td>
            <td><a class='tooltipped' style='cursor: pointer;' data-position='top' data-delay='20' data-tooltip='<?php echo $row['tt_asset']; ?>'><?php echo substr($row['tt_asset'],0,25); ?>...</a></td>
            <td><?php echo $row['source_partner']; ?></td>
            <td class="table_sourceid"><a href='index.php?tb=14&id=<?php echo $row['source_id']; ?>&dts=<?php echo $bench_date; ?>&dte=<?php echo $bench_date; ?>'><span><?php echo $row['source_id']; ?></span></a>
            </td>
        </tr>

        <?php
    }
    ?>
    </tbody>
    </table>

    <?php
    $CoID->close();

}
//********************************************
function ttx_nometa($bench_date) {
    // added 6.2.2018
    require('../fact15/fact_config.php');
    $CoID = new mysqli($config['dbhost'], $config['dblogin'], $config['dbpass']);
    $CoID->select_db($config['dbname']);
    $sqlpan1 = "SELECT * FROM teletrax_hits where source_id = '-1' group by tt_asset ORDER BY tt_detection_start desc limit 60 ";
    ?>
    <table class='display striped' id='ttdetails' style='font-size:80%;'>
        <thead>
        <tr>
            <th>Hit Time</th><th>Partner</th><th>Duration</th><th>Asset</th><th>Source</th><th>Story</th>
        </tr>
        </thead> <tbody>
        <?php
        //echo $sqlpan1 ;
        $query = $CoID->query($sqlpan1);
        while ( $row = $query->fetch_array()) {
            if ($row['source_id']!= '-1') {
                $sqlsub = "select * from pex_story where story_step_id = '".$row['source_id']."'";
                $subquery = $CoID->query($sqlsub) ;
                if ($subrow =$subquery->fetch_array()) {$mstorydate = $subrow['storydate'];} else { $mstorydate = 'NO META REF!'; }
            }
            else { $mstorydate = 'NO META REF';}

            ?>
            <tr>
                <td><?php echo $row['tt_detection_start'] ;?></td>
                <td><?php echo $row['tt_partner'] ;?></td>
                <td><?php echo substr($row['tt_duration'],3); ?></td>
                <td><a class='tooltipped' style='cursor: pointer;' data-position='top' data-delay='20' data-tooltip='<?php echo $row['tt_asset']; ?>'><?php echo substr($row['tt_asset'],0,50); ?>...</a></td>
                <td><?php echo $row['source_partner']; ?></td>
                <td><strong><?php echo $row['source_title']; ?></strong></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php

    $CoID->close();
}
//**********************************************
function ttx_top_stories_month($p_date,$ttx_type,$ttx_filter,$ttx_limit) {
    require('../fact15/fact_config.php');
    $CoID = new mysqli($config['dbhost'], $config['dblogin'], $config['dbpass']);
    $CoID->select_db($config['dbname']);
    if ($ttx_type=='month') {
        echo "<div align='center'>
        <a href='index.php?tb=3&dt=", (substr(date('Y-m-d', strtotime("-3 month", strtotime($p_date))), 0, 8) . "01"), "' class='waves-effect waves-light btn hide-on-med-and-down'>", date('F y', strtotime("-3 month", strtotime($p_date))), "</a>
        <a href='index.php?tb=3&dt=", (substr(date('Y-m-d', strtotime("-2 month", strtotime($p_date))), 0, 8) . "01"), "' class='waves-effect waves-light btn hide-on-med-and-down'>", date('F y', strtotime("-2 month", strtotime($p_date))), "</a>
        <a href='index.php?tb=3&dt=", (substr(date('Y-m-d', strtotime("-1 month", strtotime($p_date))), 0, 8) . "01"), "' class='waves-effect waves-light btn'>", date('F y', strtotime("-1 month", strtotime($p_date))), "</a>
        <a href='index.php?tb=3&dt=", (substr(date('Y-m-d', strtotime("-0 month", strtotime($p_date))), 0, 8) . "01"), "' class='waves-effect waves-light btn blue'>", date('F y', strtotime("-0 month", strtotime($p_date))), "</a>";
        if (substr($p_date, 0, 8)!= substr($today, 0, 8)) {
         echo " <a href='index.php?tb=3&dt=", (substr(date('Y-m-d', strtotime("-0 month", strtotime($today))), 0, 8) . "01"), "' class='waves-effect waves-light btn grey'>", date('F y', strtotime("-0 month", strtotime($today))), "</a>";
        }
        echo "</div>";
        $bench_start_date = substr($p_date,0,8)."01" ;
        $bench_end_date = substr(date('Y-m-d',strtotime("+1 month",strtotime($bench_start_date))),0,8)."01" ;
    }
    elseif($ttx_type=='yesterday') {
        // Yesterday
        ?>
        <div align='center'> <button class="datepicker waves-effect waves-light btn enex_lightblue"><i class="material-icons left">date_range</i>SWITCH DATE</button></div>
        <?php
        $bench_start_date = date('Y-m-d',strtotime("-1 days",strtotime($p_date))) ;
        $bench_end_date = date('Y-m-d',strtotime("-1 days",strtotime($p_date))) ;
    }
    elseif($ttx_type=='anyday') {
        // latest 20 days
        ?>
        <div align='center'> <button class="datepicker waves-effect waves-light btn enex_lightblue"><i class="material-icons left">date_range</i>SWITCH DATE</button></div>
        <?php
        $bench_start_date = date('Y-m-d',strtotime("0 days",strtotime($p_date))) ;
        $bench_end_date = date('Y-m-d',strtotime("-0 days",strtotime($p_date))) ;
    }
    elseif($ttx_type=='20days') {
        // latest 20 days
        $bench_start_date = date('Y-m-d',strtotime("-20 days",strtotime($today))) ;
        $bench_end_date = date('Y-m-d') ;
    }
    elseif($ttx_type=='year') {
        echo "<div align='center'>
        <a href='index.php?tb=11&dt=", (substr(date('Y-m-d', strtotime("-1 year", strtotime($p_date))), 0, 5) . "01-01"), "' class='waves-effect waves-light btn'>", date('Y', strtotime("-1 year", strtotime($p_date))), "</a>
        <a href='index.php?tb=11&dt=", (substr(date('Y-m-d', strtotime("-0 month", strtotime($p_date))), 0, 5) . "01-01"), "' class='waves-effect waves-light btn blue'>", date('Y', strtotime("0 year", strtotime($p_date))), "</a>";
        if (substr($p_date, 0, 5)!= substr($today, 0, 5)) {
            echo " <a href='index.php?tb=11&dt=", (substr(date('Y-m-d', strtotime("-0 month", strtotime($today))), 0, 5) . "01-01"), "' class='waves-effect waves-light btn grey'>", date('Y', strtotime("-0 month", strtotime($today))), "</a>";
        }
         echo "</div>";
        $bench_start_date = substr($p_date,0,5)."01-01" ;
        $bench_end_date = substr(date('Y-m-d',strtotime("+1 year - 1 day",strtotime($bench_start_date))),0,8)."31" ;
    }
    else {
        // latest 7 days
        $bench_start_date = date('Y-m-d',strtotime("-7 days",strtotime($today))) ;
        $bench_end_date = date('Y-m-d') ;
    }
    $ttx_condition = "";
    if ($ttx_filter == "PARTNERS") {$ttx_condition = " and source_partner <> 'Sourced by ENEX' and source_partner <> 'POOL' "; }
    if ($ttx_filter == "3RDPARTY") {$ttx_condition = " and (source_partner = 'Sourced by ENEX' or source_partner = 'POOL') "; }

    ?>
    <table class='display striped' id='ttdetails' style='font-size:80%;'>
                    <thead>
                    <tr>
                        <th>Part.</th><th>Hits</th><th>Story</th><th>Date</th><th>Asset</th><th>Source</th><th>STEP ID</th>
                    </tr>
                    </thead> <tbody>
                    <?php

                    $sql = "SELECT *,source_title,COUNT(DISTINCT(tt_partner)) AS topstation, COUNT(tt_asset) as storyhits, items.storyrecnr FROM teletrax_hits,pex_story items WHERE tt_detection_start >= '".$bench_start_date." 00:00:00' AND 
                            tt_detection_start < '".$bench_end_date." 23:59:59'  AND source_id <> '-1' ".$ttx_condition." and source_id = items.story_step_id group by tt_asset ORDER BY topstation DESC limit ".$ttx_limit;

                    $query = $CoID->query($sql);
                    while ( $row = $query->fetch_array()) {

                        ?>
                        <tr>
                            <td><?php echo $row['topstation']; ?></td>
                            <td><?php echo $row['storyhits']; ?></td>
                            <td class="table_sourcetitle"><strong><a href='https://enex.lu/members/dopedetail/<?php echo $row['storyrecnr']; ?>' target='_blank'><?php echo $row['source_title']; ?></strong></a></strong>
                                <a style="cursor: pointer;" class="modaltrig tooltipped" style='cursor: pointer;' data-position='top' data-delay='20' data-tooltip='<?php echo $row['topstation']; ?> Partners detected!' data-sdate="<?php echo $bench_start_date; ?>" data-edate="<?php echo $bench_end_date; ?>" ><i class="material-icons">announcement</i></a></td>
                            <td class="table_sourcedate"><?php echo $row['source_date']; ?></td>
                            <td><a class='tooltipped' style='cursor: pointer;' data-position='top' data-delay='20' data-tooltip='<?php echo $row['tt_asset']; ?>'><?php echo substr($row['tt_asset'],0,25); ?>...</a></td>
                            <td><?php echo $row['source_partner']; ?></td>
                            <td class="table_sourceid"><a href='index.php?tb=14&id=<?php echo $row['source_id']; ?>&dts=<?php echo $bench_start_date; ?>&dte=<?php echo $bench_end_date; ?>'><span><?php echo $row['source_id']; ?></span></a>
                                </td>
                        </tr>
                        <?php
                    }
                    ?>
    </tbody>
    </table>
    <?php
    echo "<pre style='color:#999;'>",$sql,"</pre>";
    $ajax_sdate = $bench_start_date ;
    $ajax_edate = $bench_end_date ;
    $CoID->close();
}

//********************************************
function ttx_latest() {
 // Latest hits unfiltered, created 1.1.2017
    require('../fact15/fact_config.php');
    $CoID = new mysqli($config['dbhost'], $config['dblogin'], $config['dbpass']);
    $CoID->select_db($config['dbname']);
    $sqlpan1 = "SELECT * FROM teletrax_hits ORDER BY tt_detection_start desc limit 60 ";
    ?>
    <table class='display striped' id='ttdetails' style='font-size:80%;'>
                    <thead>
                    <tr>
                        <th>Hit Time</th><th>Story Date</th><th>Partner</th><th>Program</th><th>Duration</th><th>Asset</th><th>Source</th><th>Story</th><th>STEP ID</th>
                    </tr>
                    </thead> <tbody>
                    <?php
                    //echo $sqlpan1 ;
                    $query = $CoID->query($sqlpan1);
                    while ( $row = $query->fetch_array()) {
                        if ($row['source_id']!= '-1') {
                            $sqlsub = "select * from pex_story where story_step_id = '".$row['source_id']."'";
                            $subquery = $CoID->query($sqlsub) ;
                            if ($subrow =$subquery->fetch_array()) {$mstorydate = $subrow['storydate'];} else { $mstorydate = 'NO META REF!'; }
                        }
                        else { $mstorydate = 'NO META REF';}

                        ?>
                        <tr>
                            <td><?php echo $row['tt_detection_start'] ;?></td>
                            <td><?php echo $mstorydate; ?></td>
                            <td><?php echo $row['tt_partner'] ;?></td>
                            <td><?php echo $row['tt_program']; ?></td>
                            <td><?php echo substr($row['tt_duration'],3); ?></td>
                            <td><a class='tooltipped' style='cursor: pointer;' data-position='top' data-delay='20' data-tooltip='<?php echo $row['tt_asset']; ?>'><?php echo substr($row['tt_asset'],0,25); ?>...</a></td>
                            <td><?php echo $row['source_partner']; ?></td>
                            <td><strong><?php echo $row['source_title']; ?></strong></td>
                            <td><?php echo $row['source_id']; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
    </tbody>
    </table>
    <?php
    $CoID->close();
}
;


//********************************************
function fact15_teletrax($fact_id, $p_date) {
    // Fingerprint & Watermark reports - added 01.12.2017
    require('../fact15/fact_config.php');
    $CoID = new mysqli($config['dbhost'], $config['dblogin'], $config['dbpass']);
    $CoID->select_db($config['dbname']);
    if ($fact_id == 0 ) {
        $sqlpan1 = "SELECT * FROM teletrax_hits WHERE (tt_detection_start >= '".$p_date." 00:00:00' AND tt_detection_start < '".$p_date." 23:59:59' )  ORDER BY tt_detection_start asc ";;
    } else {
        if ($p_date == $today) {
            $sqlpan1 = "SELECT * FROM teletrax_hits WHERE source_id = $fact_id ORDER BY tt_detection_start asc ";
        } else {
            $sqlpan1 = "SELECT * FROM teletrax_hits WHERE source_id = $fact_id and (tt_detection_start >= '" . $p_date . " 00:00:00' AND tt_detection_start < '" . $p_date . " 23:59:59' ) ORDER BY tt_detection_start asc ";
            $query = $CoID->query($sqlpan1);
            $row = $query->fetch_array();
            $p_date = substr($row['tt_detection_start'], 0, 10);
        }
    }


    $bench_start_date = substr($p_date,0,8)."01" ;
    $bench_end_date = substr(date('Y-m-d',strtotime("+1 month",strtotime($bench_start_date))),0,8)."01" ;


    ?>
    <div >
    <div class="small-12 large-centered columns">
    <div class="">
        <div class='button info radius' style='float:right;'>Teletrax Date  <input type='text' placeholder="Select Teletrax Date"  id='teletrax_date' value='<?php echo $p_date ; ?>'/></div>
        <h3>Teletrax Watermarks <strong><?php
                if ($fact_id == 0) {
                    echo date("l, d F Y",strtotime($p_date));
                }
                else {
                    echo "Media ID :", $fact_id ;
                }

                ?></strong></h3>

        <ul class="tabs" data-tab>
            <li class="tab-title active"><a href="#panel1">Hit Details <strong></strong></a></li>
            <li class="tab-title "><a href="#panel2">Hits per Partner<strong></strong></a></li>
            <li class="tab-title "><a href="#panel3">Top Stories<strong></strong></a></li>
            <li class="tab-title "><a href="#panel4">Top Stories <strong><?php echo date("F Y",strtotime($bench_start_date)); ?></strong></a></li>
            <li class="tab-title "><a href="#panel5">Hits per partner <strong><?php echo date("F Y",strtotime($bench_start_date)); ?></strong></a></li>

        </ul>
        <div class="tabs-content" >
            <div class="content active" id="panel1">
                <table class='display striped' id='ttdetails'>
                    <thead>
                    <tr>
                        <th>Hit Time</th><th>Story Date</th><th>Partner</th><th>Program</th><th>Duration</th><th>Asset</th><th>Source</th><th>Story</th><th>STEP ID</th>
                    </tr>
                    </thead> <tbody>
                    <?php
                    //echo $sqlpan1 ;

                    $query = $CoID->query($sqlpan1);
                    while ( $row = $query->fetch_array()) {
                        if ($row['source_id']!= '-1') {
                            $sqlsub = "select * from pex_story where story_step_id = '".$row['source_id']."'";
                            $subquery = $CoID->query($sqlsub) ;
                            if ($subrow =$subquery->fetch_array()) {$mstorydate = $subrow['storydate'];} else { $mstorydate = 'NO META REF!'; }
                        }
                        else { $mstorydate = 'NO META REF';}

                        ?>
                        <tr>
                            <td><?php echo $row['tt_detection_start'] ;?></td>
                            <td><?php echo $mstorydate; ?></td>
                            <td><?php echo $row['tt_partner'] ;?></td>
                            <td><?php echo $row['tt_program']; ?></td>
                            <td><?php echo substr($row['tt_duration'],3); ?></td>
                            <td><?php echo $row['tt_asset']; ?></td>
                            <td><?php echo $row['source_partner']; ?></td>
                            <td><?php echo $row['source_title']; ?></td>
                            <td><?php echo $row['source_id']; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>

            </div>

            <div class="content center" id="panel2" >
                <table class='display'>
                    <thead>
                    <tr>
                        <th>Partner</th><th>Items</th><th>Hits</th>
                    </tr>
                    </thead> <tbody>
                    <?php
                    $sql = "SELECT tt_partner, COUNT(DISTINCT(tt_asset)) AS storycount,COUNT(tt_asset) AS hitcount FROM teletrax_hits WHERE tt_detection_start >= '".$p_date." 00:00:00' AND tt_detection_start < '".$p_date." 23:59:59' GROUP BY tt_partner ORDER BY storycount DESC";
                    // echo $sql;
                    $query = $CoID->query($sql);
                    while ( $row = $query->fetch_array()) {
                        ?>
                        <tr>
                            <td><?php echo $row['tt_partner'] ;?></td>
                            <td><?php echo $row['storycount'] ;?></td>
                            <td><?php echo $row['hitcount']; ?></td>

                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>

            <div class="content" id="panel3">
                <table class='display' id='ttdetails'>
                    <thead>
                    <tr>
                        <th>Partners</th><th>Hits</th><th>Story</th><th>Asset</th><th>Source</th><th>STEP ID</th>
                    </tr>
                    </thead> <tbody>
                    <?php
                    $sql = "SELECT *,source_title,COUNT(DISTINCT(tt_partner)) AS topstation, COUNT(tt_asset) as storyhits FROM teletrax_hits WHERE tt_detection_start >= '".$p_date." 00:00:00' AND tt_detection_start < '".$p_date." 23:59:59' AND source_id <> '-1' group by tt_asset ORDER BY topstation DESC";
                    // echo $sql;
                    $query = $CoID->query($sql);
                    while ( $row = $query->fetch_array()) {
                        ?>
                        <tr>
                            <td><?php echo $row['topstation']; ?></td>
                            <td><?php echo $row['storyhits']; ?></td>
                            <td><strong><?php echo $row['source_title']; ?></strong></td>
                            <td><?php echo $row['tt_asset']; ?></td>
                            <td><?php echo $row['source_partner']; ?></td>
                            <td><a href='index.php?dt=<?php echo $p_date; ?>&tb=14&id=<?php echo $row['source_id']; ?>'><?php echo $row['source_id']; ?></a></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>

            </div>
            <div class="content" id="panel4">
                <table class='display' id='ttdetails'>
                    <thead>
                    <tr>
                        <th>Partners</th><th>Hits</th><th>Story</th><th>Asset</th><th>Source</th><th>STEP ID</th>
                    </tr>
                    </thead> <tbody>
                    <?php
                    $sql = "SELECT *,source_title,COUNT(DISTINCT(tt_partner)) AS topstation, COUNT(tt_asset) as storyhits FROM teletrax_hits WHERE tt_detection_start >= '".$bench_start_date." 00:00:00' AND 
                            tt_detection_start < '".$bench_end_date." 23:59:59'  AND source_id <> '-1' group by tt_asset ORDER BY topstation DESC ";
                    //echo $sql;
                    $query = $CoID->query($sql);
                    while ( $row = $query->fetch_array()) {

                        ?>
                        <tr>
                            <td><?php echo $row['topstation']; ?></td>
                            <td><?php echo $row['storyhits']; ?></td>
                            <td><strong><?php echo $row['source_title']; ?></strong></td>
                            <td><?php echo $row['tt_asset']; ?></td>
                            <td><?php echo $row['source_partner']; ?></td>
                            <td><a href='index.php?tb=14&id=<?php echo $row['source_id']; ?>'><?php echo $row['source_id']; ?></a></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>

            </div>
            <div class="content center" id="panel5" >
                <table class='display'>
                    <thead>
                    <tr>
                        <th>Partner</th><th>Items</th><th>Hits</th>
                    </tr>
                    </thead> <tbody>
                    <?php
                    $sql = "SELECT tt_partner, COUNT(DISTINCT(tt_asset)) AS storycount,COUNT(tt_asset) AS hitcount FROM teletrax_hits WHERE tt_detection_start >= '".$bench_start_date."' AND tt_detection_start < '".$bench_end_date."' GROUP BY tt_partner ORDER BY storycount DESC";
                    // echo $sql;
                    $query = $CoID->query($sql);
                    while ( $row = $query->fetch_array()) {
                        ?>
                        <tr>
                            <td><?php echo $row['tt_partner'] ;?></td>
                            <td><?php echo $row['storycount'] ;?></td>
                            <td><?php echo $row['hitcount']; ?></td>

                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>


        </div>


        <div></div></div>
    <?php
    $CoID->close();
}

