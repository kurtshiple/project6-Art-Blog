<?php require_once("includes/DB.php"); ?>
<?php require_once("includes/Functions.php"); ?>
<?php require_once("includes/sessions.php"); ?>
<?php 

$_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"];
Confirm_Login();
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
    <title>Dashboard</title>
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
                <h1><i class="fas fa-desktop" style="color:#27aae1;"></i> Dashboard</h1>
                </div>
                <div class="col-lg-3 mt-2 mb-2">
                    <a href="addnewpost.php" class="btn btn-primary btn-block">
                        <i class="fas fa-edit"></i> Add New Post
                    </a>
                </div>
                <div class="col-lg-3 mt-2 mb-2">
                    <a href="categories.php" class="btn btn-primary btn-block">
                        <i class="fas fa-folder-plus"></i> Add New Category
                    </a>
                </div>
                <div class="col-lg-3 mt-2 mb-2">
                    <a href="admins.php" class="btn btn-warning btn-block">
                        <i class="fas fa-user-plus"></i> Add New Admin
                    </a>
                </div>
                <div class="col-lg-3 mt-2 mb-2">
                    <a href="comments.php" class="btn btn-success btn-block">
                        <i class="fas fa-check"></i> Approve Comments
                    </a>
                </div>
            </div>
            <div class="row">
                
                <div class="col-lg-3 mt-2">
                    <a href="editsidebar.php" class="btn btn-primary btn-block">
                        <i class="fas fa-edit"></i> Edit Sidebar
                    </a>
                </div>
                <div class="col-lg-3 mt-2">
                    <a href="editaboutpage.php" class="btn btn-primary btn-block">
                        <i class="fas fa-edit"></i> Edit About Page
                    </a>
                </div>
                <div class="col-lg-3 mt-2">
                    <a href="https://pixlr.com/" target="_blank" class="btn btn-primary btn-block">
                        <i class="far fa-image"></i> Pixlr Photo Editor
                    </a>
                </div>
            </div>
        </div>
    </header>
    <!--- HEADER END -->
    
    <!--- Main Area -->
    
    <section class="container py-2 mb-4">
        <div class="row">
            <?php 
                    echo ErrorMessage();
                    echo SuccessMessage();
            ?>
            <!-- Left Side Area Start -->
            <div class="col-lg-2">
                <div class="card text-center bg-dark text-white mb-3">
                    <div class="card-body">
                        <h1 class="lead">Posts</h1>
                        <h4 class="display-5">
                            <i class="fas fa-align-left"></i>
                            <?php 
                            TotalPosts();
                            ?>
                        </h4>
                    </div>
                </div> 
                <div class="card text-center bg-dark text-white mb-3">
                    <div class="card-body">
                        <h1 class="lead">Categories</h1>
                        <h4 class="display-5">
                            <i class="fas fa-th-large"></i>
                            <?php 
                            TotalCategories();
                            ?>
                        </h4>
                    </div>
                </div>
                <div class="card text-center bg-dark text-white mb-3">
                    <div class="card-body">
                        <h1 class="lead">Admins</h1>
                        <h4 class="display-5">
                            <i class="fas fa-users"></i>
                            <?php 
                            TotalAdmins();
                            ?>
                        </h4>
                    </div>
                </div>
                <div class="card text-center bg-dark text-white mb-3">
                    <div class="card-body">
                        <h1 class="lead">Comments</h1>
                        <h4 class="display-5">
                            <i class="fas fa-comments"></i>
                            <?php 
                            TotalComments();
                            ?>
                        </h4>
                    </div>
                </div>
            </div>
            <!-- Left Side Area End -->
            <!-- Right Side Area Start -->
            <div class="col-lg-10">
                <div class="card bg-secondary text-light mb-3">
                    <div class="card-header">
                        <h1>Top Posts</h1>
                    </div>
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Title</th>
                            <th>Date & Time</th>
                            <th>Author </th>
                            <th>Comments</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <?php
                    $SrNo = 0;
                    global $ConnectingDB;
                    $sql = "SELECT * FROM posts ORDER BY id desc LIMIT 0,5";
                    $stmt = $ConnectingDB->query($sql);
                    while ($DataRows=$stmt->fetch()){
                        $PostId = $DataRows["id"];
                        $DateTime = $DataRows["datetime"];
                        $Author = $DataRows["author"];
                        $Title = $DataRows["title"];
                        $SrNo++;
                    
                    ?>
                    <tbody class="text-light">
                        <tr>
                            <td><?php echo $SrNo ?></td>
                            <td><?php echo $Title ?></td>
                            <td><?php echo $DateTime ?></td>
                            <td><?php echo $Author ?></td>
                            <td>
                                <span class="badge badge-success" style="width:100%;">
                                    <?php 
                                    ApproveCommentsAccordingToPost($PostId);
                                    ?>
                                </span>
                                <span class="badge badge-danger" style="width:100%;">
                                    <?php 
                                    DisapproveCommentsAccordingToPost($PostId);
                                    ?>
                                </span>
                            </td>
                            <td><a target="_blank" href="fullpost.php?id=<?php echo $PostId; ?>"><span class="btn btn-info" style="width:100%;">Preview</span></a></td>
                        </tr>
                    </tbody>
                    <?php } ?>
                </table>
            </div>
            
            <!-- Right Side Area End -->
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
</body>
</html>