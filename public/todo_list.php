<?php require_once 'resources/includes/todo.php' ; ?>
<html>
<head>
    <title>TODO App</title>
    <link rel="stylesheet" href="resources/css/todo_list.css">
</head>
<body>
 <h1>TODO LIST</h1>
 <p><a href="#savefile">Save List</a></p>
<!-- Echo Out the List Items -->
<ol>
<?	foreach($all_items as $key => $item):  ?>
	<li><?= "<a href=\"?id=$key\">X</a> " . $item; ?></li>
	<? endforeach ?>
</ol>
 
<!-- Accept new items -->
<form method="POST" name="add-form" action="/todo_list.php">
	<input id="newitem" name="newitem" type="text" placeholder="New item" autofocus>
	<button type="submit">Add</button>
</form>
<h3>Load a List (.txt)</h3> 
<!-- Accept uploads -->
<form method="POST" enctype="multipart/form-data" action="/todo_list.php">
        <p>
            <label for="file1">File to upload: </label>
            <input type="file" id="file1" name="file1">
        </p>
        <p>
            <input type="submit" value="Upload">
        </p>
</form>

</body>
</html>