<?php require_once("includes/DB.php"); ?>
<?php require_once("includes/Functions.php"); ?>
<?php require_once("includes/sessions.php"); ?>

<?php if(isset($_POST["Submit"])){

    
    $PostTitle = $_POST["PostTitle"];
    $Category = $_POST["Category"];
    $Image = $_FILES["Image"]["name"];
    echo $Image;
    $Target = "uploads/";
    $PostText = $_POST["PostDescription"];
    $Admin = $_SESSION["UserName"]; 
    date_default_timezone_set("America/New_York");
    $CurrentTime=time();
    $DateTime=strftime("%B-%d-%Y %H:%M:%S",$CurrentTime);
        
    if(empty($PostTitle)){
        $_SESSION["ErrorMessage"]= "Title Can't Be Empty";
        Redirect_to("addnewpost.php");       
    }elseif(strlen($PostTitle)<3){
        $_SESSION["ErrorMessage"]= "Post Title Must Be Greater Than 2 Characters";
        Redirect_to("addnewpost.php");   
    }elseif(strlen($PostText)>9999){
        $_SESSION["ErrorMessage"]= "Post Description Must Be Less Than 10,000 Characters";
        Redirect_to("addnewpost.php");   
    }else{
        //query to insert post in DB when everything is fine
        global $ConnectingDB;
        $sql = "INSERT INTO posts(datetime,title,category,author,image,post)";
        $sql .= "VALUES(:dateTime,:postTitle,:categoryName,:adminName,:imageName,:postDescription)";
        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(':dateTime',$DateTime);
        $stmt->bindValue(':postTitle',$PostTitle);
        $stmt->bindValue(':categoryName',$Category);
        $stmt->bindvalue(':adminName',$Admin);
        $stmt->bindValue(':imageName',$Image);
        $stmt->bindValue(':postDescription',$PostText);
        $Execute=$stmt->execute();
        move_uploaded_file ($_FILES["Image"]["tmp_name"], $Target.$Image);

    if($Execute){
        $_SESSION["SuccessMessage"]="Post with id : ".$ConnectingDB->lastInsertId()." Added Successfully";
        Redirect_to("addnewpost.php");
    }else {
        $_SESSION["ErrorMessage"]="Something went wrong. The image size may be too large. Try again!";
        Redirect_to("addnewpost.php");
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
    <title>Add New Post</title>
</head>
<body>
    <!-- NAVBAR -->
    <div style="height: 10px; background: #27aae1;"></div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a href="#" class="navbar-brand">MARYSART.COM</a>
            <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapseCMS">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarcollapseCMS">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a href="myprofile.php" class="nav-link"><i class="fas fa-user"></i> My Profile</a>
                </li>
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link"><i class="fas fa-desktop"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="posts.php" class="nav-link"><i class="fas fa-align-left"></i> Posts</a>
                </li>
                <li class="nav-item">
                    <a href="categories.php" class="nav-link"><i class="fas fa-th-large"></i> Categories</a>
                </li>
                <li class="nav-item">
                    <a href="admins.php" class="nav-link"><i class="fas fa-users-cog"></i> Manage Admins</a>
                </li>
                <li class="nav-item">
                    <a href="comments.php" class="nav-link"><i class="fas fa-comments"></i> Comments</a>
                </li>
                <li class="nav-item">
                    <a href="blog.php" class="nav-link"><i class="fab fa-elementor"></i> Live Blog</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a href="Logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
            </div>
        </div>
    </nav>
    <div style="height: 10px; background: #27aae1;"></div>
    <!-- NAVBAR END -->

    <!--- HEADER -->
    <header class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                <h1><i class="fas fa-edit" style="color:#27aae1;"></i> Add New Post</h1>
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
                ?>
                
                <form class="" action="addnewpost.php" method="post" enctype="multipart/form-data">
                    <div class="card bg-secondary text-light mb-3 mt-3" style="height: auto;">
                        <div class="card-body bg-dark">
                            <div class="form-group">
                                <label for="Title">
                                    <span class="FieldInfo">
                                    Post Title:
                                    </span>
                                </label>
                                <input class="form-control" type:"text" name="PostTitle" id="title" placeholder="Type title here" value="">
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="CategoryTitle">
                                    <span class="FieldInfo">
                                    Choose Category:
                                    </span>
                                </label>
                                <select class="form-control" id="CategoryTitle" name="Category">     
                                    <?php
                                    //Fetching all the categories from the category table
                                    global $ConnectingDB;
                                    $sql = "SELECT * FROM category";
                                    $stmt = $ConnectingDB->query($sql);
                                    while ($DateRows = $stmt->fetch()) {
                                        $Id = $DateRows["id"];
                                        $CategoryName = $DateRows["title"];
                                
                                    ?>
                                    <option> <?php echo $CategoryName; ?></option>
                                <?php } ?>
                                </select>
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
                            <div class="form-group">
                               <label for="Post">
                                    <span class="FieldInfo">
                                    Post:
                                    </span>
                                </label>
                                <textarea class="form-control" id="Post" name="PostDescription" rows="8" cols="80"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <a href="Dashboard.php" class="btn btn-warning btn-block">
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
    <div style="height: 10px; background: #27aae1;"></div>
    <footer class="bg-dark text-white text-align">
        <div class="container">
            <div class="row">
                <div class="col">
                    <p class="lead text-center">Theme By | Kurt Shiple | <span id="year"></span> &copy; ----All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    <div style="height: 10px; background: #27aae1;"></div>

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