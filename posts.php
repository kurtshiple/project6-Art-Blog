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
    <title>Posts</title>
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
                <h1><i class="fas fa-blog" style="color:#27aae1;"></i> Blog Posts</h1>
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
        </div>
    </header>
    <!--- HEADER END -->
    
    <!--- Main Area -->
    
    <section class="container py-2 mb-4">
        <div class="row">
            <div class="col-lg-12">
                <?php 
                    echo ErrorMessage();
                    echo SuccessMessage();
                ?>
                <div class="card bg-secondary text-light mb-3 mt-3">
                    <div class="card-header">
                        <h1>Existing Posts</h1>
                    </div>
<!--                    <div class="card-body bg-light">-->
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Date & Time</th>
                                    <th>Author</th>
                                    <th>Image</th>
                                    <th>Comments</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <?php 
                            global $ConnectingDB;
                            $Sr = 0;
                            $sql = "SELECT * FROM posts ORDER BY id desc";
                            $stmt = $ConnectingDB->query($sql);
                            while ($DataRows = $stmt->fetch()) {
                                $Id = $DataRows["id"];
                                $DateTime = $DataRows["datetime"];
                                $PostTitle = $DataRows["title"];
                                $Category = $DataRows["category"];
                                $Admin = $DataRows["author"];
                                $Image = $DataRows["image"];
                                $PostText = $DataRows["post"];
                                $Sr++;


                            ?>
                            <tbody class="text-light">
                            <tr>
                                <td>
                                    <?php echo $Sr; ?> 
                                </td>
                                <td>
                                    <?php 
                                        if (strlen($PostTitle)>20){
                                            $PostTitle = substr($PostTitle,0,17).'..';
                                        }
                                    echo $PostTitle;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $Category; 
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    if (strlen($DateTime)>20){
                                            $DateTime = substr($DateTime,0,17).'..';
                                        }
                                    echo $DateTime; 
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if (strlen($Admin)>10){
                                            $Admin = substr($Admin,0,10).'..';
                                        }
                                    echo $Admin; 
                                    ?>
                                </td>
                                <td>
                                    <img src="uploads/<?php echo $Image; ?>" width="70px;" height="70px;">
                                </td>
                                <td>
                                    <span class="badge badge-success" style="width:100%">
                                        <?php 
                                        ApproveCommentsAccordingToPost($Id);
                                        ?>
                                    </span>
                                    <span class="badge badge-danger" style="width:100%;">
                                        <?php 
                                        DisapproveCommentsAccordingToPost($Id);
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <table>
                                        <tr>
                                            <td><a href="editpost.php?id=<?php echo $Id ?>" class="btn btn-warning" style="width:100%">Edit</a></td>
                                            <td><a href="deletepost.php?id=<?php echo $Id ?>" class="btn btn-danger" style="width:100%">Delete</a></td>
                                        </tr>

                                        <tr>
                                            <td colspan="2"><a href="fullpost.php?id=<?php echo $Id; ?>" target="_blank" class="btn btn-primary" style="width:100%">Live Preview</a></td>
                                        </tr>
                                    </table>
                                
                                </td>
                                
                            </tr>
                            </tbody>
                            <?php } ?>
                        </table>
<!--                    </div>-->
                </div>
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