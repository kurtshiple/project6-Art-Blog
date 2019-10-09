<?php require_once("includes/DB.php"); ?>
<?php require_once("includes/Functions.php"); ?>
<?php require_once("includes/sessions.php"); ?>
<?php Confirm_Login(); ?>


<?php 
if(isset($_POST["Submit"])){

    $Headline = $_POST["Headline"];
    $Image = $_FILES["Image"]["name"];
    echo $Image;
    $Target = "uploadsaboutpage/";
    $TextDescription = $_POST["TextDescription"];
    $Admin = "Kurt"; 
    date_default_timezone_set("America/New_York");
    $CurrentTime=time();
    $DateTime=strftime("%B-%d-%Y %H:%M:%S",$CurrentTime);
        
    if(empty($Headline)){
        $_SESSION["ErrorMessage"]= "Headline Can't Be Empty";
        Redirect_to("editaboutpage.php");       
    }elseif(strlen($Headline)<3){
        $_SESSION["ErrorMessage"]= "Headline Must Be Greater Than 2 Characters";
        Redirect_to("editaboutpage.php");  
    }elseif(strlen($TextDescription)>9999){
        $_SESSION["ErrorMessage"]= "Text Must Be Less Than 10000 Characters";
        Redirect_to("editaboutpage.php");   
    }else{
        // query to insert comment in DB when everything is fine.
        global $ConnectingDB;
        $sql = "INSERT INTO aboutpage(datetime,headline,text,image)";
        $sql .= "VALUES(:dateTime,:headLine,:Text,:Image)";
        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(':dateTime',$DateTime);
        $stmt->bindvalue(':headLine',$Headline);
        $stmt->bindValue(':Text',$TextDescription);
        $stmt->bindValue(':Image',$Image);
        

        $Execute=$stmt->execute();
        move_uploaded_file ($_FILES["Image"]["tmp_name"], $Target.$Image);


    if($Execute){
        $_SESSION["SuccessMessage"]="About Page Updated Successfully";
        Redirect_to("editaboutpage.php");
    }else {
        $_SESSION["ErrorMessage"]="Something went wrong. Try again!";
        Redirect_to("editaboutpage.php");
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
    <title>Edit About Page</title>
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
                <h1><i class="fas fa-edit" style="color:#27aae1;"></i> Edit About Page</h1>
                </div>
            </div>
        </div>
    </header>
    <!--- HEADER END -->
    <br>
    <!--- Main Area -->
    
    <section class="container py-2 mb-4">
        <div class="row">
            <div class="offset-lg-1 col-lg-10" style="min-height: 450px;">
                <?php echo ErrorMessage();
                      echo SuccessMessage();
                      // fetching existing content to be edited 
                        global $ConnectingDB;
                        $sql = "SELECT * FROM aboutpage ORDER BY id DESC LIMIT 1";
                        $stmt = $ConnectingDB->query($sql);
                        while ($DataRows=$stmt->fetch()) {
                        $HeadlineToBeUpdated = $DataRows['headline'];
                        $TextToBeUpdated = $DataRows['text'];
                        $ImageToBeUpdated = $DataRows['image'];
                        }
                ?>
                
                <form class="" action="editaboutpage.php" method="post" enctype="multipart/form-data">
                    <div class="card bg-secondary text-light mb-3 mt-3" style="height: auto;">
                        <div class="card-body bg-dark">
                            <div class="form-group">
                                <label for="Title">
                                    <span class="FieldInfo">
                                    Edit Headline:
                                    </span>
                                </label>
                                <input class="form-control" type="text" name="Headline" id="title" placeholder="Type title here" value="<?php echo $HeadlineToBeUpdated; ?>">
                            </div>
                            <hr>
                            
                            <hr>
                            <div class="form-group mb-1">
                                <span class="FieldInfo">
                                    Image:
                                    </span>
                                    <div id="wrapper">
                                        <img src="uploadsaboutpage/<?php echo $ImageToBeUpdated;?>" style="max-width:100%;"/>
                                    </div>
                                <hr>
                                <span class="FieldInfo">
                                    Update Image:
                                </span>
                                <hr>
                                <div class="custom-file">
                                    <input class="custom-file-input" type="File" accept="image/*" onchange="preview_image(event)" name="Image" id="imageSelect" value="" />
                                    <label for="imageSelect" class="custom-file-label">Update Image</label>
                                    
                                </div>
                                <hr>
                                <div id="wrapper">
                                    <img id="output_image"/>
                                </div>
                                <hr>
                            <div class="form-group">
                               <label for="Post">
                                    <span class="FieldInfo">
                                    Update Text:
                                    </span>
                                </label>
                                <textarea class="form-control" id="Post" name="TextDescription" rows="8" cols="80">
                                    <?php echo $TextToBeUpdated;?>
                                </textarea>
                            </div>
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