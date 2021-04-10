<?php
session_start();
include "connection.php";
include "init.php";
include $function."backfunction.php";
include $template."frontheader.php";

// Include functions and connect to the database using PDO MySQL
//include 'functions.php';
//$pdo = pdo_connect_mysql();



/**start navbar */
include $template."frontnav.php";
/**end navbar */
/**start carousel */
?>
<div class="container">
<div id="demo" class="carousel slide" data-ride="carousel" style="height:600px">

<!-- Indicators -->
<ul class="carousel-indicators">
  <li data-target="#demo" data-slide-to="0" class="active"></li>
  <li data-target="#demo" data-slide-to="1"></li>
  <li data-target="#demo" data-slide-to="2"></li>
</ul>
<?php
//echo $_SESSION['username'];
?>
<!-- The slideshow -->
<div class="carousel-inner">
  <div class="carousel-item active" style="height:600px">
    <img src="layout/img/car4.jpeg" alt="carousel1">
  </div>
  <div class="carousel-item" style="height:600px">
    <img src="layout/img/car9.jpeg" alt="carousel2">
  </div>
  <div class="carousel-item" style="height:600px">
    <img src="layout/img/car5.jpeg" alt="carousel3">
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
</div>
<?php
/**end carousel */

/**start offer */
$stmt=$db->prepare("SELECT * FROM offer order by oid desc limit 3");
$stmt->execute();
$offers=$stmt->fetchAll();
?>
      <div class="container-fluid off">
            <fieldset>
                  <legend>
                  offers
                  </legend>
            </fieldset>
            <div class="row offers">
                  
                  <?php
                        foreach($offers as $offer)
                        {
                              echo "<div class='col-md-4 '>
                                    <div class='front-back'>
                                    <div class='front'>
                                    <img class='img-thumbnail mx-auto d-block' src='upload/offer/".$offer['image']."' width='200px' style='height:200px'>
                                    <p> ".$offer['offname']."</p>
                                    </div>
                                    <div class='back'>
                                    <p >description : ".$offer['offdescription']."</p>
                                    <p class='old'>".$offer['offoldprice']."</p>
                                    <p>".$offer['offnewprice']."</p>
                                    </div>
                                    </div>
                                          
                                          
                                    
                                    </div>";
                        }
                   ?>
                 
            </div>
      </div>
<?php
/**end offer */

/**start offer overview */
?>
<div class="container-fluid overview">
<div class="offer-img">
      <div class="offer-content">
            <h2>offers</h2>
            <button class="btn btn-primary">
            <a href="discount.php">show all offers</a>      
            </button>
      </div>
</div>
</div>
<?php
/**end offer overview */
/**start show latest items*/
$stmt=$db->prepare("select items.*,category.catname from items
inner join category on items.catid=category.id

");
$stmt->execute();
$items=$stmt->fetchAll();
?>
    <div class="container">
    <fieldset>
          <legend>latest items</legend>
    </fieldset>
        <div class="row">
         <div id='foto_villa' class='carousel slide' data-ride='carousel' style="width:100%">
           <div class='carousel-inner'>
            <?php $i=0; foreach ($items as $item): ?>
            <?php if ($i==0) {$set_ = 'active'; } else {$set_ = ''; } ?> 
            <div class='carousel-item <?php echo $set_; ?>'>
          <?php
          echo "<div class='col-md-4'>"; 
          echo "<div class='card' style='height:500px'>
          <div class='card-header'>
            <h2 style='padding:10px'>".$item['name']."</h2>
            <span class='badge badge-success' style='
        '>new</span>
          </div>
        <div class='card-body' style='padding:10px'>
          <img style='width:80%;height:200px'  src='upload\post photo/".$item['image']."'>
            <p> item description : ".$item['description']."</p>
          <p> item made in : ".$item['countrymade']."</p>
          <p> date : ".$item['date']."</p>
          <p> price : ".$item['price']."</p>
          <p> category : ".$item['catname']."</p>
          
        </div>
    </div>";
        
        echo "</div>";

          ?>
		</div>
  	<?php $i++; endforeach ?>
  </div>

  
</div>  
        </div><!--end row-->
    </div><!--end container-->
    <!--start show latest items-->
    
    <!--start about us-->
        <section class="about-us">
            <div class="container">
                  <div class="row about-cover">
                        <div class="col-md-6">
                              <div class="about-text">
                                    <h1 class="text-center">about us</h1>
                                    <p class='lead'>Ecommerce, also known as electronic commerce or internet commerce, refers to the buying and selling of goods or services using the internet, and the transfer of money and data to execute these transactions. Ecommerce is often used to refer to the sale of physical products online, but it can also describe any kind of commercial transaction that is facilitated through the internet.
                                          Whereas e-business refers to all aspects of operating an online business, ecommerce refers specifically to the transaction of goods and services.
                                                So if you’re a business owner looking to stay on top of market trends .To integrate your business into this new reality, an Ecommerce Website will be your best friend. Whether you’re looking to expand the reach of your physical store, or already have a site and are looking for some inspiration to improve its performance</p>
                                    <p class='lead'>designed by mostafa samir</p>
                              </div>
                        </div>
                        <div class="col-md-6">
                              <div class="about-img">
                              
                                    <div class="img-two"></div>
                                    
                              </div>
                        </div>
                  </div><!--end row-->
            </div><!--end container-->
        </section>
    <!--start about us-->

    <!-- Start footer  -->
    <footer >
          <h2>copy right &copy;<?php echo date('Y')?> mostafa samir </h2>
      </footer>
<!--end footer-->
<?php
   
include $template."footer.php";

if(isset($_SESSION['username']))
{
      include $template."specialfooter.php";  
}
