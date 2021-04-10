<?php
session_start();
if(isset($_SESSION['username']))
{
    include "connection.php";
    include "init.php";
    include $function."backfunction.php";
    include $template."frontheader.php";
    include $template."frontnav.php";
    $do=isset($_GET['do'])?$_GET['do']:'profile';

    if($do=='profile')
    {
        ?>
           <section class="profile">
    <div class="container">
        <div class="show-data">
      
    <?php
    $test=$_SESSION['username'];
    $stmt=$db->prepare("SELECT * FROM users WHERE username=?");
    $stmt->execute(array($test));
    $users=$stmt->fetch();
    
        echo "<div class='show-img'>
            <div class='user-img'>
            <img src='upload/user/".$users['image']."' class='rounded-circle' width='300px'>
            </div>
        
        </div>";

        echo "<div class='inner-content'>
        
        <div class='card'>
            <div class='card-head'>
                <h2 class='text-center'>information</h2>
            </div>
            <div class='card-body'>
                <p class='lead'>user name : ".$users['username']."</p>
                <p class='lead'> email : ".$users['email']."</p>
            </div>
            <a href='profil.php?do=edit' class='btn btn-success'>update your info</a>
        </div>
        </div>";
    
?>
  </div>
    </div>
    </section>
        <?php
    }
    elseif($do=='edit')
    {
        echo "<h2 class='text-center'>update your information</h2>";
        $testname=$_SESSION['username'];
        $stmt=$db->prepare("SELECT * FROM users WHERE username=?");
        $stmt->execute(array($testname));
        $data=$stmt->fetch();
        ?>
        <form action="profil.php?do=update" method="post" class="form was-validated" placeholder="off">
        <input type="hidden" class="form-control" name='id' value="<?php echo $data['userid'] ?>">

        <div class="form-group">
                <label for="">user name</label>
                <input type="text" class="form-control" name='user' value="<?php echo $data['username'] ?>">
            </div>

            <div class="form-group">
                <label for="">email</label>
                <input type="email" class="form-control" name='email' value="<?php echo $data['email'] ?>">
            </div>

            <input type="hidden" class="form-control" name='oldpass' value="<?php echo $data['password'] ?>">


            <div class="form-group">
                <label for="">password</label>
                <input type="password" class="form-control" name='newpass' value="<?php echo $data['password'] ?>">
            </div>
            <input type="submit" value="update" class="btn btn-primary">
           
            <a href="profil.php" class="btn btn-danger" >back</a>
        </form>

        <?php

    }

    elseif($do=="update")
    {
        if($_SERVER['REQUEST_METHOD']=="POST")
        {
            $user=$_POST['user'];
            $email=$_POST['email'];
            $newpass=$_POST['newpass'];
            $oldpass=$_POST['oldpass'];
            $id=$_POST['id'];
            $testname=$_SESSION['username'];
            //chech data enter by users
            $formerror=[];
            if(strlen($user)<=4)
            {
                $formerror[]="user name must be larger than 4 character";
            }
            elseif(is_numeric($user))
            {
                $formerror[]="user name can not be a number";
            }
            elseif(empty($user))
            {
                $formerror[]="user name can not be empty";
            }
            elseif(empty($email))
            {
                $formerror[]="email can not be empty";
            }
            elseif(empty($newpass))
            {
                $formerror[]="password ca not be empty";
            }
            elseif(strlen($newpass)<=8)
            {
                $formerror[]="password must be larger than 8 character";
            }
            elseif($oldpass==$newpass)
            {
                $formerror[]="password can not same the password already wxists";
            }
            foreach($formerror as $error)
            {
                echo "<div class='alert alert-danger'>".$error."</div>";
            }

            if(empty($formerror))
            {
                $stmt=$db->prepare("UPDATE users SET username=?,email=?,password =? WHERE userid=? ");
                $stmt->execute(array($user,$email,$newpass,$id));
                $count=$stmt->rowcount();
                if($count>0)
                {
                    echo "<div class='alert alert-success'>your data is updated successfully</div>";
                }
                else
                {
                    echo "<div class='alert alert-danger'>no data updated please try again</div>";
                    
                }
            }

            {}
        }
    }

    else
    {
        echo "<div>not found</div>";
    }
    ?>
 
<?php
 
/**start include js files */
    include $template."footer.php";
    include $template."specialfooter.php"; 
}
else{
    echo "<div class='alert alert-danger'>you are not allow to open this page</div>";
}