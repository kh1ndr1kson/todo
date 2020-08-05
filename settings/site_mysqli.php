<?php
$db_location = 'localhost';
$db_user = 'h95172gs_test';
$db_password = 'naXZdif8';
$db_name = 'h95172gs_test';
$db_connect = mysqli_connect($db_location, $db_user, $db_password, $db_name);

mysqli_set_charset($db_connect, 'utf8');

if (!$db_connect)
{ 
 printf("Невозможно подключиться к базе данных. Код ошибки: %s\n", mysqli_connect_error()); 
 exit; 
}