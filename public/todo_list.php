<?php
function openFile($filename = 'todo.txt') {
	$lengthFile = filesize($filename);
	if($lengthFile > 0) {
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

function uploadFile() {
   	// Set the destination directory for uploads
   	$uploadDir = './uploads/';
    // Grab the filename from the uploaded file by using basename
    $filename = basename($_FILES['file1']['name']);
    // Create the saved filename using the file's original name and our upload directory
    $savedFilename = $uploadDir . $filename;
    // Move the file from the temp location to our uploads directory
    move_uploaded_file($_FILES['file1']['tmp_name'], $savedFilename);
 	// If we did, show a link to the uploaded file
    $_listItems = isset($savedFilename) ? saveFile(openFile($savedFilename)) : false;
	header("Refresh:0");
    return $_listItems;
}

//Initialize a todo-list file
if (filesize('todo.txt') < 1) {
	$_initItem = array("Add new items.");
	saveFile($_initItem);
	header("Refresh:0");
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
	saveFile($_listItems);
}
// Check for FILES to upload and do it if there is...
(count($_FILES) > 0 && $_FILES['file1']['error'] == UPLOAD_ERR_OK && $_FILES['file1']['type'] == 'text/plain') ? uploadFile() : false;

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