
<?php
session_start();
include "connection.php";
include "init.php";
include $template."header.php";

echo "<h1 class='text-center'>items page</h1>";
/*create do to make page*/
$do=isset($_GET['do'])?$_GET['do']:"manage";

/*start manage page */
if($do=="manage")
{
        //items input
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per-page']) && $_GET['per-page'] <= 50 ? (int)$_GET['per-page'] : 5;
//positioning
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;
//query
$articles = $db->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM items LIMIT {$start},{$perPage}");
$articles->execute();
$articles = $articles->fetchAll(PDO::FETCH_ASSOC);
//pages
$total = $db->query("SELECT FOUND_ROWS() as total")->fetch()['total'];
$pages = ceil($total / $perPage);
    echo "<h1 class='text-center'>manage page</h1>";
    $stmt=$db->prepare("SELECT * FROM items");
    $stmt->execute();
    $items=$stmt->fetchAll();
    ?>
   <div class="container-fluid">
   <div class="row">
   <div class="col-md-12 col-sm-12">
   <table class="table table-border table-hover">
    <thead class="thead-dark">
    <th>name</th>
    <th class='h'>description</th>
    <th class='h'>price</th>
    <th class='h'>count</th>
    <th class='h'>country made</th>
    <th class='h'>date</th>
    <th class='h'>rate</th>

    <th >action</th>
    </thead>
    <?php foreach ($articles as $item): ?>
				<div class="article">
					<p class="lead">
						<?php
						     echo "<tr>
                             <td>".$item['name']."</td>
                            <td class='h' style='width:150px'>".$item['description']."</td>
                            <td class='h'>".$item['price']."</td>
                            <td class='h'>".$item['count']."</td>
                            <td class='h'>".$item['countrymade']."</td>
                            <td class='h'>".$item['date']."</td>
                            <td class='h'>".$item['rate']."</td>
                            <td>
                            <a href='items.php?do=edit&itemid=".$item['id']."' class='btn btn-success'>
                            <i class='fas fa-edit'></i>
                            </a>

                            <a style='margin-left:35px' href='items.php?do=delete&itemid=".$item['id']."' class='btn btn-danger'>
                            <i class='fas fa-times'></i>
                            </a>
                            </td>
                     
            
                             </tr>";
						?>
					</p>
				</div>
				<?php endforeach ?>
			</div>
			<div class="col-md-12">
				<div class="well well-sm">
					<div class="paginate">
						<?php for ($x=1; $x <= $pages; $x++): ?>
						<ul class="pagination">
							<li>
								<a href="?page=<?php echo $x; ?>&per-page=<?php echo $perPage; ?>">
									<?php
										echo $x;
									?>
								</a>
							</li>
						</ul>
						<?php endfor; ?>
					</div>
				</div>
    <?php
    /* foreach($items as $item)
    {
        echo "<tr>
        <td>".$item['name']."</td>
        <td>".$item['description']."</td>
        <td>".$item['price']."</td>
        <td>".$item['count']."</td>
        <td>".$item['countrymade']."</td>
        <td>".$item['date']."</td>
        <td>".$item['rate']."</td>
        <td>
        <a href='items.php?do=edit&itemid=".$item['id']."' class='btn btn-success'>edit</a>
        </td>
        <td>
        <a href='items.php?do=delete&itemid=".$item['id']."' class='btn btn-danger'>
        <i class='fas fa-times'></i>
        </a>
        </td>
        </tr>";
    } */
    ?>
    </table>
    <a href="items.php?do=add">
                    <i class="fas fa-plus-circle fa-2x"></i>
                    </a>
   </div>
   </div>
   </div>

    <?php
}
/*end manage page */

