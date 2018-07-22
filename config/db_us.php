<?php
  // DB Params
##
date_default_timezone_set('America/Chicago');
##

// find if its production and connect to the correct database
$Production =  (stristr($_SERVER['SERVER_NAME'], "durgabari.net") != "");

if ($Production == TRUE ) {

	$DB_HOST='durgabarius.ipagemysql.com';
	$DB_NAME='hdbs_prod';          ## usually like this: prefix_dbName
	$DB_USER='hdbs_admin_17';      ## usually like this: prefix_name
	$DB_PASS='65Us~%.Ln8(Fm]Jj';	   ## must be already encrypted (recommended)

} else {

    $DB_HOST='localhost';       /* usually localhost                          */
    $DB_NAME='hdbs_uat1';          /* usually like this: prefix_dbName           */
    $DB_USER='testdurgabarius';            /* usually like this: prefix_name             */
    $DB_PASS='testdurgabarius';                /* must be already enscrypted (recommended)   */

}

$incOID = 70000;


?>