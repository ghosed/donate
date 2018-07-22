<?php
  // DB Params
##
date_default_timezone_set('America/Chicago');
##

// find if its production and connect to the correct database
$OnWeb =  (stristr($_SERVER['SERVER_NAME'], "durgabari.net") != "");

if ($OnWeb == TRUE ) {

	$DB_HOST='durgabarinet.ipagemysql.com';
    $DB_NAME='hdbs_uat1';          /* usually like this: prefix_dbName           */
    $DB_USER='testdurgabarius';            /* usually like this: prefix_name             */
    $DB_PASS='testdurgabarius';                /* must be already enscrypted (recommended)   */

} else {

    $DB_HOST='localhost';       /* usually localhost                          */
    $DB_NAME='hdbs_uat1';          /* usually like this: prefix_dbName           */
    $DB_USER='testdurgabarius';            /* usually like this: prefix_name             */
    $DB_PASS='testdurgabarius';                /* must be already enscrypted (recommended)   */

}

$incOID = 50000;

?>