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
        foreach ($new_post as $key => $value) {   
            try{
                if(empty($new_post[$key])) {
                    $fail_validation = true;
                } elseif (strlen($value) > 125) {
                    $fail_validation = true;
                    throw new Exception("| :( |  Input cannot be greater than 125 characters");        
                }
            } catch(Exception $e){
                $errorMessage = $e->getMessage();
                echo "<div class='alert alert-danger' role='alert'> $errorMessage </div>";
            }
        }
       return $fail_validation == false ? true : false;
}

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
