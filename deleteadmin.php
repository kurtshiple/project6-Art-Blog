<?php require_once("includes/DB.php"); ?>
<?php require_once("includes/Functions.php"); ?>
<?php require_once("includes/sessions.php"); ?>

<?php 
if(isset($_GET["id"])){
    $SearchQueryParameter = $_GET["id"];
    global $ConnectingDB;
    $sql = "DELETE FROM admins WHERE id='$SearchQueryParameter'";
    $Execute = $ConnectingDB->query($sql);
    if ($Execute){
        $_SESSION["SuccessMessage"]="Admin Deleted Successfully!";
        Redirect_to("admins.php");
    }else{
        $_SESSION["ErrorMessage"]="Something Went Wrong. Try Again!";
        Redirect_to("admins.php"); 
    }
}
?>