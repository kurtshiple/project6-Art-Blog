<div class="col-sm-4">
                <div class="card mt-4">
                    <div class="card-body">
                        <?php
                            global $ConnectingDB;
                            $sql = "SELECT * FROM sidebarcontent ORDER BY id DESC LIMIT 1";
                            $stmt = $ConnectingDB->query($sql);
                            while ($DataRows=$stmt->fetch()) {
                            $HeadlineforSidebar = $DataRows['headline'];
                            $ImageforSidebar = $DataRows['image'];
                            $TextforSidebar = $DataRows['text'];
                        ?>
                            
                        <h1 class="text-center"><?php echo $HeadlineforSidebar; ?></h1><br>
                            
                            
                        <img src="uploadssidebar/<?php echo htmlentities($ImageforSidebar); ?>" class="d-block img-fluid mb-3" alt="">
                        <div class="text-center">
                            <?php echo $TextforSidebar; ?>

                        </div>
                        <?php } ?>
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