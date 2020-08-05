<?php
session_start();
error_reporting (-1);

$http = $_SERVER['REQUEST_SCHEME'];
$site_url = $_SERVER['HTTP_HOST'];
$site_path = $_SERVER['DOCUMENT_ROOT'];
$current_url = $_SERVER['REQUEST_URI'];
$current_script = $_SERVER['PHP_SELF'];

require_once $_SERVER['DOCUMENT_ROOT'].'/settings/site_mysqli.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/settings/site_functions.php';

if(!empty($_GET['page']))
{
 $get_page = mysqli_real_escape_string($db_connect, $_GET['page']);

 if(!$get_page)
 {
  $get_page = 1;
 }
}
else
{
 $get_page = 1;
}