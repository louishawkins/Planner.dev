<?php
function openFile($filename = 'todo.txt') {
	$lengthFile = filesize($filename);
	if ($lengthFile > 0) {
		$handle = fopen($filename, 'r');
		$contents = fread($handle, $lengthFile);
		$contentsArray = explode("\n", $contents);
		fclose($handle);
		return $contentsArray;
	}
}
/* This function accepts an array, saves it to file, and returns nothing. */
 function saveFile($array, $filename = 'todo.txt') {
	$handle = fopen($filename, 'w');
	$string = implode("\n", $array);
	fwrite($handle, $string);
	fclose($handle);
	return;
}
//Initialize a todo-list file
if (filesize('todo.txt') <= 0) {
	$_initItem = array('\n');
	saveFile($_initItem);
}

// Initialize your array by calling your function to open file.
 $_listItems = openFile();
 
// Check for GET Requests
     // If there is a get request; remove the appropriate item.
if(isset($_GET) && empty($_GET) == false) {
	$id = $_GET['id'];
	unset($_listItems[$id]);
	array_values($_listItems);
	saveFile($_listItems);
}
 // Check for POST Requests
    // If there is a post request; add the items.
if(empty($_POST) == false) {
	$_listItems[] = $_POST['newitem'];
	var_dump($_POST['newitem']);
	saveFile($_listItems);
}
?>
 
<html>
<head>
    <title>TODO App</title>
    <link rel="stylesheet" href="/css/todo_list.css">
</head>
<body>
 <h1>TODO LIST</h1>
<!-- Echo Out the List Items -->
<ol>
	<?php
		foreach($_listItems as $key => $item) {
			echo "<li><a href=\"?id=$key\">X</a> " . $item . "</li>";
		}
	?>
</ol>

 
<!-- Create a Form to Accept New Items -->
<form method="POST" name="add-form" action="/todo_list.php">
	<input id="newitem" name="newitem" type="text" placeholder="New item" autofocus>
	<button type="submit">Add</button>
</form>
 
</body>
</html>