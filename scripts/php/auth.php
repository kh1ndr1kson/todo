<?php
if(isset($_POST['auth']))
{
	$user_name = mysqli_post_injection($db_connect, $_POST['name']);
	$user_password = md5(mysqli_post_injection($db_connect, $_POST['password']));

	$count = mysqli_count($db_connect, 'site_users', 'name = "'.$user_name.'" AND password = "'.$user_password.'"');

	if($count > 0)
	{
		$user_result = mysqli_query($db_connect, 'SELECT * FROM site_users WHERE name = "'.$user_name.'" AND password = "'.$user_password.'"');
		$row = mysqli_fetch_array($user_result);
		{
			$_SESSION['user_id'] = $row['id'];
			$_SESSION['user_name'] = $row['name'];
			$_SESSION['user_email'] = $row['email'];
			$_SESSION['user_status'] = $row['status'];
		}
		mysqli_free_result($user_result);

		$user_accept = 'Авторизация прошла успешно!';
	}
	else
	{
		$user_error = 'Неверный логин или пароль';
	}
}

if(isset($_POST['exit']))
{
 unset($_SESSION['user_id']);
 unset($_SESSION['user_name']);
 unset($_SESSION['user_email']);
 unset($_SESSION['user_status']);
}
?>