<?php

class Filestore
{
    protected $filename;
    protected $isCSV = false;

    function __construct($filename)
    {
        if (filesize($filename) == 0) {
        	throw new Exception('File is empty');
        }

        $this->filename = $filename;

        if (substr($filename, -3) == "csv") {
        	$this->isCSV = true;
        }
    }

    public function read() {

    	if($this->isCSV) {
    		return $this->readCSV();
    	} else {
    		return $this->readLines();
    	}

    } 

    public function write($stuff) {

    	if($this->isCSV){
    		$this->writeCSV($stuff);
    	} else {
    		$this->writeLines($stuff);
    	}
    }

    private function readLines()
    {
		$contentsArray = array();
		$filesize = filesize($this->filename);
		if (filesize($this->filename) > 0){
			$handle = fopen($this->filename, 'r');
			$contents = trim(fread($handle, $filesize));
			$contentsArray = explode("\n", $contents);
			fclose($handle);
		}	
	return $contentsArray;
    }

    private function writeLines($items)
    {
		$handle = fopen($this->filename, 'w');
		$string = implode("\n", $items);
		fwrite($handle, $string);
		fclose($handle);
    }

    private function readCSV()
     {
		$addressBook = [];
		$filename = $this->filename;
		$handle = fopen($filename, 'r');

        while(!feof($handle)){
			$row = fgetcsv($handle);

			if (!empty($row)){
		   		$addressBook[] = $row;
			}
		}	
       	fclose($handle);

		return $addressBook;	
     }

    private function writeCSV($addressesArray)
    {
		$handle = fopen($this->filename, 'w');
		foreach ($addressesArray as $row){
			fputcsv($handle, $row);
		}

		fclose($handle);

    }
} // end class Filestore
