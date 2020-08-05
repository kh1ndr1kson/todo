<!-- Подключение скриптов для обработки -->
<?php include_once($site_path.'/scripts/php/tasks.php');

if(!empty($_SESSION['user_id']))
{
 $auth_user_id = $_SESSION['user_id'];
}
else
{
	$auth_user_id = 0;
}

if(isset($_POST['sort_name']))
{
	$sort_name = $_POST['sort_name'];
	$sort_name_query = 'WHERE name = "'.$_POST['sort_name'].'"';
}

if(isset($_POST['sort_email']))
{
	$sort_email = $_POST['sort_email'];
	$sort_email_query = 'WHERE email = "'.$_POST['sort_email'].'"';
}

if(isset($_POST['sort_status']))
{
	$sort_status = $_POST['sort_status'];
	$sort_status_query = 'WHERE status = "'.$_POST['sort_status'].'"';
}

$site_url_t = $site_url;
$temp = explode('/', $_SERVER['REQUEST_URI']);
if(count($temp) > 2)
$get_page = $temp[2];

if(empty($sort_name)) $sort_name_query = null; else $sort_query = $sort_name_query;
if(empty($sort_email)) $sort_email_query = null; else $sort_query = $sort_email_query;
if(empty($sort_status)) $sort_status_query = null; else $sort_query = $sort_status_query;
if(empty($sort_query)) $sort_query = null;
?>
<h2>Новая задача</h2>
<form method="post" class="row">
	<div class="col-md-10 m-0">
		<input class="form-control mb-3" type="text" name="name" placeholder="Ваше имя">
		<input class="form-control mb-3" type="email" name="email" placeholder="E-mail">
		<textarea class="form-control mb-3" name="task" id="task" placeholder="Задача . . ."></textarea>
		<input type="text" hidden="hidden" name="user_id" value="<?=$auth_user_id?>">
	</div>
	<div class="col-md-2 pl-0 mb-3">
		<input class="btn btn-success btn-lg btn-block p-1 h-100" name="add_task" type="submit" value="Добавить">
	</div>
</form>
<h3>Сортировать по</h3>
<form method="post" class="row">
	<div class="col-md-4 mb-3">
		<p class="mb-0">Пользователям</p>
		<select name="sort_name" class="form-control" onchange="this.form.submit()">
			<option value="0">Не выбран</option>
<?php
$tasks_name_result = mysqli_query($db_connect, 'SELECT DISTINCT name FROM site_tasks ORDER BY id DESC');
while($row = mysqli_fetch_array($tasks_name_result))
{
	if($sort_name == $row['name']) $selected_sort_name = ' selected="selected"';
	else $selected_sort_name = null;
 echo '   <option value="'.$row['name'].'"'.$selected_sort_name.'>'.$row['name'].'</option>'."\n";
}
mysql_free_result($tasks_name_result);
?>
		</select>
	</div>
	<div class="col-md-4 mb-3">
		<p class="mb-0">E-mail</p>
		<select name="sort_email" class="form-control" onchange="this.form.submit()">
			<option value="0">Не выбран</option>
<?php
$tasks_email_result = mysqli_query($db_connect, 'SELECT DISTINCT email FROM site_tasks WHERE email != "" ORDER BY id DESC');
while($row = mysqli_fetch_array($tasks_email_result))
{
	if($sort_email == $row['email']) $selected_sort_email = ' selected="selected"';
	else $selected_sort_email = null;
 echo '   <option value="'.$row['email'].'"'.$selected_sort_email.'>'.$row['email'].'</option>'."\n";
}
mysql_free_result($tasks_email_result);
?>
		</select>
	</div>
	<div class="col-md-4 mb-3">
		<p class="mb-0">Статусу</p>
		<select name="sort_status" class="form-control" onchange="this.form.submit()">
			<option value="0">Не выбран</option>
<?php
$tasks_status_result = mysqli_query($db_connect, 'SELECT * FROM site_statuses ORDER BY id DESC');
while($row = mysqli_fetch_array($tasks_status_result))
{
	if($sort_status == $row['id']) $selected_sort_status = ' selected="selected"';
	else $selected_sort_status = null;
 echo '   <option value="'.$row['id'].'"'.$selected_sort_status.'>'.$row['name'].'</option>'."\n";
}
mysql_free_result($tasks_status_result);
?>
		</select>
	</div>
	<div class="col-md-4"></div>
	<div class="col-md-4"></div>
