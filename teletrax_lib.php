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
// *******************************************
function ttx_top20_month($p_date) {
    require('../fact15/fact_config.php');
    $CoID = new mysqli($config['dbhost'], $config['dblogin'], $config['dbpass']);
    $CoID->select_db($config['dbname']);
    $bench_start_date = substr($p_date,0,8)."01" ;
    $bench_end_date = substr(date('Y-m-d',strtotime("+1 month",strtotime($bench_start_date))),0,8)."01" ;
    ?>
    <table class='display striped' id='topstories_monthtable' style='font-size:85%;'>
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
    <?php
    $CoID->close();
}
//********************************************
function ttx_top_stories_month($p_date) {
    require('../fact15/fact_config.php');
    $CoID = new mysqli($config['dbhost'], $config['dblogin'], $config['dbpass']);
    $CoID->select_db($config['dbname']);
    $bench_start_date = substr($p_date,0,8)."01" ;
    $bench_end_date = substr(date('Y-m-d',strtotime("+1 month",strtotime($bench_start_date))),0,8)."01" ;
    ?>
    <table class='display' id='ttdetails' style='font-size:80%;'>
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
    <?php
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
                <table class='display' id='ttdetails'>
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