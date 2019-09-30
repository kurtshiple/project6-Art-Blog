<?php require_once("includes/DB.php"); ?>
<?php require_once("includes/Functions.php"); ?>
<?php require_once("includes/sessions.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://kit.fontawesome.com/baf56a4085.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <title>Blog Page</title>
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
                    <a href="blog.php" class="nav-link"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"><i class="fas fa-info-circle"></i> About Us</a>
                </li>
                <li class="nav-item">
                    <a href="blog.php" class="nav-link"><i class="fab fa-elementor"></i> Blog</a>
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
                <h1>Complete Responsive CMS Art Blog</h1>
                <h1 class="lead">PHP, HTML, Bootstrap, and MySQL</h1>
                <hr>
                <?php echo ErrorMessage();
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
                    $sql = "SELECT * FROM posts ORDER BY id desc";
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
                        <small class="text-white">By <?php echo htmlentities($Admin); ?> On <?php echo htmlentities($DateTime); ?></small>
                        <span style="float:right;" class="badge badge-dark text-light">Comments 20</span>
                        
                        <hr>
                        <p class="card-text">
                            <?php 
                                if (strlen($PostDescription)>150){
                                    $PostDescription = substr($PostDescription,0,147).'...';
                                }
                            echo htmlentities($PostDescription);
                            ?>
                            
                        </p>
                        <a href="fullpost.php?id=<?php echo $PostID; ?>" style="float:right;">
                            <span class="btn btn-info">Read More</span>
                        </a>
                    </div>
                </div>
                <hr>
                <?php } ?>
                <hr>
            
            </div>
            <!-- Main Area End -->
            
            
            <!-- Side Area Start -->
            <div class="col-sm-4" style="min-height:40px; background:green;">
            
            
            </div>
            <!-- Side Area End -->
        </div>
    </div>
    <!--- HEADER END -->
    
    <!--- Main Area -->
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
</body>
</html>