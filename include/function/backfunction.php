<?php
//$pdo = pdo_connect_mysql();
function getcount($field,$column)
{
    global $db;
    $stmt=$db->prepare("SELECT $field FROM $column");
    $stmt->execute();
    $getcount=$stmt->rowcount();
    echo $getcount;
    //echo "<div class='alert alert-info'> user number is ".$getcount."</div>";
}


function getlatest($row,$col,$id,$v)
{
    global $db;
    $stmt=$db->prepare("SELECT $row FROM $col  order by $id DESC limit $v");
    $stmt->execute();
    $latest=$stmt->fetchAll();
    foreach($latest as $last)
    {
        echo "<h3>".$last[$row]." <span class='badge badge-primary'>New</span></h3>";

        /*  echo "<ul>
            <li>".$last[$row]."</li>
        </ul>";  */
        
    }
}


function getcategory()
{
    global $db;
    $stmt=$db->prepare("SELECT * FROM category  order by id DESC");
    $stmt->execute();
    $items=$stmt->fetchAll();
    foreach($items as $item)
    {
       

          echo "
            
            <a class='dropdown-item' href='showitems.php?do=show&catid=".$item['id']."'>".$item['catname']."</a>
            
        ";  
        
    }
}

function getitemid($itemname)
{
    global $db;
    $stmt=$db->prepare("SELECT * FROM items where name=?");
    $stmt->execute(array($itemname)); 
    $itemid=$stmt->fetch();
    
        return $itemid['id'];
    
}

function getitemuser($iuname)
{
    global $db;
    $stmt=$db->prepare("SELECT  items.*,users.* from useritem
    INNER JOIN users on users.userid=useritem.userid
    INNER JOIN items ON items.id=useritem.itemid where itemid=?");
    $stmt->execute(array($iuname)); 
    $username=$stmt->fetch();
    
        return $username['username'];
    
}



function alluser()
{
    global $db;
    $stmt=$db->prepare("SELECT email FROM users");
    $stmt->execute();
    $all=$stmt->fetchAll();
    foreach($all as $a)
    {
        return $x[]=$a;
    }
}


/**
 * function to access control panel
 */
function accessadminpanel($x)
{
    global $db;
  
    $stmt=$db->prepare("SELECT * FROM users WHERE username=$x and groupid=1");
    $stmt->execute(array($x));
    $count=$stmt->rowcount();
    if($count>0)
    {
        echo "<a href='admin.php' class='nav-link'>admin</a>";
    }
}
?>