/*start add page*/
elseif($do=="add")
{
    echo "<h1 class='text-center'>add page</h1>";
    ?>
    <a href="items.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    $stmt=$db->prepare("SELECT * from category");
    $stmt->execute();
    $data=$stmt->fetchAll();
    ?>
    <div class="container">
  
    <div class="row">
    
    <div class="col-md-6 col-sm-12">
    <form class="form was-validated" enctype="multipart/form-data" autocomplete="off" action="items.php?do=insert" method="post">
  

    <!--start name-->
    <div class="form-group">
    <label for="">item name</label>
    <input type="text" name="name" class="form-control" required placeholder="enter item name" >
    <div class="valid-feedback">correct</div>
    <div class="invalid-feedback">this field is required</div>
    </div>
    <!--end name-->

    <!--start description-->
    <div class="form-group">
    <label for="">item description</label>
    <input type="text" name="desc" class="form-control" required placeholder="enter item description">
    <div class="valid-feedback">correct</div>
    <div class="invalid-feedback">this field is required</div>
    </div>
    <!--end description-->

    <!--start price-->
    <div class="form-group">
    <label for="">item price</label>
    <input type="text" name="price" class="form-control" required placeholder="enter item price">
    <div class="valid-feedback">correct</div>
    <div class="invalid-feedback">this field is required</div>
    </div>
    <!--end price-->

    <!--start country made-->
    <div class="form-group">
    <label for="">item country made</label>
    <input type="text" name="country" class="form-control" required placeholder="enter item coutry made">
    <div class="valid-feedback">correct</div>
    <div class="invalid-feedback">this field is required</div>
    </div>
    <!--end country made-->

    <!--start date-->
    <!-- <div class="form-group">
    <label for="">item date</label>
    <input type="date" name="date" class="form-control" required>
    <div class="valid-feedback">correct</div>
    <div class="invalid-feedback">this field is required</div>
    </div> -->
    <!--end date-->

    <!--start image-->
    <div class="form-group">
    <label for="">item image</label>
    <input type="file" name="image" class="form-control" required>
    <div class="valid-feedback">correct</div>
    <div class="invalid-feedback">this field is required</div>
    </div>
    <!--end image-->


     <!--start count-->
     <div class="form-group">
    
    <input type="hidden" name="count" class="form-control">
    <div class="valid-feedback">correct</div>
    <div class="invalid-feedback">this field is required</div>
    </div>
    <!--end count-->
    <!--start rate-->
    <div class="form-group">
    <label for="">item rate</label>
    <input type="text" name="rate" class="form-control" placeholder="enter item rate" required>
    <div class="valid-feedback">correct</div>
    <div class="invalid-feedback">this field is required</div>
    </div>
    <!--end rate-->
 <!--start cateory-->
 <div class="form-group">
    <label for="">item category</label>
   <select name="cats" id="" class="form-control">
   <?php
   foreach($data as $cat)
   {
       echo "<option value='".$cat['id']."'>".$cat['catname']."</option>";
   }
   ?>
   </select>
    <div class="valid-feedback">correct</div>
    <div class="invalid-feedback">this field is required</div>
    </div>
    <!--end category-->
     
    <input type="submit" class="btn btn-primary">
    </form>
    </div>
  
    
    <div class="content-img">test</div>

    </div>
    </div>
    <?php
}
/*end add page*/

/*start insert */
elseif($do=="insert")
{
    echo "<h1 class='text-center'>insert page</h1>";
    ?>
    <a href="items.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    /*start get data from input */
    $formerror=[];
    if($_SERVER['REQUEST_METHOD']=="POST")
    {
        $name=$_POST['name'];
        $description=$_POST['desc'];
        $price=$_POST['price'];
        $countrymade=$_POST['country'];
        /**$date=$_POST['date'];*/
        $rate=$_POST['rate'];
        $count=$_POST['count'];
        $category=$_POST['cats'];
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
move_uploaded_file($avatartemp, "upload/post photo//".$avatar);
        /*check data*/

        if(is_numeric($name))
        {
            $formerror[]="name can not be number";
        }
        elseif(is_numeric($description))
        {
            $formerror[]="description can not be number";
        }
        elseif(is_numeric($countrymade))
        {
            $formerror[]="country made  can not be number";
        }
        /* elseif(htmlspecialchars($name))
        {
            $formerror[]="name can not be special chars";
        } */
        elseif($count==0)
        {
            $count==1;
        }
        elseif($count>0)
        {
            $count++;
        }
        foreach($formerror as $error)
        {
            echo "<div class='alert alert-danger'>
            <h2>".$error."</h2>
            </di>";
        }
       
        if(empty($formerror))
        {
            $stmt=$db->prepare("INSERT INTO items(name,description,price,count,countrymade,date,image,rate,catid) 
            VALUES(:zn,:zd,:zp,:zcc,:zc,now(),:zi,:zr,:zcat)
            ");
            $stmt->execute(array(
                ":zn"=>$name,
                ":zd" =>$description,
                ":zp" =>$price,
                ":zcc" =>$count,
                ":zc" =>$countrymade,
               
                ":zi" =>$avatar,
                ":zr" =>$rate,
                ":zcat"=>$category
            ));
            $count=$stmt->rowcount();
            if($count>0)
            {
                echo "<div class='alert alert-success'>
                <h2>".$count." record is added to items :)</h2>
                </div>";
            }
            else
            {
                echo "<div class='alert alert-danger'>
                <h2>no record added to items :)</h2>
                </div>";  
            }
        }
    }
    else
    {
        echo "<div class='alert alert-danger'>
        <h2>error 404</h2>
        </div>";
    }
    /*start get data from input */
}
/**end insert */

