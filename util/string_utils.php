<?php
   /**
    * Capitalize first letter of each word of a phrase.
    *
    * return: Capitalized phrase
    */
    function capitalizeWords($phrase) {
       $formatted = "";
       $words = explode(' ', $phrase);
    
       foreach ($words as $word) {
           $tWord = trim($word, ' ');
           $formatted .= " " . ucfirst($tWord);
       }
    
       return $formatted;
    }
    
    /**
    * Validate input string
    *
    * All Values need to be alpha numeric
    */
    function validate($string) {
       $pattern = "/^[a-z0-9]+$/";
    
       if (empty($string)) {
           return false;
       }
    
       $lowerString = strtolower($string);
       $pieces = explode(" ", $lowerString);
    
       foreach ($pieces as $piece) {
           $match = preg_match($pattern, $piece);
           # echo "String: $piece | Match: $match <br>";
    
           # If any string piece doesn't match stop
           if (!$match) {
               return false;
           }
       }
    
       return $match;
    }
?>