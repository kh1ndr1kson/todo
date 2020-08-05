<!-- Подключение скриптов для обработки -->
<?php include_once($site_path.'/scripts/php/auth.php');

if(!empty($_SESSION['user_id']))
{
 $auth_user_id = $_SESSION['user_id'];
}
else
{
 $auth_user_id = 0;
}

if(!empty($auth_user_id))
{
 echo '<h2>Профиль</h2>'."\n";

 if(!empty($user_accept)) echo '<p class="mb-2 text-success">'.$user_accept.'</p>'."\n";
 if(!empty($user_error)) echo '<p class="mb-2 text-danger">'.$user_error.'</p>'."\n";

 $users_result = mysqli_query($db_connect, 'SELECT * FROM site_users WHERE id = '.$auth_user_id);
 $row = mysqli_fetch_array($users_result);
 {
  $user_name = $row['name'];
  $user_email = $row['email'];
  $user_status = $row['status'];
 }
 mysqli_free_result($users_result);

 if($user_status == 1) $status_name = 'администратор';
 else $status_name = 'Обычный пользователь';

 echo '<p class="mb-3 text-muted small">'.$status_name.'</p>
 <p class="mb-2">Имя: '.$user_name.'</p>
 <p class="mb-2">E-mail: <a href="mailto:'.$user_email.'">'.$user_email.'</a></p>
 <form method="post"><input class="btn btn-danger btn-sm m-0" type="submit" name="exit" value="Выход"></form>'."\n";
}
else
{
?>
<h2>Авторизация</h2>
<p class="mb-3 text-muted small">для администратора</p>
<?php
if(!empty($user_accept)) echo '<p class="mb-2 text-success">'.$user_accept.'</p>'."\n";
if(!empty($user_error)) echo '<p class="mb-2 text-danger">'.$user_error.'</p>'."\n";
?>
<div id="formGroup" class="form-group">
 <form method="post">
  <input class="form-control mb-3" type="text" id="name" name="name" placeholder="Логин" required="required">
  <input class="form-control mb-3" type="password" id="password" name="password" placeholder="Пароль" required="required">
  <input class="btn btn-primary btn-sm btn-block" type="submit" name="auth" value="Вход">
 </form>
</div>
<?php
 $user_name = 'Анонимный пользователь';
 $user_email = null;
 $user_status = null;
}
?>