/*start edit */
elseif($do=="edit")
{
    echo "<h1 class='text-center'>edit page</h1>";
    ?>
    <a href="items.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    $itemid=isset($_GET['itemid'])&&is_numeric($_GET['itemid'])?$_GET['itemid']:0;
    $stmt=$db->prepare("SELECT items.*,category.catname FROM items
    inner join category on category.id=items.catid
     WHERE items.id=?");
    $stmt->execute(array($itemid));
    $show=$stmt->fetch();
    ?>
    <div class="container">
    <div class="row">
    <div class="col-md-6 col-sm-12">
        <form action="items.php?do=update" method="post" class="form was-validated" autocomplete="off">
        <input type="hidden" name="id" class="form-control" value="<?php echo $show['id'] ?>">
            <div class="form-group">
            <label for="">item name</label>
            <input type="text" name="name" class="form-control" value="<?php echo $show['name'] ?>">
            </div>

            <div class="form-group">
            <label for="">item description</label>
            <input type="text" name="desc" class="form-control" value="<?php echo $show['description'] ?>">
            </div>

            <div class="form-group">
            <label for="">item price</label>
            <input type="text" name="price" class="form-control" value="<?php echo $show['price'] ?>">
            </div>

            <div class="form-group">
            <label for="">item count</label>
            <input type="text" name="count" class="form-control" value="<?php echo $show['count'] ?>">
            </div>

            <div class="form-group">
            <label for="">item country made</label>
            <input type="text" name="country" class="form-control" value="<?php echo $show['countrymade'] ?>">
            </div>

            <div class="form-group">
            <label for="">item date</label>
            <input type="date" name="date" class="form-control" value="<?php echo $show['date'] ?>">
            </div>

            <div class="form-group">
            <label for="">item image</label>
            <input type="text" name="image" class="form-control" value="<?php echo $show['image'] ?>">
            </div>

            <div class="form-group">
            <label for="">item rate</label>
            <input type="text" name="rate" class="form-control" value="<?php echo $show['rate'] ?>">
            </div>

            <div class="form-group">
            <label for="">item category</label>
            <input type="text" name="cats" class="form-control" value="<?php echo $show['catname'] ?>">
            </div>
            
            <input type="submit" class="btn btn-primary">
        </form>
    </div>

    <div class="col-md-6">test</div>
    </div>
    </div>
    
    <?php
}

/**end edit */


/**start update */
elseif($do=="update")
{
    echo "<h1 class='text-center'>update page</h1>";
    ?>
    <a href="items.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    $formerror=[];
    if($_SERVER['REQUEST_METHOD']=="POST")
    {
        $name=$_POST['name'];
        $description=$_POST['desc'];
        $price=$_POST['price'];
        $countrymade=$_POST['country'];
        $date=$_POST['date'];
        $image=$_POST['image'];
        $rate=$_POST['rate'];
        $count=$_POST['count'];
        $category=$_POST['cats'];
        $id=$_POST['id'];
        $stmt=$db->prepare("UPDATE items SET name=?,description=?,price=?,count=?,countrymade=?,date=?,image=? WHERE id=?");
        $stmt->execute(array($name,$description,$price,$count,$countrymade,$date,$image,$id));
        $count=$stmt->rowcount();
        if($count>0)
        {
            echo "<div class='alert alert-success'>
            <h1>".$count." is updated :)</h1>
            </div>";
        }
        else
        {
            echo "<div class='alert alert-danger'>
            <h1>no record update</h1>
            </div>";
        }
    }
    else
    {
        echo "<div class='alert alert-danger'>you are not authorized to login this page</div>";
    }
}
/**end update */

/**start delete */
elseif($do=="delete")
{
    echo "<h1 class='text-center'>delete page</h1>";
    ?>
    <a href="items.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    $itemid=isset($_GET['itemid'])&&is_numeric($_GET['itemid'])?intval($_GET['itemid']):0;
    $stmt=$db->prepare("SELECT * FROM items WHERE id=?");
    $stmt->execute(array($itemid));
    $count=$stmt->rowcount();
    if($count>0)
    {
        $stmt=$db->prepare("DELETE FROM items WHERE id=:zid");
        $stmt->execute(array(
            ":zid"=>$itemid
        ));
        $count=$stmt->rowcount();
        if($count>0)
        {
            echo "<div class='alert alert-success'>
            <h1>".$count." record is deleted :)</h1>
            </div>";
        }
        else
        {
            echo "<div class='alert alert-danger'>
            <h1>no record delete</h1>
            </div>";
        }
    }
    else{
        echo "<div class='alert alert-danger'>
            <h1>no record found</h1>
            </div>";
    }
}
/**end delete */
include $template."header.php";

?>
