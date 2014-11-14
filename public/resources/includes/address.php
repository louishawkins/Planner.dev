<?php

require_once 'filestore.php'; // file storage class (Filestore)
require_once 'datastore.php'; // data storage class (AddressDataStore) -> extends Filestore

function sanitizeEntry($array) {
    foreach ($array as $key => $value) {
        $array[$key] = htmlspecialchars(strip_tags($value));  
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
            // to-do: tell the user what they're missing
       } 
       return $a_value_is_empty == false ? true : false;
} //end validate entry function

function check_GET($GET){
	return isset($GET) && !empty($GET) ? true : false;
}

function check_POST($POST){
	return isset($POST) && !empty($POST) ? true : false;
}

$addressBookInstance = new AddressDataStore($filename = 'data/address_book.csv');
$contacts = $addressBookInstance->readAddressBook();

if(check_GET($_GET)) {
        $id = $_GET['id'];
        unset($contacts[$id]);
        $contacts = array_values($contacts);
        $addressBookInstance->writeAddressBook($contacts);
}

if(check_POST($_POST)) {
    $_POST = sanitizeEntry($_POST);

    if(validateEntry($_POST)) {
        $contacts[] = $_POST;
        $addressBookInstance->writeAddressBook($contacts);
    }
}
?>
