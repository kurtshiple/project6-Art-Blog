<?php require_once("includes/DB.php"); ?>
<?php require_once("includes/Functions.php"); ?>
<?php require_once("includes/sessions.php"); ?>

<?php 
$SearchQueryParameter = $_GET['id'];
// fetching existing content to be edited 
                        global $ConnectingDB;
                        $sql = "SELECT * FROM posts WHERE id='$SearchQueryParameter'";
                        $stmt = $ConnectingDB->query($sql);
                        while ($DataRows=$stmt->fetch()) {
                        $TitleToBeDeleted = $DataRows['title'];
                        $CategoryToBeDeleted = $DataRows['category'];
                        $ImageToBeDeleted = $DataRows['image'];
                        $PostToBeDeleted = $DataRows['post'];
                    }
if(isset($_POST["Submit"])){

        //query to delete post in DB when everything is fine
        global $ConnectingDB;
        $sql = "DELETE FROM posts WHERE id='$SearchQueryParameter'";
        $Execute = $ConnectingDB->query($sql);
    if($Execute){
        $Target_Path_To_Delete_Image = "uploads/$ImageToBeDeleted";
        unlink($Target_Path_To_Delete_Image);
        $_SESSION["SuccessMessage"]="Post Deleted Successfully";
        Redirect_to("posts.php");
    }else {
        $_SESSION["ErrorMessage"]="Something went wrong. Try again!";
        Redirect_to("posts.php");
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
    <title>Delete Post</title>
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
                <h1><i class="fas fa-edit" style="color:#27aae1;"></i> Delete Post</h1>
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
                
                <form class="" action="deletepost.php?id=<?php echo $SearchQueryParameter; ?>" method="post" enctype="multipart/form-data">
                    <div class="card bg-secondary text-light mb-3 mt-3" style="height: auto;">
                        <div class="card-body bg-dark">
                            <div class="form-group">
                                <label for="Title">
                                    <span class="FieldInfo">
                                    Post Title:
                                    </span>
                                </label>
                                <input disabled class="form-control" type:"text" name="PostTitle" id="title" placeholder="Type title here" value="<?php echo $TitleToBeDeleted; ?>">
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="CategoryTitle">
                                    <span class="FieldInfo">
                                    Category:
                                    </span>
                                    <?php echo $CategoryToBeDeleted; ?>
                                </label>
                            </div>
                            <hr>
                            <div class="form-group mb-1">
                                <span class="FieldInfo">
                                    Image:
                                    </span>
                                    <div id="wrapper">
                                        <img src="uploads/<?php echo $ImageToBeDeleted;?>" id="output_image"/>
                                    </div>
                                <hr>
                            <div class="form-group">
                               <label for="Post">
                                    <span class="FieldInfo">
                                    Post:
                                    </span>
                                </label>
                                <textarea disabled class="form-control" id="Post" name="PostDescription" rows="8" cols="80">
                                    <?php echo $PostToBeDeleted;?>
                                </textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <a href="Dashboard.php" class="btn btn-warning btn-block">
                                        <i class="fas fa-arrow-left"></i> Back To Dashboard
                                    </a>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <button type="submit" name="Submit" class="btn btn-danger btn-block">
                                        <i class="fas fa-trash"></i> Delete
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