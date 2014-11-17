<?php

class AddressDataStore extends Filestore
{
	function __construct($filename) {
		// make sure the filename is lowercase
		$filename = strtolower($filename);
		parent::__construct($filename);

	}
}    
