<?php
/**
 * Created by PhpStorm.
 * User: Ralph
 * Date: 25.01.18
 * Time: 22:59
 */
// created 25.1.2018 updated 27.1.2018 17:58
//echo $_POST['sourceid'];
//echo "<br>",$_POST['sdate'], " - ",$_POST['edate'];
require('../fact15/fact_config.php');
$CoID = new mysqli($config['dbhost'], $config['dblogin'], $config['dbpass']);
$CoID->select_db($config['dbname']);
$sdate = $_POST['sdate'] ;
$edate = $_POST['edate'] ;

if (isset($_POST['sourceid'])) {
    // AJAX load of Partners detected in story (STORIES section)
    $ttx_id = $_POST['sourceid'];
    $sql = "SELECT distinct tt_partner FROM teletrax_hits WHERE source_id = $ttx_id and (tt_detection_start >= '" . $sdate . " 00:00:00' AND tt_detection_start < '" . $edate . " 23:59:59' ) ORDER BY tt_partner asc ";
    //echo $sql;
    $result = $CoID->query($sql) ;
    echo "Item has been detected at : <br>(Date range : $sdate => $edate )";
    echo "<ol>";
    while ($row  =  $result->fetch_array()) {
        echo "<li style='color:#336699; '><strong>",$row['tt_partner'],"</strong></li>" ;
    }
    echo "</ol>";
}
else {
    // AJAX load of stories detected at partner (PARTNERS section)
    $ttx_id = $_POST['partnerid'];
    echo $_POST['partnerid'];
    echo "<br>",$_POST['sdate'], " - ",$_POST['edate'] ;

}
$CoID->close();
