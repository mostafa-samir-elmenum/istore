<?php
session_start();
include "connection.php";
include "init.php";
include $function."backfunction.php";
include $template."frontheader.php";
include $template."frontnav.php";
if($_SERVER['REQUEST_METHOD']=="POST")
{
 $email=$_POST['email'];
 $pass=$_POST['pass'];

 $stmt=$db->prepare("SELECT * FROM users WHERE email=? and password=? and status=1");
 $stmt->execute(array($email,$pass));
 $users=$stmt->fetch();
 
 if($email==$users['email'])
 {
     header("location:index.php");
     $_SESSION['username']=$users['username'];
 }else{
     echo "<div class='alert alert-danger>this email not found</div>";
     header("location:login.php?do=login");
 }
}

include $template."footer.php";