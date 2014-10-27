<?php
	function read_file($file = 'list.txt') {
   		//$file = '/list.txt';
    	$handle = fopen($file, 'r');
    	$contents = fread($handle, filesize($file));
    	$contentsArray = explode("\n", $contents);
    	fclose($handle);

    	for($i = 0; $i < count($contentsArray); $i++) {
        	$contentsArray[$i] = trim($contentsArray[$i]);
        	if(empty($contentsArray[$i]) == true) {
            	unset($contentsArray[$i]);
            	array_values($contentsArray);
        	} // if
       		else {}
       	} //end for loop
       	return $contentsArray;
      } // function

	function add_to_file($filename, $array){
    	$handle = fopen($filename, 'a');
    	foreach ($array as $item) {
        	fwrite($handle, PHP_EOL . $item);
    	}
    	fclose($handle);
    	return;
	}

	function overwrite_file($filename, $array){
    	$handle = fopen($filename, 'w+');
    	foreach ($array as $item) {
        	fwrite($handle, PHP_EOL . $item);
    	}
    	fclose($handle);
    	return;
	}

	function add_item($item) {
		$_listItems[] = $item;
		return $_listItems;
	}
 	
 	if(isset($_GET['$id'])) {
		$id = $_GET['$id'];
		unset($_listItems[$id]);
		overwrite_file($_listItems);
	}

	if(isset($_POST['add_item'])) {
		$_listItems = add_item($_POST['add_item']);
		add_to_file('list.txt', $_listItems);
	}

?>

<!DOCTYPE html>

<!-- *

//Create an array from your sample todo list items in the template. Next, use PHP to display the array items within the unordered list in your template and test in your browser.

//Reference the code you wrote in your command line todo list app to add the ability to load todo items from a file. The items should be loaded into an array, and then that array should be used to display the items just as in the above steps.

//Using the POST method on the form in your template, create the ability to add todo items to the list. Each time an item is added, the todo list file should be saved with the new item added.

Add a link next to each todo item that says "Mark Complete" and have it send a GET request to the page that deletes the entry. Use query strings to send the proper key back to the server, and update the todo list file to reflect the deletion.
 -->

<html>
<head>
	<title>// TODO LIST // </title>
	<link rel="stylesheet" href="/css/todo_list.css">
</head>
<body>
<h1>// TODO LIST</h1>

	<h3><a href="#loadList">Load List</a></h3>

	
	<div id="todo_list_container">
		<ul>
		<?php
			$_listItems = read_file();
			foreach ($_listItems as $item) {
				echo "<li><a href='#remove'>x</a> | " . $item . "</li>";
			}
		?>
		</ul>
	</div>

<form method="POST" action="/todo_list.php">
	<label for="add_item">Add Item:</label>
	<input type="text" id="add_item" name="add_item" placeholder="Bro down" autofocus>
	<button type="submit" value="add_item">Add Item</button>
</form>

</body>
</html>