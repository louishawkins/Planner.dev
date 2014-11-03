<?php
class AddressDataStore
{
    public $filename = 'address_book.csv';

    function readAddressBook()
    {
        $addressBook = [];
        $filename = $this->filename;
            $handle = fopen($filename, 'r');

            while(!feof($handle)) {
                $row = fgetcsv($handle);

                if (!empty($row)) {
                    $addressBook[] = $row;
                }
            }  
        fclose($handle);

        return $addressBook;
    }

    function writeAddressBook($addressesArray)
    {
        $handle = fopen($this->filename, 'w');
        foreach ($addressesArray as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
    }

}

function sanitizeEntry($array) {
    foreach ($array as $key => $value) {
        $array[$key] = htmlspecialchars(strip_tags($value));  // Overwrite each value.
    }
    return $array;
}
// Validate information entered before new entry. If input passes then return true.
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
       return $a_value_is_empty == false ? true : false;
} //end validate entry function

// create instance of the address book for the page
// read the active csv an put it into contacts array that can be iterated through in the table.
$addressBookInstance = new AddressDataStore();
$address_book_filesize = filesize($addressBookInstance->filename);
$contacts = $addressBookInstance->readAddressBook();

// Check for GET queries
// REMOVE ENTRY code is here:
if(isset($_GET) && !empty($_GET)) { 
    $id = $_GET['id'];
    unset($contacts[$id]);
    $contacts = array_values($contacts);
    $addressBookInstance->writeAddressBook($contacts);
}

// Check for POST queries
if(isset($_POST) && !empty($_POST)) {
    $_POST = sanitizeEntry($_POST);
    $entry_is_valid = validateEntry($_POST);

    if($entry_is_valid == true) {
        $contacts[] = $_POST;
        $addressBookInstance->writeAddressBook($contacts);
    }
}
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
        <table id="contactsTable" class="table table-bordered table-hover">
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
                    foreach ($contacts as $key => $value) {
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
        <form id="newContactForm" method="POST" action="/address_book.php" class="form-horizontal" role="form">
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
