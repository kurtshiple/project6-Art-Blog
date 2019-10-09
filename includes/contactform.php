<?php

if (isset($_POST['submit'])) {
    $name = $_POST['Name'];
    $subject = $_POST['Subject'];
    $mailfrom = $_POST['Email'];
    $message = $_POST['Message'];
    
    $mailTo = "mary.shiple@gmail.com";
    $headers = "From: ".$mailfrom;
    $txt = "You have received an e-mail from ".$name.".\n\n".$message;
    
    $mailsent = mail($mailTo, $subject, $txt, $headers);
    
    if($mailsent){
        $_SESSION["SuccessMessage"]="Post Updated Successfully";
        Redirect_to("contactpage.php");
    }else {
        $_SESSION["ErrorMessage"]="Something went wrong. Try again!";
        Redirect_to("contactpage.php");
    }

}

?>