<?php require_once("includes/DB.php"); ?>
<?php require_once("includes/Functions.php"); ?>
<?php require_once("includes/sessions.php"); ?>
<?php 
$_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"];
Confirm_Login(); 
?>

<?php if(isset($_POST["Submit"])){
    
    $Category = $_POST["CategoryTitle"];
    $Admin = $_SESSION["UserName"]; 
    date_default_timezone_set("America/New_York");
    $CurrentTime=time();
    $DateTime=strftime("%B-%d-%Y %H:%M:%S",$CurrentTime);
        
    if(empty($Category)){
        $_SESSION["ErrorMessage"]= "All Fields Must Be Filled Out";
        Redirect_to("categories.php");       
    }elseif(strlen($Category)<3){
        $_SESSION["ErrorMessage"]= "Category Title Must Be Greater Than 2 Characters";
        Redirect_to("categories.php");   
    }elseif(strlen($Category)>49){
        $_SESSION["ErrorMessage"]= "Category Title Must Be Less Than 50 Characters";
        Redirect_to("categories.php");   
    }else{
        global $ConnectingDB;
        $sql = "INSERT INTO category(title,author,datetime)";
        $sql .= "VALUES(:categoryName,:adminName,:dateTime)";
        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(':categoryName',$Category);
        $stmt->bindvalue(':adminName',$Admin);
        $stmt->bindValue(':dateTime',$DateTime);
        $Execute=$stmt->execute();

    if($Execute){
        $_SESSION["SuccessMessage"]="Category with id : ".$ConnectingDB->lastInsertId()." Added Successfully";
        Redirect_to("categories.php");
    }else {
        $_SESSION["ErrorMessage"]="Something went wrong. Try again!";
        Redirect_to("categories.php");
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
    <title>Categories</title>
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
                <h1><i class="fas fa-th-large" style="color:#27aae1;"></i> Manage Categories</h1>
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
                
                <form class="" action="categories.php" method="post">
                    <div class="card bg-secondary text-light mb-3 mt-3">
                        <div class="card-header">
                            <h1>
                            Add New Category
                            </h1>
                        </div>
                        <div class="card-body bg-dark">
                            <div class="form-group">
                                <label for="Title">
                                    <span class="FieldInfo">
                                    Category Title:
                                    </span>
                                </label>
                                <input class="form-control" type:"text" name="CategoryTitle" id="title" placeholder="Type title here" value="">
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
                </form>
                <div class="card bg-secondary text-light mb-3 mt-3">
                    <div class="card-header">
                        <h1>Existing Categories</h1>
                     </div>
                  
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No. </th>
                                    <th>Date & Time </th>
                                    <th>Category Name</th>
                                    <th>Creator Name </th>
                                    <th>Action </th>
                                </tr>
                            </thead>

                        <?php
                        global $ConnectingDB;
        //                $sql = "SELECT * FROM comments ORDER BY id desc";
                        $sql = "SELECT * FROM category ORDER BY id desc";
                        $Execute=$ConnectingDB->query($sql);
                        $SrNo = 0;
                        while ($DataRows=$Execute->fetch()){
                            $CategoryId = $DataRows["id"];
                            $CategoryDate = $DataRows["datetime"];
                            $CategoryName = $DataRows["title"];
                            $CreatorName = $DataRows["author"];
                            $SrNo++;
                        ?>
                            <tbody class="text-light">
                                <tr>
                                    <td><?php echo htmlentities($SrNo); ?></td>
                                    <td><?php echo htmlentities($CategoryDate); ?></td>
                                    <td><?php echo htmlentities($CategoryName); ?></td>
                                    <td><?php echo htmlentities($CreatorName); ?></td>
                                    <td>
                                        <a href="deletecategory.php?id=<?php echo $CategoryId; ?>" class="btn btn-danger" style="width:100%; height:100%;">Delete</a>
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
