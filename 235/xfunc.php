<?

//----------------------------------------------------------------------------
// Possibly unused? ----------------------------------------------------------
// ---------------------------------------------------------------------------


// Generates a password with no ambiguous characters
function gen_pw($length) {
    $pw = "";
    //warning: i took out ambiguous-looking characters
    $template = "23456789abcdefghjkmnopqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ!@#$%^&*()[]{}|?~";

    for ($a = 0; $a < $length; $a++) {
        $b = rand(0, strlen($template) - 1);
        $pw .= $template[$b];
    }

    return $pw;
}


function randomkey($length) {
   $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
   for($i=0;$i<$length;$i++) {
     $key .= $pattern{rand(0,35)};
   }
   return $key;
}

?>
