<?php
try {
    $file = fopen("input/input.txt","r");      
    if(!$file)
    {
        $message = "File not found.";
        echo "<script type='text/javascript'>alert('$message');</script>";
        throw new Exception('File not found.');
    }   
    
}
catch (Exception $e) {
$errorfile = fopen ("error/error.txt" , 'w'); 
$message=$e->getMessage();
fwrite ( $errorfile , $message ) ;
fclose ($errorfile);
} 

$headerKeys = fgetcsv($file, 1000, ';');
$headerValues = fgetcsv($file, 1000, ';');

header ("Content-Type:text/xml");
$simplexml = new SimpleXMLElement('<?xml version="1.0"?><order></order>'); 

$headerXML = $simplexml->addChild('header');
 foreach ($headerKeys as $key => $column) {
     $headerXML->addChild($column, $headerValues[$key]);
   
} 

 $detailKeys = fgetcsv($file, 1000, ';');
 $linesXML = $simplexml->addChild('lines');
 while (($rows = fgetcsv($file, 1000, ';')) !== FALSE) {

     $lineXML = $linesXML->addChild('line');
     foreach ($detailKeys as $key => $column) {
         $lineXML->addChild($column, $rows[$key]);
     }

 }

 fclose($file);

 echo $simplexml->asXML();
 file_put_contents('output/output.xml', $simplexml->asXML());
