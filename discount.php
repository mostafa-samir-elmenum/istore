<?php
include "connection.php";
include "init.php";
include $function."backfunction.php";
include $template."frontheader.php";
include $template."frontnav.php";

/**start fetch all offers */
$stmt=$db->prepare("SELECT * FROM offer");
$stmt->execute();
$offers=$stmt->fetchAll();
$count=$stmt->rowcount();
/**start fetch all offers */
?>
    <!--start offer-->
    <div class="container">
        <div class="row">
            <?php
            if($count>0)
            {
            foreach($offers as $offer)
            {
                echo "<div class='col-md-4 col-sm-12'>
                    <div class='card showitem'>
                        <div class='card-header'>
                        <img class='img-thumbnails' width='100%' height='250px' src='upload/offer/".$offer['image']."'>
                        </div>
                        <div class='card-body'>
                            <h2>".$offer['offname']."</h2>
                            <p class='lead'>".$offer['offdescription']."</p>
                            <p style='text-decoration:line-through'>".$offer['offoldprice']."</p>
                            <p p class='lead'>".$offer['offnewprice']."</p>
                            <p p class='lead'>".$offer['date']."</p>
                        </div>
                    </div>

                </div>";
            }
        }
        else
        {
            echo "<div class='alert alert-danger no-data'>there's no items to show</div>";
        }
            ?>
        </div>
    </div>

    <!--end offer-->
 <!-- Start footer  -->
 <footer >
          <h2>copy right &copy;error 404 team </h2>
      </footer>
<!-- End footer -->
<?php
/**start include js files */
include $template."footer.php";
?>