<?php
/**
 * Created by PhpStorm.
 * User: Ralph
 * Date: 25.01.18
 * Time: 22:59
 */
//echo $_POST['sourceid'];
//echo "<br>",$_POST['sdate'], " - ",$_POST['edate'];

$ttx_id = $_POST['sourceid'];
$sdate = $_POST['sdate'] ;
$edate = $_POST['edate'] ;

require('../fact15/fact_config.php');
$CoID = new mysqli($config['dbhost'], $config['dblogin'], $config['dbpass']);
$CoID->select_db($config['dbname']);
//$sql ="SELECT * from partners  order by partner_code" ;
$sql = "SELECT distinct tt_partner FROM teletrax_hits WHERE source_id = $ttx_id and (tt_detection_start >= '" . $sdate . " 00:00:00' AND tt_detection_start < '" . $edate . " 23:59:59' ) ORDER BY tt_partner asc ";
//echo $sql;
$result = $CoID->query($sql) ;
echo "Item has been detected at : <br>(Date range : $sdate => $edate )";
echo "<ol>";
while ($row  =  $result->fetch_array()) {
    //echo $row->partner_code,"<br>";
    echo "<li style='color:#336699; '><strong>",$row['tt_partner'],"</strong></li>" ;
}
echo "</ol>";
$CoID->close();
