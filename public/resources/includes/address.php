<?php

// TODO: ADD LOAD CONTACT LIST FUNCTION, RENAME ADD CONTACT MODAL
class AddressDataStore
{
    public $filename = 'data/address_book.csv';

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