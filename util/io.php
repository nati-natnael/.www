<?php
   /**
    * Reads file containing json object.
    * file must only contain one JSon object.
    *
    * returns json object
    */
    function readJSonFile ($filePath) {
       try {
           $jsonFile = fopen($filePath, "r");
    
           # Read Entire file
           $size = filesize($filePath);
           if ($size > 0) {
               $jsonString = fread($jsonFile, $size);
           } else {
               # Create empty json if file is empty
               $jsonString = '{"monday": [], "tuesday": [],
                               "wednesday": [], "thursday": [], "friday": []}';
           }
    
           fclose($jsonFile);
    
           $jsonObject = json_decode($jsonString, true);
    
           return $jsonObject;
       } catch (Exception $e) {
           echo 'Error: ' . $e->getMessage();
           return null;
       }
    }
    
   /**
    * Write json to file
    */
    function writeJSonToFile ($filePath, $json) {
       try {
           $file = fopen($filePath, "w") or die("unable to open file");
           $encodedJson = json_encode($json);
           $read = fwrite($file, $encodedJson);
           fclose($file);
    
           return TRUE;
       } catch (Exception $e) {
           echo 'Error: ' . $e->getMessage();
           return FALSE;
       }
    }
?>