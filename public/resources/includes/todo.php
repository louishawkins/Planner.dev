<?

include_once 'filestore.php';

class TodoList extends Filestore
{
    function writeList($items)
    {
	return $this->write($items);    
    }

    function readList()
    {
	return $this->read();    
    }
}

function sanitize($array) {
    foreach ($array as $key => $value) {
        $array[$key] = htmlspecialchars(strip_tags($value));  // Overwrite each value.
    }
    return $array;
}

function validate($string) {
    try {
        if(strlen($string) == 0) {
            throw new Exception("Input cannot be empty!  :(");  
        }
        elseif(strlen($string) > 240) {
            throw new Exception("Input cannot be longer than 240 characters!  :(");   
        } else {return true;}
    } catch(Exception $e) {
        $errorMessage = $e->getMessage();
        echo "<div class='alert alert-danger' role='alert'> $errorMessage </div>";
    }
}

function uploadFile() {
   	$uploadDir = 'data/uploads/';
    $filename = basename($_FILES['file1']['name']);
    $savedFilename = $uploadDir . $filename;
    move_uploaded_file($_FILES['file1']['tmp_name'], $savedFilename);
    return isset($savedFilename) ? $savedFilename : false;
}

$list = new TodoList('data/todo.txt');

// Check for FILES to upload and do it if there is...
if (count($_FILES) > 0 && $_FILES['file1']['error'] == UPLOAD_ERR_OK && $_FILES['file1']['type'] == 'text/plain') {
	$list->filename = uploadFile();
	$all_items = $list->readList();
	$list->filename = "data/todo.txt";
	$list->writeList($all_items);
}

$all_items = $list->readList();

if(isset($_GET['id'])) {
	$id = $_GET['id'];
	unset($all_items[$id]);
	$all_items = array_values($all_items);
	$list->writeList($all_items);
}

if(!empty($_POST)) {
    $_POST = sanitize($_POST);
    if(validate($_POST['newitem'])){
        $all_items[] = $_POST['newitem'];
        $list->writeList($all_items);
    }
}
?>
