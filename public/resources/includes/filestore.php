 <?php

 class Filestore
 {
     public $filename = '';

     function __construct($filename)
     {
        return $this->filename = $filename;
     }

     function readLines()
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

     function writeLines($items)
     {
	$handle = fopen($this->filename, 'w');
	$string = implode("\n", $items);
	fwrite($handle, $string);
	fclose($handle);
     }

     function readCSV()
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

     function writeCSV($addressesArray)
     {
	
	$handle = fopen($this->filename, 'w');
	foreach ($addressesArray as $row){
		fputcsv($handle, $row);
	}

	fclose($handle);

     }
 }
