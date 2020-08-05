<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/settings/config.php';
?>
<!DOCTYPE html>
<html lang="ru">
 <head>
  <title>Todo</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
  <link rel="stylesheet preload" type="text/css" href="<?=$http?>://<?=$site_url?>/styles/bootstrap.min.css?<?=filemtime($site_path.'/styles/bootstrap.min.css')?>" />
  <link rel="stylesheet preload" type="text/css" href="<?=$http?>://<?=$site_url?>/styles/style.css?<?=filemtime($site_path.'/styles/style.css')?>" />
  <link rel="shortcut icon preload" type="image/x-icon" href="<?=$http?>://<?=$site_url?>/favicon.ico?<?=filemtime($site_path.'/favicon.ico')?>">
 </head>
 <body>

 <div class="container">
  <div class="row mt-4">
   <div class="col-md-4">
<!-- Подключение формы для авторизации администратора -->
<?php include_once($site_path.'/modules/col_left.php') ?>
   </div>
   <div class="col-md-8">
<!-- Подключение списка задач -->
<?php include_once($site_path.'/modules/col_todolist.php') ?>
   </div>
  </div>
 </div>

 <script src="<?=$http?>://<?=$site_url?>/scripts/bootstrap.min.js"></script>
 <script>
  $('.edit-item').click(function()
  {
   $(this).parent('p').next().next('.edit-item-block').slideToggle();
  });
 </script>
 
</body>
</html>