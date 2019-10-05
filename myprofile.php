<?php require_once("includes/DB.php"); ?>
<?php require_once("includes/Functions.php"); ?>
<?php require_once("includes/sessions.php"); ?>
<?php 
$_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"];

Confirm_Login(); ?>


<?php 
// Fetch the existing Admin Data Start
$AdminId = $_SESSION["UserId"];
global $ConnectingDB;
$sql = "SELECT * FROM admins WHERE id='$AdminId'";
$stmt = $ConnectingDB->query($sql);
while ($DataRows = $stmt->fetch()){
    $ExistingName = $DataRows['aname'];
    $ExistingUsername = $DataRows['username'];
    $ExistingHeadline = $DataRows['aheadline'];
    $ExistingBio = $DataRows['abio'];
    $ExistingImage = $DataRows['aimage'];
    
}
// Fetch the existing Admin Data End

if(isset($_POST["Submit"])){
    
    $AName = $_POST["Name"];
    $AHeadline = $_POST["Headline"];
    $ABio = $_POST["Bio"];
    $Image = $_FILES["Image"]["name"];
    echo $Image;
    $Target = "images/";
    
if(strlen($Headline)>30){
        $_SESSION["ErrorMessage"]= "Headline Must Be Less Than 12 Characters";
        Redirect_to("myprofile.php");   
    }elseif(strlen($ABio)>500){
        $_SESSION["ErrorMessage"]= "Bio Must Be Less Than 500 Characters";
        Redirect_to("myprofile.php");   
    }else{
           //query to update admin data in DB when everything is fine
        global $ConnectingDB;
        if (!empty($_FILES["Image"]["name"])) {
            $sql = "UPDATE admins 
            SET aname='$AName', aheadline='$AHeadline', abio='$ABio', aimage='$Image'
            WHERE id='$AdminId'";
        }else {
            $sql = "UPDATE admins 
            SET aname='$Aname', aheadline='$AHeadline', abio='$ABio'
            WHERE id='$AdminId'";
        }
        $Execute = $ConnectingDB->query($sql);
        move_uploaded_file ($_FILES["Image"]["tmp_name"], $Target.$Image);


    if($Execute){
        $_SESSION["SuccessMessage"]="Details Updated Successfully";
        Redirect_to("myprofile.php");
    }else {
        $_SESSION["ErrorMessage"]="Something went wrong. Try again!";
        Redirect_to("myprofile.php");
    }
  
  }
    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://kit.fontawesome.com/baf56a4085.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <title>My Profile</title>
</head>
<body>
    <!-- NAVBAR -->
    <?php require_once("privatenavbar.php"); ?>
    <!-- NAVBAR END -->

    <!--- HEADER -->
    <header class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                <h1><i class="fas fa-user mr-2 text-success"></i>@<?php echo $ExistingUsername ?></h1>
                </div>
            </div>
        </div>
    </header>
    <!--- HEADER END -->
    <br>
    <!--- Main Area -->
    
    <section class="container py-2 mb-4">
        <div class="row">
            <!-- Left Area -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header bg-dark text-light">
                        <h3><?php echo $ExistingName; ?></h3>
                    </div>
                    <div class="card-body">
                        <img src="images/<?php echo $ExistingImage; ?>" class="block img-fluid mb-3" alt="">
                        <hr>
                        <div class="">
                            <?php echo $ExistingHeadline ?>
                        </div>
                        <hr>
                        <div class="">
                            <?php echo $ExistingBio ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Left Area End -->
            <div class="col-lg-9" style="min-height: 450px;">
                <?php echo ErrorMessage();
                      echo SuccessMessage();
                ?>
                
                <form class="" action="myprofile.php" method="post" enctype="multipart/form-data">
                    <div class="card bg-dark text-light mb-3 py-2" style="height: auto;">
                        <div class="card-header bg-secondary text-light">
                                    <h4>Edit Profile</h4>
                                </div>
                        <div class="card-body">
                            <div class="form-group">
                                <input class="form-control" type="text" name="Name" id="title" placeholder="Your name here" value="">
                            
                            </div>
                            <hr>
                            <div class="form-group">
                                <input class="form-control" type="text" id="title" placeholder="Headline" name="Headline" value="">
                                <small class="text-muted">Add a professional headline.</small>
                                <span class="text-danger">Not more than 30 characters</span>
                            </div>
                            <hr>
                            <div class="form-group">
                                <textarea placeholder="Bio" class="form-control" id="Post" name="Bio" rows="8" cols="80"></textarea>
                            </div>
                            <hr>
                            <div class="form-group mb-1">
                                <label for="CategoryTitle">
                                    <span class="FieldInfo">
                                    Choose Cover Image:
                                    </span>
                                </label>
                                <div class="custom-file">
                                    <input class="custom-file-input" type="File" accept="image/*" onchange="preview_image(event)" name="Image" id="imageSelect" value="" />
                                    <label for="imageSelect" class="custom-file-label">Select Image</label>
                                    
                                </div>
                                <hr>
                                <div id="wrapper">
                                    <img id="output_image"/>
                                </div>
                                <hr>
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <a href="dashboard.php" class="btn btn-warning btn-block">
                                        <i class="fas fa-arrow-left"></i> Back To Dashboard
                                    </a>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <button type="submit" name="Submit" class="btn btn-success btn-block">
                                        <i class="fas fa-check"></i> Publish
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    </div>
    
                </form>
            
            </div> 
        </div>
    </section>
    
    
    
    
    <!--- Main Area End -->





    <!-- FOOTER -->
    <?php require_once("footer.php"); ?>

    <!-- FOOTER END -->


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>    
    <script>
        $('#year').text(new Date().getFullYear());
    </script>
    <script type='text/javascript'>
        function preview_image(event) 
        {
         var reader = new FileReader();
         reader.onload = function()
         {
          var output = document.getElementById('output_image');
          output.src = reader.result;
         }
         reader.readAsDataURL(event.target.files[0]);
        }
</script>
</body>
</html>