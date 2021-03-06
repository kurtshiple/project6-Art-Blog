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
    <?php require_once("publicnavbar.php"); ?>

    <!-- NAVBAR END -->

    <!--- HEADER -->
    <div class="container mt-3 mb-3">
   
        <div class="row">
       
            <!-- Main Area Start -->
            <div class="col-sm-8" style="min-height:40px;">
                
                <?php
                    echo ErrorMessage();
                    echo SuccessMessage();
                ?>
                
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
                        Redirect_to("index.php?page=1");
                    }
                    $sql = "SELECT * FROM posts WHERE id = '$PostIdFromURL'";
                    $stmt = $ConnectingDB->query($sql);
                    $Result=$stmt->rowcount();
                    if ($Result!=1) {
                        $_SESSION["ErrorMessage"]="Bad Request.";
                        Redirect_to("index.php?page=1");
                    }
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
                <div class="card mb-3 mt-4">
                    
                    <img src="uploads/<?php echo htmlentities($Image); ?>" style="min-height:450px; padding:10px" class="img-fluid card-img-top"/>
                    <div class="card-body">
                        <h4 class="card-title"><?php echo htmlentities($PostTitle); ?></h4>
                        <span class="badge badge-dark">Category: <a href="index.php?category=<?php echo htmlentities($Category); ?>"><?php echo htmlentities($Category); ?></a></span> & Written By <a href="profile.php?username=<?php echo htmlentities($Admin); ?>"><?php echo htmlentities($Admin); ?></a> On <?php echo htmlentities($DateTime); ?>
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
                                <textarea name="CommenterText" class="form-control" rows="6" cols="80" placeholder="Comment must be under 1,000 characters"></textarea>
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
            <?php require_once("sidearea.php"); ?>
            <!-- Side Area End -->
        </div>
    </div>

    
    <!--- HEADER END -->
     





    <!-- FOOTER -->
    <?php require_once("footer.php"); ?>

    <!-- FOOTER END -->


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>    
    <script>
        $('#year').text(new Date().getFullYear());
    </script>
</body>
</html>