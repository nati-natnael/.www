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
?>