<?php
session_start();
include "connection.php";
include "init.php";
include $function."backfunction.php";
include $template."frontheader.php";
include $template."frontnav.php";

$do=isset($_GET['do'])?$_GET['do']:'notfound';

if($do=="notfound")
{
    echo "<div class='alert alert-danger'>this page not found</div>";
}
elseif($do=="login")
{
    /**start login */

?>

<section class='login' >
    <div class="container">
        <h1 class="text-center">login page</h1>
        <div class="row login-color">
            <div class="col-md-6 form-login">
            <h1 class="text-center">istore</h1>
                <form action="checklogin.php" method="post" class="form was-validated" autocomplete="off">
                    <div class="form-group">
                        <label for="">email </label>
                        <input type="email" name="email" placeholder="enter your email" required class="form-control">
                        <div class="valid-feedback">correct</div>
                        <div class="invalid-feedback">this field is required</div>
                    </div>

                    <div class="form-group">
                        <label for="">password </label>
                        <input type="password" name="pass" placeholder="enter your password" required class="form-control">
                        <div class="valid-feedback">correct</div>
                        <div class="invalid-feedback">this field is required</div>
                    </div>

                    <input type="submit" value="login" class="btn btn-primary">
                    <button class="btn btn-success">
                        <a href="login.php?do=signup" style='list-style:none;color:#fff;font-weight:bold'>signup</a>
                    </button>
                </form>
            </div>
            <div class="col-md-6 login-content">
                <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing.</p>
                <div class="element">
                  <div class="sub-element">Hello, This is a my Website.</div>
                  <div class="sub-element">designed by mostafa samir.</div>
                  <div class="sub-element">If you need this website, Please contact us.</div>
                </div>

                <h2>error 404 </h2>
            </div>
        </div><!--end row-->
    </div><!--end container-->
</section>
<?php
/**end  login */
}

elseif($do=="signup")
{
    if($_SERVER['REQUEST_METHOD']=="POST")
    {
        $name=$_POST['username'];
        $pass=$_POST['pass'];
        $shapass=sha1($pass);
        $x=alluser();
        $email=$_POST['email'];
        $avatar=$_FILES['image'];
        //print_r($avatar);
        $avatarname=$_FILES['image']['name'];
        $avatarsize=$_FILES['image']['size'];
        $avatartype=$_FILES['image']['type'];
        $avatartemp=$_FILES['image']['tmp_name'];
        $avatartemp=$_FILES['image']['tmp_name'];
        //echo $avatarname . $avatarsize .$avatartemp .$avatartype;
        $avatarallow=array("jpg","png","gif","jpeg");
        $formerror=[];
        /* $avatarextension=strtolower(end(explode(".",$avatarname)));
        if(! in_array($avatarextension,$avatarallow))
        {
            $formerror[]="this extension not avaliable";
        } */
        if(empty($avatarname))
        {
            $formerror[]="avatar name is must";
        }
        if($avatarsize>4194304)
        {
            $formerror[]="avatar size can be 4 MB";
        }
        if(empty($formerror))
{
    $avatar=rand(0,100000)."-".$avatarname;
}
foreach($formerror as $error)
{
    echo "<div class='alert alert-danger'>". $error ."</div>";
}
move_uploaded_file($avatartemp, "upload/user//".$avatar);
        /*check data*/
        if(strlen($name)<=3)
        {
            $formerror[]="<div class='alert alert-danger'>user name must larger than 4 characters !!</div>";
        }
        elseif(strlen($pass)<=8)
        {
            $formerror[]="<div class='alert alert-danger'>password must larger than 8 characters !!</div>";
        }
        elseif(is_numeric($name))
        {
            $formerror[]="<div class='alert alert-danger'>user name can not be a number !!</div>";
        }
        
        
        elseif(in_array($email,$x))
        {
            $formerror[]="<div class='alert alert-danger'>this email is exists !!</div>"; 
        }
        foreach($formerror as $error)
        {
            echo "<div class='alert alert-danger'>
            <h2>".$error."</h2>
            </di>";
        }
       
        if(empty($formerror))
        {
            $stmt=$db->prepare("INSERT INTO users(username,password,email,image)
            VALUES(:zn,:zp,:ze,:zi);
            ");
            $stmt->execute(array(
                ":zn"=>$name,
                ":zp"=>$pass,
                ":ze"=>$email,
                ":zi"=>$avatar
            ));
            $count=$stmt->rowcount();
            if($count>0)
            {
                echo "<div class='alert alert-success'>now you are login wait for activation</div>";
            }
            else
            {
                echo "<div class='alert alert-danger'>error try again</div>";
   
            }
        }   
    }

?>
    <section class="signup">
        <div class="container">
            <div class="row">
                <div class="col-md-6 signup-content">
                    <h1>our deals are so good we can only share them with our member !</h1>
                    <div class="element">
                  <div class="sub-element">Hello, This is a my Website.</div>
                  <div class="sub-element">designed by mostafa samir.</div>
                  <div class="sub-element">If you need this website, Please contact us.</div>
                </div>
                </div>
                <div class="col-md-6 signup-form">
                    <h1 class="text-center">sign up !</h1>
                    
                    <form action="" method="post" enctype="multipart/form-data" class="form was-validated" autocomplete="off">
                        <!--start username-->
                        <div class="form-group">
                            <label for="">username</label>
                            <input type="text" name="username" required placeholder="enter your name" class="form-control">
                            <div class="valid-feedback">correct</div>
                            <div class="invalid-feedback">this field is required</div>
                        </div>
                        <!--end user name-->

                        <!--start email-->
                        <div class="form-group">
                            <label for="">email</label>
                            <input type="email" name="email" required placeholder="enter your email" class="form-control">
                            <div class="valid-feedback">correct</div>
                            <div class="invalid-feedback">this field is required</div>
                        </div>
                        <!--end email-->

                        <!--start password-->
                        <div class="form-group">
                            <label for="">password</label>
                            <input type="password" name="pass" required placeholder="enter your password" class="form-control">
                            <div class="valid-feedback">correct</div>
                            <div class="invalid-feedback">this field is required</div>
                        </div>
                        <!--end password-->

                        <!--start image-->
                        <div class="form-group">
                            <label for="">image</label>
                            <input type="file" name="image" required placeholder="enter your image" class="form-control">
                            <div class="valid-feedback">correct</div>
                            <div class="invalid-feedback">this field is required</div>
                        </div>
                        <!--end user image-->

                        <input type="submit" value="signup" class="btn btn-success" name="register">
                        <button class="btn btn-primary">
                            <a href="login.php?do=login" style='list-style:none;color:#fff;font-weight:bold'>login</a>
                        </button>
                    </form>
                </div>
            </div><!--endrow-->
        </div><!--endcontainer-->
    </section>

    <?php
}

include $template."footer.php";