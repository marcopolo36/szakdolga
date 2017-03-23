<?php
function showErrors($errors) {
    if(isset($errors) && ! empty($errors)) {
        $error_text = "";
        foreach($errors as $error) {
            $error_text .= $error . " | ";
            // alternativa:
            // $error_text = $error_text . $error + "";
        }
        //alternatíva:
        /* for($i = 0; $i < count($errors); ++$i) {
            $error = $errors[$i];
            ...
        } */
        print "<script>alert('A következők hibák léptek fel: " . $error_text . "');</script>";
    }
}
?>
