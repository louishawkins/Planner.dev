<?php
// Function to read from CSV
function readCSV($filename = 'contacts.csv') {
    $lengthOfFile = filesize($filename);
    if($lengthOfFile > 0) {
        $handle = fopen($filename, 'r');
        $contents = fread($handle, $lengthOfFile);
        $contents_array = explode("\n", $contents);
        fclose($handle);
    }
    else {
        $contents_array = ['Add some contacts!'];
    }
    $individual_contacts = array();
    foreach ($contents_array as $key => $value) {
        $individual_contacts[] = explode(",", $contents_array[$key]);
    }
   return $individual_contacts;
}

// Function to write to CSV
function writeCSV($incoming_array, $filename = 'contacts.csv') {
    $handle = fopen($filename, 'a');
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

function sanitizeEntry($array) {
    foreach ($array as $key => $value) {
        $array[$key] = htmlspecialchars(strip_tags($value));  // Overwrite each value.
    }
    return $array;
}

// Validate information entered before new entry. If input passes, then send the input off to the newEntry function to add it to the csv.

function validateEntry($new_post) {
        $a_value_is_empty = null;
        foreach ($new_post as $key => $value) {   
            $is_empty = empty($new_post[$key]) ? true : false;
            if($is_empty == true) {
                $a_value_is_empty = true;
            }//end ifs
        }//end outside foreach
       $a_value_is_empty == false ? newEntry() : false;
} //end validate entry function

// Check for GET 
if(isset($_GET) && !empty($_GET)) {
    var_dump($_GET);
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
</head>
<body>

<div class="row">
    <div id="contactList">
        <h1>Contacts</h1>

        <table>
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>City</th>
                <th>State</th>
                <th>Zip</th>
            </tr>
        
             <!-- Loop through each of the addresses and output -->
                <?php
                    foreach ($addresses as $key => $value) {
                        echo "<tr>";
                        foreach ($value as $key2 => $value2) {
                            echo "<td>$value2</td>";
                        }
                        echo "</tr>";
                    }
                ?>
    </table>
    </div> <!-- contact list div -->
</div> <!-- row div -->

    <!-- Form to accept multiple inputs -->
<div class="row">
    <div id="addContact">
        <form method="POST" action="/address_book.php">
            <p>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="Bill Murray">
            </p>
            <p>
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="123 Magic Street">
            </p>
            <p>
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="San Antonio">
            </p>
            <p>
                <label for="state">State:</label>
                <input type="text" id="state" name="state" value="Texas">
            </p>
            <p>
                <label for="zip">Zip:</label>
                <input type="text" id="zip" name="zip" value="78063">
            </p>
            <p>
                <button type="submit" value="addEntry">Add Entry</button>
            </p>
        </form>
    </div> <!-- add contact div -->
</div> <!-- second row div -->
</body>
</html>