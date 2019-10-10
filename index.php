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
    <style media="screen">
    .heading{
    font-family: Bitter,Georgia,"Times New Roman",Times, serif;
    font-weight: bold;
    color: #005E90;
        }

    .heading:hover{
    color: #0090DB;
        }
    
    </style>
    <!-- For the image slider -->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

</head>

<body>
    <!-- NAVBAR -->
   <?php require_once("publicnavbar.php"); ?>
    <!-- NAVBAR END -->

    <!--- HEADER -->
    <div class="container mt-3 mb-3">
        <div class="row">
            <!-- Main Area Start -->
            <!-- Image Slider -->
            <div class="col-sm-8" style="min-height:40px;">
            
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="container-fluid padding">
                            <div class="w3-content w3-display-container">

                                <?php
                                global $ConnectingDB;

                                $sql = "SELECT * FROM posts ORDER BY id desc";
                                $stmt = $ConnectingDB->query($sql);

                                while ($DataRows = $stmt->fetch()) {
                                $PostID = $DataRows["id"];
                                $DateTime = $DataRows["datetime"];
                                $PostTitle = $DataRows["title"];
                                $Category = $DataRows["category"];
                                $Admin = $DataRows["author"];
                                $Image = $DataRows["image"];
                                $PostDescription = $DataRows["post"];

                                ?>

                                <div class="w3-display-container mySlides">
                                <img src="uploads/<?php echo htmlentities($Image); ?>" style="height:400px; width:100%">
                                <div class="w3-display-bottom text-align w3-large w3-container w3-padding-16 w3-black">
                                     <?php echo htmlentities($PostTitle); ?> 
                                    </div>
                                </div>
                                <?php } ?>

                                <button class="w3-button w3-display-left w3-black" onclick="plusDivs(-1)">&#10094;</button>
                                <button class="w3-button w3-display-right w3-black" onclick="plusDivs(1)">&#10095;</button>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Image Slider Ends -->
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
                  //Query When pagination is active i.e. index.php?pages=1  
                }elseif(isset($_GET["page"])){
                    $Page = $_GET["page"];
                    if($Page==0||$Page<1){
                        $ShowPostFrom=0;
                    }else{
                        $ShowPostFrom=($Page*5)-5;  
                    }
                    
                    $sql = "SELECT * FROM posts ORDER BY id desc LIMIT $ShowPostFrom,5";
                    $stmt=$ConnectingDB->query($sql);
                    
                }
                // Query When Category is active in URL Tab
                elseif(isset($_GET["category"])){
                    $Category = $_GET["category"];
                    $sql = "SELECT * FROM posts WHERE category=:categoryName ORDER BY id desc";   
                    $stmt = $ConnectingDB->prepare($sql);
                    $stmt->bindValue(':categoryName',$Category);
                    $stmt->execute();
                }
                // The default SQL query
                else{
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
                    <img src="uploads/<?php echo htmlentities($Image); ?>" style="min-height:450px; padding:10px" class="img-fluid card-img-top"/>
                    <div class="card-body">
                        <h4 class="card-title"><?php echo htmlentities($PostTitle); ?></h4>
                        <span class="badge badge-dark">Category: <a href="index.php?category=<?php echo htmlentities($Category); ?>"><?php echo htmlentities($Category); ?></a></span> & Written By <a href="profile.php?username=<?php echo htmlentities($Admin); ?>"><?php echo htmlentities($Admin); ?></a> On <?php echo htmlentities($DateTime); ?>
                        <span style="float:right;" class="badge badge-dark">Comments: 
                        <?php echo ApproveCommentsAccordingToPost($PostID); ?>
                        </span>
                        
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
                <!-- Pagination -->
                <nav>
                    <ul class="pagination pagination-lg">
                        <!-- Creating back button -->
                        
                        <?php if (isset($Page)) { 
                        if ($Page>1){?>
                            <li class="page-item">
                                <a href="index.php?page=<?php echo $Page-1; ?>" class="page-link">&laquo;</a>
                            </li>
                        <?php } } ?>
                        <?php 
                        global $ConnectingDB;
                        $sql = "SELECT COUNT(*) FROM posts";
                        $stmt = $ConnectingDB->query($sql);
                        $RowPagination=$stmt->fetch();
                        $TotalPosts=array_shift($RowPagination);
                       
                        $PostPagination=$TotalPosts/5;
                        $PostPagination=ceil($PostPagination);
                        //echo post paginations
                        for ($i=1;$i <= $PostPagination; $i++){
                            if(isset($Page)){
                                if($i==$Page){  ?>
                            <li class="page-item active">
                                <a href="index.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                            </li>
                        <?php 
                            }else {
                            ?>     <li class="page-item">
                                    <a href="index.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                                    </li>
                           <?php }    
                            } }  ?>
                        <!-- Creating forward button -->
                        
                        <?php if (isset($Page)&&!empty($Page)) { 
                        if ($Page+1<=$PostPagination){?>
                            <li class="page-item">
                                <a href="index.php?page=<?php echo $Page+1; ?>" class="page-link">&raquo;</a>
                            </li>
                        <?php } } ?>
                    </ul>
                </nav>
                
                <!-- Pagination End -->
                <hr>
            
            </div>
            <!-- Main Area End -->
            
            
            <!-- Side Area Start -->
            <?php require_once("sidearea.php"); ?>
            <!-- Side Area End -->
        </div>
    </div>
    <!--- HEADER END -->
    
    <!--- Main Area -->
    <!--- Main Area End -->





    <!-- FOOTER -->
    <?php require_once("footer.php"); ?>

    <!-- FOOTER END -->
    
    <!--- javascript file-->
    <!-- image slider and image gallery -->
        <script src="includes/imagesliderandgallery.js"></script>
    <!-- image slider and image gallery end -->




    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>    
    <script>
        $('#year').text(new Date().getFullYear());
    </script>
</body>
</html>