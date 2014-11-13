 <?php

 class Filestore
 {
     public $filename = '';

     function __construct($filename)
     {
        $this->filename = $filename;
     }

     /**
      * Returns array of lines in $this->filename
      */
     function readLines()
     {
	 //what here?    
     }

     /**
      * Writes each element in $array to a new line in $this->filename
      */
     function writeLines($array)
     {
	//what here?
     }

     /**
      * Reads contents of csv $this->filename, returns an array
      */
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

     /**
      * Writes contents of $array to csv $this->filename
      */
     function writeCSV($addressesArray)
     {
	
	$handle = fopen($this->filename, 'w');
	foreach ($addressesArray as $row){
		fputcsv($handle, $row);
	}

	fclose($handle);

     }
 }
