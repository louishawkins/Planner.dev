<?
class TodoList
{
    public $filename;

    function writeList($items)
    {
        $handle = fopen($this->filename, 'w');
        $string = implode("\n", $items);
        fwrite($handle, $string);
        fclose($handle);
    }

    function readList()
    {
        $contentsArray = array();
        $filesize = filesize($this->filename);
        if (filesize($this->filename) > 0) {
        	$handle = fopen($this->filename, 'r');
        	$contents = trim(fread($handle, $filesize));
        	$contentsArray = explode("\n", $contents);
        	fclose($handle);
        }

        return $contentsArray;
    }
}

function sanitize($array) {
    foreach ($array as $key => $value) {
        $array[$key] = htmlspecialchars(strip_tags($value));  // Overwrite each value.
    }
    return $array;
}

function uploadFile() {
   	// Set the destination directory for uploads
   	$uploadDir = 'data/uploads/';
    // Grab the filename from the uploaded file by using basename
    $filename = basename($_FILES['file1']['name']);
    // Create the saved filename using the file's original name and our upload directory
    $savedFilename = $uploadDir . $filename;
    // Move the file from the temp location to our uploads directory
    move_uploaded_file($_FILES['file1']['tmp_name'], $savedFilename);
 	// If we did, load file into active todo-list file and refresh the page to show the new items.
    $ok = isset($savedFilename) ? true : false;

    if($ok) {
    	return $savedFilename;
    }
}

$list = new TodoList();

// Check for FILES to upload and do it if there is...
if (count($_FILES) > 0 && $_FILES['file1']['error'] == UPLOAD_ERR_OK && $_FILES['file1']['type'] == 'text/plain') {
	$list->filename = uploadFile();
	$all_items = $list->readList();
	$list->filename = "data/todo.txt";
	$list->writeList($all_items);
}
else {
	$list->filename = "data/todo.txt";
}

$all_items = $list->readList();

// Check for GET Requests
     // If there is a get request; remove the appropriate item.
if(isset($_GET) && !empty($_GET)) {
	$id = $_GET['id'];
	unset($all_items[$id]);
	$all_items = array_values($all_items);
	$list->writeList($all_items);
}

 // Check for POST Requests
    // If there is a post request; add the items.
if(isset($_POST) && !empty($_POST)) {
	$_POST = sanitize($_POST);
	$all_items[] = $_POST['newitem'];
	$list->writeList($all_items);

}

?>

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