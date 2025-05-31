<?
$dbuser="USER_PIKET";  
$dbpass="admin";
$tnsserver="localhost:1521/orclpdb";
$conn=oci_connect($dbuser,$dbpass,$tnsserver);
error_reporting(0);
?>