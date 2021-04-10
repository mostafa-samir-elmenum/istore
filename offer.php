<?php
include "connection.php";
include "init.php";
include $template."header.php";
$do=isset($_GET['do'])?$_GET['do']:'manage';
if($do=="manage")
{
            //offers input
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per-page']) && $_GET['per-page'] <= 50 ? (int)$_GET['per-page'] : 5;
//positioning
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;
//query
$articles = $db->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM offer LIMIT {$start},{$perPage}");
$articles->execute();
$articles = $articles->fetchAll(PDO::FETCH_ASSOC);
//pages
$total = $db->query("SELECT FOUND_ROWS() as total")->fetch()['total'];
$pages = ceil($total / $perPage);
    echo "<h1 class='text-center'>manage offer</h1>";
    $stmt=$db->prepare("SELECT * FROM offer");
    $stmt->execute();
    $offers=$stmt->fetchAll();

    ?>
    <div class="container-fluid">
    <table class="table table-border table-hover">
        <thead class="thead-dark">
            <th>name</th>
            <th>description</th>
            <th>old price</th>
            <th>new price</th>
            <th>date</th>
            <th>action</th>
        </thead>
        <?php foreach ($articles as $offer): ?>
				<div class="article">
					<p class="lead">
						<?php
						     echo "<tr>
                             <td>".$offer['offname']."</td>
                             <td style='width:200px'>".$offer['offdescription']."</td>
                             <td>".$offer['offoldprice']."</td>
                             <td>".$offer['offnewprice']."</td>
                             <td>".$offer['date']."</td>
                             <td>
                             <a class='btn btn-success' href='offer.php?do=edit&offerid=".$offer['oid']."'>
                             <i class='fas fa-edit'></i>
                             </a>

                             <a style='margin-left:35px' class='btn btn-danger' href='offer.php?do=delete&offerid=".$offer['oid']."'>
                             <i class='far fa-trash-alt'></i>
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
           /*  foreach($offers as $offer)
            {
                echo "<tr>
                    <td>".$offer['offname']."</td>
                    <td>".$offer['offdescription']."</td>
                    <td>".$offer['offoldprice']."</td>
                    <td>".$offer['offnewprice']."</td>
                    <td>".$offer['date']."</td>
                    <td>
                    <a href='offer.php?do=edit&offerid=".$offer['oid']."'>
                    <i class='fas fa-edit'></i>
                    </a>
                    </td>

                    <td>
                    <a href='offer.php?do=delete&offerid=".$offer['oid']."'>
                    <i class='far fa-trash-alt'></i>
                    </a>
                    </td>
                </tr>";
            } */
        ?>
    </table>
    
            <a href="offer.php?do=add">
            <i class="fas fa-plus-circle fa-2x"></i>
            </a>
            </div>
    <?php
}
elseif($do=="add")
{
    echo "<h1 class='text-center'>add new offer</h1>";
    ?>
    <div class="container">
    <a href="offer.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <form action="offer.php?do=insert" method="post" class="form was-validated" enctype="multipart/form-data" autocomplete="off" >
        <div class="form-group">
            <label for="">offer name</label>
            <input type="text" name="name" class="form-control" required placeholder="enter offer name">
            <div class="valid-feedback">correct</div>
    <div class="invalid-feedback">this field is required</div>
        </div>

        <div class="form-group">
            <label for="">offer description</label>
            <input type="text" name="desc" class="form-control" required placeholder="enter offer description">
            <div class="valid-feedback">correct</div>
            <div class="invalid-feedback">this field is required</div>
        </div>

        <div class="form-group">
            <label for="">offer oldprice</label>
            <input type="text" name="old" class="form-control" required placeholder="enter offer old price">
            <div class="valid-feedback">correct</div>
            <div class="invalid-feedback">this field is required</div>
        </div>

        <div class="form-group">
            <label for="">offer newprice</label>
            <input type="text" name="new" class="form-control" required placeholder="enter offer new price">
            <div class="valid-feedback">correct</div>
             <div class="invalid-feedback">this field is required</div>
        </div>

        <div class="form-group">
            <label for="">offer image</label>
            <input type="file" name="image" class="form-control" required placeholder="enter offer image">
            <div class="valid-feedback">correct</div>
             <div class="invalid-feedback">this field is required</div>
        </div>
        <input type="submit" value="add new offer" class="btn btn-success">
    </form>
    
    </div>
    <?php
}
elseif($do=="insert")
{
    ?>
    <a href="offer.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    echo "<h1>insert new offer</h1>";
    if($_SERVER['REQUEST_METHOD']=='POST')
    {
        $name=$_POST['name'];
        $desc=$_POST['desc'];
        $old=$_POST['old'];
        $new=$_POST['new'];
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
        move_uploaded_file($avatartemp, "upload/offer//".$avatar);
        /*check data*/

        if(is_numeric($name))
        {
            $formerror[]="name can not be number";
        }
        elseif(is_numeric($desc))
        {
            $formerror[]="description can not be number";
        }
        elseif(! is_numeric($old))
        {
            $formerror[]="price must be a number";
        }
        elseif(! is_numeric($new))
        {
            $formerror[]="price must be a number";
        }
        /* elseif(htmlspecialchars($name))
        {
            $formerror[]="name can not be special chars";
        } */
       
        foreach($formerror as $error)
        {
            echo "<div class='alert alert-danger'>
            <h2>".$error."</h2>
            </di>";
        }
       
        if(empty($formerror))
        {
            $stmt=$db->prepare("INSERT INTO offer (offname,offdescription,offoldprice,offnewprice,image)
            VALUES(:zn,:zd,:zo,:zw,:zi)
            ");
            $stmt->execute(array(
                ":zn"=>$name,
                ":zd"=>$desc,
                ":zo"=>$old,
                ":zw"=>$new,
                ":zi"=>$avatar
            ));
            $count=$stmt->rowcount();
            if($count>0)
            {
                echo "<div class='alert alert-success'>
            <h2>".$count." is added </h2>
            </di>";
            }
            else
            {
                echo "<div class='alert alert-danger'>
            <h2>no record added</h2>
            </di>";
            }
        }
    }

}
elseif($do=="edit")
{
    ?>
    <a href="offer.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    echo "<h1>edit</h1>";
    $offerid=isset($_GET['offerid'])&&is_numeric($_GET['offerid'])?intval($_GET['offerid']):0;
    $stmt=$db->prepare("SELECT * FROM offer where oid=?");
    $stmt->execute(array($offerid));
    $offers=$stmt->fetch();

    ?>
    <div class="container">
    <form action="offer.php?do=update" method="post" class="form was-validated" enctype="multipart/form-data" autocomplete="off" >
    <input type="hidden" name="id" value="<?php echo $offers['oid'] ?>" class="form-control">
        <div class="form-group">
            <label for="">offer name</label>
            <input type="text" name="name" value="<?php echo $offers['offname'] ?>" class="form-control"  placeholder="enter offer name">
            <div class="valid-feedback">correct</div>
    <div class="invalid-feedback">this field is required</div>
        </div>

        <div class="form-group">
            <label for="">offer description</label>
            <input type="text" name="desc" value="<?php echo $offers['offdescription'] ?>" class="form-control"  placeholder="enter offer name">
            <div class="valid-feedback">correct</div>
            <div class="invalid-feedback">this field is required</div>
        </div>

        <div class="form-group">
            <label for="">offer oldprice</label>
            <input type="text" name="old" value="<?php echo $offers['offoldprice'] ?>" class="form-control"  placeholder="enter offer old price">
            <div class="valid-feedback">correct</div>
            <div class="invalid-feedback">this field is required</div>
        </div>

        <div class="form-group">
            <label for="">offer newprice</label>
            <input type="text" name="new" class="form-control" value="<?php echo $offers['offnewprice'] ?>"  placeholder="enter offer new price">
            <div class="valid-feedback">correct</div>
             <div class="invalid-feedback">this field is required</div>
        </div>

        <div class="form-group">
            <label for="">offer image</label>
            <input type="text" name="image" class="form-control" value="<?php echo $offers['image'] ?>"  placeholder="enter offer new price">
            <div class="valid-feedback">correct</div>
             <div class="invalid-feedback">this field is required</div>
        </div>

        <input type="submit" value="update offer" class="btn btn-success">
    </form>
    </div>
    <?php
}
    elseif($do=="update")
    {
        ?>
    <a href="offer.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
        echo "<h1>update</h1>";
        if($_SERVER['REQUEST_METHOD']=="POST")
        {
            $id=$_POST['id'];
            $name=$_POST['name'];
            $desc=$_POST['desc'];
            $old=$_POST['old'];
            $new=$_POST['new'];
           
        $formerror=[];
        
       
        foreach($formerror as $error)
        {
            echo "<div class='alert alert-danger'>". $error ."</div>";
        }
        

        if(is_numeric($name))
        {
            $formerror[]="name can not be number";
        }
        elseif(is_numeric($desc))
        {
            $formerror[]="description can not be number";
        }
        
        /* elseif(htmlspecialchars($name))
        {
            $formerror[]="name can not be special chars";
        } */
       
        foreach($formerror as $error)
        {
            echo "<div class='alert alert-danger'>
            <h2>".$error."</h2>
            </di>";
        }
       
        if(empty($formerror))
        {
            $stmt=$db->prepare("UPDATE offer SET offname=?,offdescription=?,
            offoldprice=?,offnewprice=? where oid=?
            ");
          
            $stmt->execute(array(
                $name,
                $desc,
                $old,
                $new,
                
                $id));
            $count=$stmt->rowcount();
            if($count>0)
            {
                echo "<div class='alert alert-success'>".$count. " is updated</div>";
            }
            else
            {
                echo "<div class='alert alert-danger'>no record updated</div>";
            }
        }
        }
    }
    elseif($do=="delete")
    {
        ?>
    <a href="offer.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
        echo "<h1 class='text-center'>delete</h1>";
        $offerid=isset($_GET['offerid'])&&is_numeric($_GET['offerid'])?intval($_GET['offerid']):0;
        $stmt=$db->prepare("SELECT * FROM offer WHERE oid=?");
        $stmt->execute(array($offerid));
        $count=$stmt->rowcount();
        if($count>0)
        {
            $stmt=$db->prepare("DELETE FROM offer WHERE oid=:zid");
            $stmt->execute(array(
                ":zid"=>$offerid
            ));
            $count=$stmt->rowcount();
            if($count>0)
            {
                echo "<div class='alert alert-success'>".$count." was deleted</div>";
            }
            else
            {
                echo "<div class='alert alert-success'>no record  deleted</div>";
            }
        }
        else
        {
            echo "<div class='alert alert-danger'>no record found</div>";
        }
    }
    else
    {
        echo "<h1 class='alert alert-danger'>no page</h1>";
    }


/**start footer */
include $template."footer.php";
?>