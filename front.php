<?php
include "connection.php";
include "init.php";
include $template."header.php";

/**start navbar */
include $template."navbar.php";
/**end navbar */

/**start carousel */
?>
<div id="demo" class="carousel slide" data-ride="carousel">

<!-- Indicators -->
<ul class="carousel-indicators">
  <li data-target="#demo" data-slide-to="0" class="active"></li>
  <li data-target="#demo" data-slide-to="1"></li>
  <li data-target="#demo" data-slide-to="2"></li>
</ul>

<!-- The slideshow -->
<div class="carousel-inner">
  <div class="carousel-item active">
    <img src="layout/img/images (8).jpg" alt="Los Angeles">
  </div>
  <div class="carousel-item">
    <img src="layout/img/images (8).jpg" alt="Chicago">
  </div>
  <div class="carousel-item">
    <img src="layout/img/images (8).jpg" alt="New York">
  </div>
</div>

<!-- Left and right controls -->
<a class="carousel-control-prev" href="#demo" data-slide="prev">
  <span class="carousel-control-prev-icon"></span>
</a>
<a class="carousel-control-next" href="#demo" data-slide="next">
  <span class="carousel-control-next-icon"></span>
</a>

</div>



<div class="container">
    <div class="row">
        <div class="col-md-3">
            test
        </div>

        <div class="col-md-9">
            <?php
            $stmt=$db->prepare("SELECT * FROM items order by id ASC");
            $stmt->execute();
            $items=$stmt->fetchAll();
            ?>
            <div class="row">
                <?php
                     foreach($items as $item)
                     {
                         echo "<div class='col-md-4'>
                         <p>".$item['name']."</p>
                         <p>".$item['description']."</>
                         <p>".$item['countrymade']."</p>
                         <p>".$item['price']."</p>
                         
                         </div>";
                         echo "<h1></h1>";
                     }
                ?>
            </div>
        </div>
    </div>
</div>


<?php
/**end carousel */
include $template."footer.php";