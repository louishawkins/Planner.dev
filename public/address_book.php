<?php
// Function to read from CSV
function readCSV($filename = 'contacts.csv') {
    $lengthOfFile = filesize($filename);
    if($lengthOfFile > 0) {
        $handle = fopen($filename, 'r');
        $contents = fread($handle, $lengthOfFile);
        $contactsArray = explode("\n", $contents);
        fclose($handle);
    }
    else {
        $contactsArray = ['name', 'address'];
    }
    $individual_contacts = array();
    foreach ($contactsArray as $key => $value) {
        $individual_contacts[] = explode(",", $contactsArray[$key]);
    }
   return $individual_contacts;
}

// Function to write to CSV
function writeCSV($incomingDataArray, $filename = 'contacts.csv') {
    $handle = fopen($filename, 'a');
    $dataArray_as_string = implode(",", $incomingDataArray);
    fwrite($handle, $dataArray_as_string . PHP_EOL);
    fclose($handle);
}

// Function to store a new entry
function newEntry($postArray) {
   $newEntry = [];
   $newEntry[] = $_POST['name'];
   $newEntry[] = $_POST['address'];
   $newEntry[] = $_POST['city'];
   $newEntry[] = $_POST['state'];
   $newEntry[] = $_POST['zip'];
    
    writeCSV($newEntry);
    return $newEntry;
}

// Validate information entered before new entry

function validateEntry($addresses) {
        $a_value_is_empty = null;
        foreach ($addresses as $key => $value) {
                $empty = empty($addresses[$key]) ? true : false;
                if($empty == true) {
                    var_dump("Please fill in all fields");
                    $a_value_is_empty = true;
                }//end ifs
        }//end outside foreach

        if($a_value_is_empty != true) {
            newEntry($addresses);
            return 0;
        }
        else {
            var_dump("A value is empty somehwere!");
            return false;
        }
} //end validate entry function

// Check for GET 
if(isset($_GET) && !empty($_GET)) {
    var_dump($_GET);
}

// Check for POST
/*if(empty($_POST['name']) || empty($_POST['address']) empty($_POST['city']) || empty($_POST['state']) || empty($_POST['zip'])) 
{
    echo "missing some input!";
}*/
if(isset($_POST) && !empty($_POST)) {
    var_dump($_POST);
    validateEntry($_POST);
}

// Check for FILES
if(sizeof($_FILES) > 0 && $_FILES['file1']['error'] == UPLOAD_ERR_OK && $_FILES['file1']['type'] == 'text/plain') {
    //blah
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