<?php
function mysqli_post_injection($link, $string)
{
 $string = trim($string);
 $string = stripslashes($string);
 $string = mysqli_real_escape_string($link, $string);
 return $string;
}

function mysqli_insert($link, $table_name, $data)
{
 if (empty($link) or empty($table_name) or empty($data))
 {
  return false;
 }
 elseif(!is_array($data) or !is_string($table_name))
 {
  return false;
 }
 else
 {
  $keys_arr = array();
  $values_arr = array(); 
  foreach ($data as $key => $value) 
  {
   $keys_arr[] = $key;
   $values_arr[] = "'".$value."'";
  }
  $keys = implode(', ', $keys_arr);
  $values = implode(', ', $values_arr);

  $mysqli_result = mysqli_query($link, "INSERT INTO ".$table_name." (".$keys.") VALUES (".$values.")");

  if ($mysqli_result)
  {
   return mysqli_insert_id($link);
  }
  else
  {
   return $mysqli_result;
  }
 }
}

function mysqli_update_id($link, $table_name, $data, $where_id)
{
 if (empty($link) or empty($table_name) or empty($data))
 {
  return false;
 }
 elseif(!is_array($data) or !is_string($table_name) or !is_numeric($where_id))
 {
  return false;
 }
 else
 {
  $updates_arr = array(); 
  foreach ($data as $key => $value) 
  {
   $updates_arr[] = $key."='".$value."'";
  }

  $updates = implode(', ', $updates_arr);

  $mysqli_result = mysqli_query($link, "UPDATE ".$table_name." SET ".$updates." WHERE id = '".$where_id."'");

  return $mysqli_result;
 }
}

function mysqli_delete_id($link, $table_name, $where_id)
{
 if(empty($link) or empty($table_name) or empty($where_id))
 {
  return false;
 }
 elseif(!is_string($table_name) or !is_numeric($where_id))
 {
  return false;
 }
 else
 {
  $mysqli_result = mysqli_query($link, "DELETE FROM ".$table_name." WHERE id = '".$where_id."'");

  if (!$mysqli_result)
  {
   return false;
  }

  $count = mysqli_affected_rows($link);
  if ($count == -1)
  {
   return false;
  }

  return $count;
 }
}

function mysqli_count($link, $table_name, $where = '1')
{
 if (empty($link) or empty($table_name))
 {
  return false;
 }
 elseif(!is_string($table_name))
 {
  return false;
 }
 else
 {
  $mysqli_result = mysqli_query($link, "SELECT COUNT(*) AS counts FROM ".$table_name." WHERE ".$where."");

  $row = mysqli_fetch_array($mysqli_result);

  $count = $row['counts'];

  mysqli_free_result($mysqli_result);
  return $count;
 } 
}

function post_echo($string)
{
 $string = trim($string);
 $string = stripslashes($string);
 $string = htmlspecialchars($string);
 return $string;
}

function links_bar($page_link, $page, $count, $pages_count, $show_link)
{
 if ($pages_count == 1) return false;
 $sperator = " ";
 $begin = $page - intval($show_link / 2);
 unset($show_dots);
 if ($pages_count <= $show_link + 1) $show_dots = "no";
 if (($begin > 2) && ($pages_count - $show_link > 2))
 {
  echo '<li class="page-item"><a class="page-link" href="/page/1/">Первая страница</a></li> ';
 }
 for ($j = 0; $j <= $show_link; $j++)
 {
  $i = $begin + $j;
  if ($i < 1) continue;
  if (!isset($show_dots) && $begin > 1)
  {
   echo ' <li class="page-item"><a class="page-link" href="/page/'.($i-1).'/">…</a><li> ';
   $show_dots = "no";
  }
  if ($i > $pages_count) break;
  if ($i == $page)
  {
   echo ('<li class="page-item"><a class="page-link active">'.$i.'</a></li>');
  }
  else
  {
   if ($i == 1)
   {
    echo ' <li class="page-item"><a class="page-link" href="/page/'.$i.'/">'.$i.'</a><li> ';
   }
   else 
   {
    echo ' <li class="page-item"><a class="page-link" href="/page/'.$i.'/">'.$i.'</a><li> ';
   }
  }
  if (($i != $pages_count) && ($j != $show_link)) echo $sperator;
  if (($j == $show_link) && ($i < $pages_count))
  {
   echo '<li class="page-item"><a class="page-link" href="/page/'.($i+1).'/">…</a></li> ';
  }
 }
 if ($begin + $show_link + 1 < $pages_count)
 {
  echo ' <li class="page-item"><a class="page-link" href="/page/'.$pages_count.'/">Последняя страница</a><li>';
 }
 return true;
}