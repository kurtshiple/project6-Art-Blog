<?php require_once("includes/DB.php"); ?>
<?php require_once("includes/Functions.php"); ?>
<?php require_once("includes/sessions.php"); ?>
<?php $SearchQueryParameter = $_GET["id"]; ?>
<?php if(isset($_POST["Submit"])){
    
    $Name = $_POST["CommenterName"];
    $Email = $_POST["CommenterEmail"];
    $Comment = $_POST["CommenterText"];
    date_default_timezone_set("America/New_York");
    $CurrentTime=time();
    $DateTime=strftime("%B-%d-%Y %H:%M:%S",$CurrentTime);
        
    if(empty($Name)||empty($Email)||empty($Comment)){
        $_SESSION["ErrorMessage"]= "All Fields Must Be Filled Out";
        Redirect_to("fullpost.php?id={$SearchQueryParameter}");       
    }elseif(strlen($Comment)>1000){
        $_SESSION["ErrorMessage"]= "Comment length should be less than 1,000 characters";
        Redirect_to("fullpost.php?id={$SearchQueryParameter}");   
    }else{
        // query to insert comment in DB when everything is fine.
        global $ConnectingDB;
        $sql = "INSERT INTO comments(datetime,name,email,comment,approvedby,status,post_id)";
        $sql .= "VALUES(:dateTime,:name,:email,:comment,'pending','ON',:postIdFromURL)";
        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(':dateTime',$DateTime);
        $stmt->bindvalue(':name',$Name);
        $stmt->bindValue(':email',$Email);
        $stmt->bindValue(':comment',$Comment);
        $stmt->bindValue(':postIdFromURL',$SearchQueryParameter);

        $Execute=$stmt->execute();

    if($Execute){
        $_SESSION["SuccessMessage"]="Comment Submitted Successfully";
        Redirect_to("fullpost.php?id={$SearchQueryParameter}"); 
    }else {
        $_SESSION["ErrorMessage"]="Something went wrong. Try again!";
        Redirect_to("fullpost.php?id={$SearchQueryParameter}"); 
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
    <title>Full Post Page</title>
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
                    <a href="blog.php?page=1" class="nav-link"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"><i class="fas fa-info-circle"></i> About Us</a>
                </li>
                <li class="nav-item">
                    <a href="blog.php?page=1" class="nav-link"><i class="fab fa-elementor"></i> Blog</a>
                </li>
                <li class="nav-item">
                    <a href="comments.php" class="nav-link"><i class="fas fa-at"></i> Contact Us</a>
                </li>
                <li class="nav-item">
                    <a href="blog.php" class="nav-link"><i class="fas fa-sitemap"></i> Features</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <form class="form-inline d-none d-sm-block" action="blog.php">
                    <div class="form-group">
                        <input class="form-control mr-2" type="text" name="Search" placeholder="Type Here..." value="">
                        <button class="btn btn-primary" name="SearchButton">Search</button>
                        
                    </div>
                </form>
            </ul>
            </div>
        </div>
    </nav>
    <div style="height: 10px; background: #27aae1;"></div>
    <!-- NAVBAR END -->

    <!--- HEADER -->
    <div class="container mt-3 mb-3">
   
        <div class="row">
       
            <!-- Main Area Start -->
            <div class="col-sm-8" style="min-height:40px;">
                <hr>
                <?php
                    echo ErrorMessage();
                    echo SuccessMessage();
                ?>
                <h1>Complete Responsive CMS Art Blog</h1>
                <h1 class="lead">PHP, HTML, Bootstrap, and MySQL</h1>
                <hr>
                <?php
                global $ConnectingDB;
                // SQL query when search button is activated and else is otherwise
                if(isset($_GET["SearchButton"])){
                    $Search = $_GET["Search"];
                    $sql = "SELECT * FROM posts 
                            WHERE datetime LIKE :search
                            OR title LIKE :search
                            OR category LIKE :search
                            OR post LIKE :search";
                    $stmt = $ConnectingDB->prepare($sql);
                    $stmt->bindValue(':search','%'.$Search.'%');
                    $stmt->execute();
                    
                }else{
                    $PostIdFromURL = $_GET["id"];
                    if (!isset($PostIdFromURL)){
                        $_SESSION["ErrorMessage"]="Bad Request.";
                        Redirect_to("blog.php");
                    }
                    $sql = "SELECT * FROM posts WHERE id = '$PostIdFromURL'";
                    $stmt = $ConnectingDB->query($sql);
                }
                while ($DataRows = $stmt->fetch()) {
                    $PostID = $DataRows["id"];
                    $DateTime = $DataRows["datetime"];
                    $PostTitle = $DataRows["title"];
                    $Category = $DataRows["category"];
                    $Admin = $DataRows["author"];
                    $Image = $DataRows["image"];
                    $PostDescription = $DataRows["post"];
                    
                
                ?>
                <div class="card mb-3">
                    
                    <img src="uploads/<?php echo htmlentities($Image); ?>" style="max-height:450px; padding:10px" class="img-fluid card-img-top"/>
                    <div class="card-body">
                        <h4 class="card-title"><?php echo htmlentities($PostTitle); ?></h4>
                        <span class="badge badge-dark">Category: <?php echo htmlentities($Category); ?></span> & Written By <?php echo htmlentities($Admin); ?> On <?php echo htmlentities($DateTime); ?>
                        <span style="float:right;" class="badge badge-dark">Comments: 
                        <?php echo ApproveCommentsAccordingToPost($PostID); ?>
                        </span>
                        
                        <hr>
                        <p class="card-text">
                            <?php echo htmlentities($PostDescription); ?>
                        </p>
                    </div>
                </div>
                <hr>
                <?php } ?>
                <hr>
                
                <!-- Comment Area Start -->
                <div>
                <form class="" action="fullpost.php?id=<?php echo $SearchQueryParameter ?>" method="post">
                    <div class="card mb-3">
                        <div class="card-header" style="padding:20px;">
                            <h5 class="FieldInfo"> Add Comment</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input class="form-control" type="text" name="CommenterName" placeholder="Name" value="">

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input class="form-control" type="email" name="CommenterEmail" placeholder="Email" value="">

                                </div>
                            </div>
                            <div class="form-group">
                                <textarea name="CommenterText" class="form-control" rows="6" cols="80"></textarea>
                            </div>
                            <div class="">
                                <button type="submit" name="Submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
                <!-- Fetching existing comment Start -->
                <div class="card mb-3">
                    <div class="card-header" style="padding:20px">
                    <span class="FieldInfo">Comment Section</span>
                    </div>
                    <br>
                    <?php
                    global $ConnectingDB;
                    $sql = "SELECT * FROM comments 
                    WHERE post_id='$SearchQueryParameter'
                    AND status='ON'";
                    $stmt = $ConnectingDB->query($sql);
                    while ($DataRows = $stmt->fetch()) {
                        $CommentDate = $DataRows['datetime'];
                        $CommenterName = $DataRows['name'];
                        $CommentContent = $DataRows['comment'];

                    ?>
                    <div style="padding:20px">
                        <div class="media CommentBlock">
                            <img class="d-block img-fluid ml-1 mt-1" src="images/user.ong.png" alt="" height="60" width="60">
                            <div class="media-body ml-2">
                                
                                <h6 class="lead bold" style="color: blue;"><?php echo $CommenterName; ?></h6>
                                <p class="small" style="color: blue;"><?php echo $CommentDate; ?></p>
                                
                                <p><?php echo $CommentContent; ?></p>
                        
                
                            </div>
                        </div>
                    </div>
                    <hr>
                   

                    <?php } ?>
                    
                </div>
                <!-- Fetching Existing comment END -->
            <!-- Comment Area End -->
                
            <!-- Main Area End -->
            
            
            </div>
            <!-- Side Area Start -->
            <!-- Side Area Start -->
            <div class="col-sm-4">
                <div class="card mt-4">
                    <div class="card-body">
                        <img src="images/Adolph_Tidemand_-_Low_Church_Devotion_-_Google_Art_Project.jpg" class="d-block img-fluid mb-3" alt="">
                        <div class="text-center">
                            The passage experienced a surge in popularity during the 1960s when Letraset used it on their dry-transfer sheets, and again during the 90s as desktop publishers bundled the text with their software. Today it's seen all around the web; on templates, websites, and stock designs. Use our generator to get your own, or read on for the authoritative history of lorem ipsum.


                        </div>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-header bg-dark text-light">
                        <h2 class="lead">Sign Up!</h2>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-success btn-block text-center text-white mb-4" name="button">Join the Forum</button>
                        <button type="button" class="btn btn-danger btn-block text-center text-white mb-4" name="button">Login</button>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="" placeholder="Enter your email" value="">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary btn-sm text-center text-white" name="button">Subscribe Now</button>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-header bg-primary text-light">
                        <h2 class="lead">Categories</h2>
                        </div>
                        <div class="card-body">
                            <?php
                            global $ConnectingDB;
                            $sql = "SELECT * FROM category ORDER BY id desc";
                            $stmt = $ConnectingDB->query($sql);
                            while ($DataRows = $stmt->fetch()) {
                                $CategoryId = $DataRows["id"];
                                $CategoryName = $DataRows["title"];
                                ?>
                            
                            <a href="blog.php?category=<?php echo $CategoryName; ?>"><span class="heading"><?php echo $CategoryName; ?></span></a><br>
                            
                            <?php } ?>
                        
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h2 class="lead"> Recent Posts</h2>
                    </div>
                    <div class="card-body">
                        <?php 
                        global $ConnectingDB;
                        $sql = "SELECT * FROM posts ORDER BY id desc LIMIT 0,5";
                        $stmt= $ConnectingDB->query($sql);
                        while ($DataRows=$stmt->fetch()) {
                            $Id = $DataRows['id'];
                            $Title = $DataRows['title'];
                            $DateTime = $DataRows['datetime'];
                            $Image = $DataRows['image'];
                        
                        ?>
                        <div class="media">
                            
                            <img src="uploads/<?php echo htmlentities($Image); ?>" class="d-block img-fluid align-self-start" width="50%" height="100px" alt="">
                            <div class="media-body ml-2">
                                <a href="fullpost.php?id=<?php echo htmlentities($Id); ?>" target="_blank"><h6 class="lead"><?php echo htmlentities($Title); ?></h6></a>
                                <p class="small"><?php echo htmlentities($DateTime); ?></p>
                            </div>
                            
                        </div>
                        
                        <hr>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- Side Area End -->
            <!-- Side Area End -->
        </div>
    </div>

    
    <!--- HEADER END -->
     





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
</body>
</html>