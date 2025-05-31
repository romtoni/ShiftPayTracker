<?
ob_start(); 
session_start();
if($_SESSION["server_user"]=="") header("location:../.")
?>