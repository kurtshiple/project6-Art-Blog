<?php require_once("includes/DB.php"); ?>
<?php require_once("includes/Functions.php"); ?>
<?php require_once("includes/sessions.php"); ?>
<?php 
$_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"];

Confirm_Login(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://kit.fontawesome.com/baf56a4085.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <title>Comments</title>
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
                <li class="nav-item"><a href="logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
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
                <h1><i class="fas fa-comments" style="color:#27aae1"></i> Manage Comments</h1>
                </div>
            </div>
        </div>
    </header>
    <!--- HEADER END -->
    <!-- Main Area Start -->
    <section class="container py-2 mb-4">
        <div class="row" style="min-height:30px;">
            <div class="col-lg-12" style="min-height:400px;">
                <?php echo ErrorMessage();
                      echo SuccessMessage();
                ?>
                
                <div class="card bg-secondary text-light mb-3 mt-3">
                    <div class="card-header">
                        <h1>Existing Comments</h1>
                    </div>
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No. </th>
                            <th>Date & Time </th>
                            <th>Post Title </th>
                            <th>Commenter Name </th>
                            <th>Comment </th>
                            <th>Status </th>
                            <th>Actions </th>
                        </tr>
                    </thead>
                
                <?php
                global $ConnectingDB;
//                $sql = "SELECT * FROM comments ORDER BY id desc";
                $sql = "SELECT posts.id, posts.title, comments.id, comments.datetime, comments.name, comments.comment, comments.post_id, comments.status FROM posts INNER JOIN comments ON posts.id = comments.post_id ORDER BY comments.id DESC";
                $Execute=$ConnectingDB->query($sql);
                $SrNo = 0;
                while ($DataRows=$Execute->fetch()){
                    $PostTitle = $DataRows["title"];
                    $CommentId = $DataRows["id"];
                    $DateTimeOfComment = $DataRows["datetime"];
                    $CommenterName = $DataRows["name"];
                    $CommentContent = $DataRows["comment"];
                    $CommentPostId = $DataRows["post_id"];
                    $CommentStatus = $DataRows["status"];
                    $SrNo++;
                ?>
                    <tbody class="text-light">
                        <tr>
                            <td><?php echo htmlentities($SrNo); ?></td>
                            <td><?php echo htmlentities($DateTimeOfComment); ?></td>
                            <td><?php echo htmlentities($PostTitle); ?></td>
                            <td><?php echo htmlentities($CommenterName); ?></td>
                            <td><?php echo htmlentities($CommentContent); ?></td>
                            <td><?php echo htmlentities($CommentStatus); ?></td>
                            <td>
                                <table>
                                    <tr>
                                        <td><a href="approvecomments.php?id=<?php echo $CommentId; ?>" class="btn btn-success" style="width:100%">Approve</a></td>
                                        <td><a href="disapprovecomments.php?id=<?php echo $CommentId; ?>" class="btn btn-warning" style="width:100%">Disapprove</a></td>
                                    </tr>
                                    
                                    <tr>
                                        <td><a href="deletecomments.php?id=<?php echo $CommentId; ?>" class="btn btn-danger" style="width:100%; height:100%;">Delete</a></td>
                                        <td><a target="_blank" class="btn btn-primary" href="fullpost.php?id=<?php echo $CommentPostId; ?>" style="width:100%">Preview</a></td>
                                    </tr>
                                </table>
                            </td>
                            
                        </tr>
                    </tbody>
                    <?php } ?>
                </table> 
                </div>
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
</body>
</html>