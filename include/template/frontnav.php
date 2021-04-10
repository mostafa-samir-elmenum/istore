
<?php

?>
<div class="container-fluid">
    <div class="topnav d-flex">
        
            <div class="col-md-4 first">
            <i class="fas fa-envelope"></i>    
            network.eng.mostafa@gmail.com</div>
            <div class="col-md-4 second">
            <i class="fas fa-phone"></i>    
            +0258745322</div>
            <div class="col-md-4 third">
            <i class="fab fa-facebook-square fa-spin fa-2x"></i>
            <i class="fab fa-twitter-square fa-spin fa-2x"></i>
            <i class="fab fa-google-plus-square fa-spin fa-2x"></i>
            </div>
        
    </div>
</div>

<nav class="navbar navbar-expand-md navbar-light  bg-dark sticky-top" style="margin-bottom:40px">

    <a href="index.php" class="navbar-brand">istore</a>
    <button class="navbar-toggler" data-toggle="collapse" data-target="#frontnav">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="frontnav">
        <ul class="navbar-nav">
        
            <li class="nav-item">
                <a href="discount.php" class="nav-link">offer</a>
            </li>

            <li class="nav-item sess-hide">
                <a href="login.php?do=signup" class="nav-link ">signup</a>
            </li>
            <li class="nav-item sess" id='profile'>
                <a href="profil.php" class="nav-link ">profil</a>
            </li>
            <li class="nav-item sess" id="logout">
                <a href="logout.php" class="nav-link ">logout</a>
            </li>
            <li class="nav-item sess" id="admin">
                <?php
                    $stmt=$db->prepare("SELECT * FROM users WHERE username=? and groupid=1");
                    $stmt->execute(array($_SESSION['username']));
                    $count=$stmt->rowcount();
                    if($count>0)
                    {
                        echo "<a href='admin.php' class='nav-link'>dashboard</a>";
                    }
                ?>
            </li>
            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                   category
                </a>
                
                <div class="dropdown-menu">
                <?php
                getcategory();
                ?>
                </div>
                </li>
        </ul>
        <span class="navbar-text">
    mostafa samir
  </span>
    </div>
    
    <!-- <form class="form-inline" action="/action_page.php">
    <input class="form-control mr-sm-2" type="text" placeholder="Search">
    <button class="btn btn-success" type="submit">Search</button>
  </form> -->

  
</nav>

                
          