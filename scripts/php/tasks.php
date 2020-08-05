<?php
// Добавить новую задачу
if(isset($_POST['add_task']))
{
	$post['name'] = mysqli_post_injection($db_connect, $_POST['name']);
	$post['email'] = mysqli_post_injection($db_connect, $_POST['email']);
	$post['task'] = mysqli_post_injection($db_connect, $_POST['task']);
	$post['user_id'] = mysqli_post_injection($db_connect, $_POST['user_id']);
	$post['status'] = 1; // новая задача

	if(empty($post['name'])) $post['name'] = 'Анонимный пользователь';

	if(!empty($post['user_id']))
	{
	 $users_result = mysqli_query($db_connect, 'SELECT * FROM site_users WHERE id = '.$post['user_id']);
	 $row = mysqli_fetch_array($users_result);
	 {
	  $post['name'] = $row['name'];
	  $post['email'] = $row['email'];
	 }
	 mysqli_free_result($users_result);
	}

	if(!empty($post['task']))
	{
		$insert_id = mysqli_insert($db_connect, 'site_tasks', $post);

		$accept = 'Задача успешно добавлена';
	}
	else
	{
		$error = 'Вы не указали задачу';
	}
}

// Изменить существующую задачу
if(isset($_POST['edit_task']))
{
	$task_id = mysqli_post_injection($db_connect, $_POST['edit_task']);
	$post['task'] = mysqli_post_injection($db_connect, $_POST['task']);
	$post['status'] = (int)$_POST['status'];

	if(!empty($post['task']) and !empty($post['status']))
	{
		mysqli_update_id($db_connect, 'site_tasks', $post, $task_id);

		$accept = 'Задача успешно обновлена';
	}
	else
	{
		$error = 'Вы не указали задачу';
	}
}
?>