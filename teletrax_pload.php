<?php
/**
 * Created by PhpStorm.
 * User: Ralph
 * Date: 25.01.18
 * Time: 22:59
 */
echo $_POST['sourceid'];
require('../fact15/fact_config.php');
$CoID = new mysqli($config['dbhost'], $config['dblogin'], $config['dbpass']);
$CoID->select_db($config['dbname']);
$sql="SELECT * from partners  order by partner_code" ;
$result = $CoID->query($sql) ;

while ($row  =  $result->fetch_object()) {
    echo $row->partner_code,"<br>";
}
$CoID->close();
