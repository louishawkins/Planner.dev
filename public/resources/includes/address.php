<?php

require_once 'filestore.php'; // file storage class (Filestore)
require_once 'datastore.php'; // data storage class (AddressDataStore) -> extends Filestore
define('FILE', 'data/address_book.csv');

function sanitizeEntry($array) {
    foreach ($array as $key => $value) {
        $array[$key] = htmlspecialchars(strip_tags($value));  
    }
    return $array;
}
// Validate information entered before new entry. If input passes then return true.
function validateEntry($new_post) {
        $a_value_is_empty = null;
        $empty_keys = []; // to-do: array of keys that are empty
        foreach ($new_post as $key => $value) {   
            if(empty($new_post[$key])) {
                $a_value_is_empty = true;
                $empty_keys[] = $key;
            }//end ifs
        }//end outside foreach
       if (sizeof($empty_keys) > 0) {
            // to-do: tell the user what they're missing
       } 
       return $a_value_is_empty == false ? true : false;
} //end validate entry function

$addressBookInstance = new AddressDataStore(FILE);
$contacts = $addressBookInstance->read();

if(isset($_GET['id'])) {
        $id = $_GET['id'];
        unset($contacts[$id]);
        $contacts = array_values($contacts);
        $addressBookInstance->write($contacts);
}

if(!empty($_POST)) {
    $_POST = sanitizeEntry($_POST);

    if(validateEntry($_POST)) {
        $contacts[] = $_POST;
        $addressBookInstance->write($contacts);
    }
}
?>
