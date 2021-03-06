
<?php require_once("includes/DB.php"); ?>
<?php require_once("includes/Functions.php"); ?>
<?php require_once("includes/sessions.php"); ?>
<!-- Fetching Existing Data -->
<?php 
        $SearchQueryParameter = $_GET["username"];
        global $ConnectingDB;
        $sql="SELECT aname,aheadline,abio,aimage FROM admins WHERE username=:username";
        $stmt=$ConnectingDB->prepare($sql);
        $stmt->bindValue(':username', $SearchQueryParameter);
        $stmt->execute();
$Result = $stmt->rowcount();
if($Result==1){
    while ($Datarows=$stmt->fetch()){
        $ExistingName=$Datarows["aname"];
        $ExistingBio=$Datarows["abio"];
        $ExistingImage=$Datarows["aimage"];
        $ExistingHeadline=$Datarows["aheadline"];
    }
}else{
    $_SESSION["ErrorMessage"]="Bad Request!";
    Redirect_to("index.php?pages=1");
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
    <title>Profile</title>
</head>
<body>
    <!-- NAVBAR -->
    <?php require_once("privatenavbar.php"); ?>
    <!-- NAVBAR END -->

    <!--- HEADER -->
    <header class="bg-dark text-white py-2">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                <h1><i class="fas fa-user text-success mr-2"></i><?php echo $ExistingName; ?> </h1>
                <h3><?php echo $ExistingHeadline; ?></h3>
                </div>
            </div>
        </div>
    </header>
    <!--- HEADER END -->
    
    <!--- Main Area -->
    
    <section class="container py-2 mb-4">
        <div class="row">
            <div class="col-md-3">
                <img src="images/<?php echo $ExistingImage; ?>" class="d-block img-fluid mb-3 rounded-circle" alt="">
            </div>
            <div class="col-md-9" style="min-height:440px">
                <div class="card">
                    <div class="card-body">
                        <p class="lead"><?php echo $ExistingBio; ?></p>
                    </div>
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