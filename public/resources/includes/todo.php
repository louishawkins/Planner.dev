<?

include_once 'filestore.php';

class TodoList extends Filestore
{
    function writeList($items)
    {
	return $this->writeLines($items);    
    }

    function readList()
    {
	return $this->readLines();    
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
    return isset($savedFilename) ? $savedFilename : false;
}

// create a new instance of a todo-list
$list = new TodoList('data/todo.txt');
$list->filename = 'data/todo.txt';
// Check for FILES to upload and do it if there is...
if (count($_FILES) > 0 && $_FILES['file1']['error'] == UPLOAD_ERR_OK && $_FILES['file1']['type'] == 'text/plain') {
	$list->filename = uploadFile();
	$all_items = $list->readList();
	$list->filename = "data/todo.txt";
	$list->writeList($all_items);
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
