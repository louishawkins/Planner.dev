<?php
// Function to read from CSV
function readCSV($filename = 'contacts.csv') {
    if(filesize($filename) > 10) {
        $handle = fopen($filename, 'r');
        while(!feof($handle)) {
            $row = fgetcsv($handle);
            if (!empty($row)) {
                $individual_contact[] = $row;
            }
        }
        fclose($handle);
    }
    else {
        $individual_contact = ['Add some contacts!'];
    }
   return $individual_contact;
}

// Function to write to CSV
function writeCSV($incoming_array, $write_mode = 'a', $filename = 'contacts.csv') {
    $handle = fopen($filename, $write_mode);
    $incoming_array_as_string = implode(",", $incoming_array);
    fwrite($handle, $incoming_array_as_string . PHP_EOL);
    fclose($handle);
}

// Function to store a new entry
function newEntry() {
    $newEntry = array();
    foreach ($_POST as $key => $value) {
        $newEntry[] = $_POST[$key];
    }
    writeCSV($newEntry);
    return 0;
}

function removeEntry($id) {
    $addresses = readCSV();
    foreach ($addresses[$id] as $key => $value) {
        unset($addresses[$id][$key]);
    }
    unset($addresses[$id]);
    $addresses = array_values($addresses);
    foreach ($addresses as $key => $value) {
        $key == 0 ? writeCSV($addresses[$key], 'w+') : writeCSV($addresses[$key], 'a');
    }
}

function sanitizeEntry($array) {
    foreach ($array as $key => $value) {
        $array[$key] = htmlspecialchars(strip_tags($value));  // Overwrite each value.
    }
    return $array;
}
// Validate information entered before new entry. If input passes, then send the input off to the newEntry function to add it to the csv.
function validateEntry($new_post) {
        $a_value_is_empty = null;
        $empty_keys = [];
        foreach ($new_post as $key => $value) {   
            $is_empty = empty($new_post[$key]) ? true : false;
            if($is_empty == true) {
                $a_value_is_empty = true;
                $empty_keys[] = $key;
            }//end ifs
        }//end outside foreach
       if (sizeof($empty_keys) > 0) {
            //tell the user what they're missing
       } 
       $a_value_is_empty == false ? newEntry() : false;
} //end validate entry function


// Check for GET 
if(isset($_GET) && !empty($_GET)) { 
    $id = $_GET['id'];
    removeEntry($id);
}

// Check for POST
if(isset($_POST) && !empty($_POST)) {
    $_POST = sanitizeEntry($_POST);
    validateEntry($_POST);
}

// Check for FILES
if(sizeof($_FILES) > 0 && $_FILES['file1']['error'] == UPLOAD_ERR_OK && $_FILES['file1']['type'] == 'text/plain') {
    //TODO
}
// Define $addresses array
$addresses = readCSV();

?>

<html>
<head>
    <title>Address Book!</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/address_book.css">
    <script src="/js/jquery.min.js"></script>
    <script src="~/js/bootstrap.min.js"></script>
</head>
<body>
    <script src="/js/bootstrap.min.js"></script>
<div class="container">

<!-- Button trigger modal -->
<div class="row" id="row_with_button">
    <button id="addContactButton" type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">Add Contact</button>
</div> <!-- END Button Row div -->

<div class="row" id="main_row">
    <div id="contactList" class="col-md-12">
        <h1>Contacts</h1>
        <table class="table table-striped">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>City</th>
                <th>State</th>
                <th>Delete</th>
            </tr>
      <!-- Loop through each of the addresses and output -->
                <?php
                    foreach ($addresses as $key => $value) {
                        echo "<tr>";
                        foreach ($value as $key2 => $value2) {
                            echo "<td>$value2</td>";
                        }
                        echo "<td><a href=\"?id=$key\"><span class=\"glyphicon glyphicon-remove\"></span></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                ?>
    </table>
    </div> <!-- contact list div -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Add New Contact</h4>
      </div> <!-- modal header -->
        <!-- MODAL BODY WITH FORM -->
      <div class="modal-body">
        <form id="newContactForm" method="POST" action="/address_book.php" class="form-horizontal" role="form" >
            <div class="form-group">
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" placeholder="Name">
            </div>
            </div>
            <div class="form-group">
            <div class="col-sm-10">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
            </div>
            </div>
            <div class="form-group">
            <div class="col-sm-10">
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
            </div>
            </div>
            <div class="form-group">
            <div class="col-sm-10">
                <input type="text" class="form-control" id="city" name="city" placeholder="City">
            </div>
            </div>
            <div class="form-group">
            <div class="col-sm-10">
                <input type="text" class="form-control" id="state" name="state" placeholder="State">
            </div>
            </div>
      </div> <!-- modal body -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Contact</button>
      </div> <!-- modal-footer -->
        </form>
    </div> <!-- modal-content -->
  </div> <!-- modal-dialogue -->
</div> <!-- master modal-div -->
<!-- end MODAL -->

</div> <!-- contact list row div -->
</div> <!-- site container div -->
</body>
</html>