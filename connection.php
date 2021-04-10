<?php
 $dsn="mysql:host=localhost
 ;dbname=id12492013_istore	
 ;DB_PORT=3306";
$user="id12492013_mostafa";
$password="pa55W.rd@@@123";

try
{
    $db=new PDO ($dsn,$user,$password);
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
   // echo "good";
}
catch (pdoException $e)
{
    echo "failed". $e->getMessage();
} 


