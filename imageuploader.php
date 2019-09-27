<?php
    if(isset($_FILES['file'])){
        $name_file = $_FILES['file']["name"];
        $tmp_name = $_FILES['file']["tmp_name"];
        $local_image = "uploads/";
        move_uploaded_file($tmp_name,$local_image.$name_file);
    }
?>



<form method="post" enctype="multipart/form-data">
    <input type="file" name="file"/><br><br>
    <input type="submit" name="submit" value="upload"/>
</form>