</form>
<?php  
if(!empty($accept)) echo '<p class="mb-2 text-success rounded">'.$accept.'</p>'."\n";
if(!empty($error)) echo '<p class="mb-2 text-danger rounded">'.$error.'</p>'."\n";

$perpage = 3;

if(!empty($sort_query))
{
	$sort_query_cut = substr($sort_query, 6);
 $count = mysqli_count($db_connect, 'site_tasks', $sort_query_cut);
}
else
{
	$count = mysqli_count($db_connect, 'site_tasks');
}

$pages_count = ceil($count / $perpage);
if($get_page > $pages_count) $get_page = $pages_count;
$start_pos = ($get_page - 1) * $perpage;

$limit = $start_pos.', '.$perpage;

if($count > 0)
{
	$tasks_result = mysqli_query($db_connect, 'SELECT * FROM site_tasks '.$sort_query.' ORDER BY id DESC LIMIT '.$limit);
	while($row = mysqli_fetch_array($tasks_result))
	{
	 $task_id = $row['id'];
	 $user_name = $row['name'];
	 $user_email = $row['email'];
	 $task = $row['task'];
	 $user_id = $row['user_id'];
	 $status = $row['status'];

		$statuses_result = mysqli_query($db_connect, 'SELECT * FROM site_statuses WHERE id = '.$status);
		$row = mysqli_fetch_array($statuses_result);
		{
		 $status_task_name = $row['name'];
		}
		mysqli_free_result($statuses_result);

		switch($status)
		{
			case 1: $bg = 'success'; break;
			case 2: $bg = 'primary'; break;
			case 3: $bg = 'danger'; break;
		}

		// если это администратор
		if(!empty($auth_user_id) and $_SESSION['user_status'] == 1)
		{
			$task_item_edit = '<span class="edit-item float-right bg-info text-white p-1 ml-1 small rounded">Изменить</span>';
			$task_item_edit_block = '<div class="col-md-6 m-0">
			<textarea class="form-control" name="task">'.$task.'</textarea>
		</div>
		<div class="col-md-4 pl-0">
		 <select name="status" class="form-control">'."\n";

		$statuses_for_edit_result = mysqli_query($db_connect, 'SELECT * FROM site_statuses ORDER BY id');
		while($row = mysqli_fetch_array($statuses_for_edit_result))
		{
		 $status_id = $row['id'];
		 $status_name = $row['name'];

		 if($status == $status_id) $status_selected = ' selected="selected"';
		 else $status_selected = null;

		 $task_item_edit_block .= '	  <option value="'.$status_id.'"'.$status_selected.'>'.$status_name.'</option>'."\n";
		}
		mysqli_free_result($statuses_for_edit_result);

		$task_item_edit_block .= '</select>
		</div>
		<div class="col-md-2 pl-0">
			<button class="btn btn-info btn-lg btn-block p-1" name="edit_task" type="submit" value="'.$task_id.'">Изменить</button>
		</div>';
		}
		else
		{
			$task_item_edit = null;
			$task_item_edit_block = null;
		}

	 echo '    <div class="p-3 mb-2 border text-dark rounded task-item">
	    <p class="text-muted small">
	     <span>'.$user_name.' <a href="mailto:'.$user_email.'">'.$user_email.'</a></span>
	     '.$task_item_edit.'
	     <span class="float-right bg-'.$bg.' text-white p-1 small rounded">'.$status_task_name.'</span>
	    </p>
	    <p class="mb-0">'.$task.'</p>
	    <div class="edit-item-block">
					 <form method="post" class="row">'.$task_item_edit_block.'</form>
					</div>
	   </div>'."\n";
	}
	mysqli_free_result($tasks_result);
}
else
{
	echo '<p>Задач не найдено</p>'."\n";
}
?>
<nav>
 <ul class="pagination justify-content-center">
<?php
if($count > $perpage)
{
 links_bar($site_url_t, $get_page, $count, $pages_count, 15);
}
?>
 </ul>
</nav>