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
    <title>About Page</title>
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
    <!-- For the gallery -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>



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
            
                <div class="card mt-4">
                    <div class="card-body">
                        <?php
                            global $ConnectingDB;
                            $sql = "SELECT * FROM aboutpage ORDER BY id DESC LIMIT 1";
                            $stmt = $ConnectingDB->query($sql);
                            while ($DataRows=$stmt->fetch()) {
                            $HeadlineforAboutPage = $DataRows['headline'];
                            $ImageforAboutPage = $DataRows['image'];
                            $TextforAboutPage = $DataRows['text'];
                        ?>
                            
                        <h1 class="text-center"><?php echo $HeadlineforAboutPage; ?></h1><br>
                            
                            
                        <img src="uploadssidebar/<?php echo htmlentities($ImageforAboutPage); ?>" class="d-block img-fluid mb-3" alt="">
                        <div class="text-center">
                            <?php echo $TextforAboutPage; ?>

                        </div>
                        <?php } ?>
                    </div>
                </div>
            
            </div>
            <!-- Main Area End -->
            
            
            <!-- Side Area Start -->
            <?php require_once("sidearea.php"); ?>
            <!-- Side Area End -->
        </div>
    </div>
    
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