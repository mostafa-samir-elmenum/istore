
<?php
include "connection.php";
include "init.php";
include $template."header.php";

$do=isset($_GET['do'])?$_GET['do']:"manage";
if($do=="manage")
{
            //category input
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per-page']) && $_GET['per-page'] <= 50 ? (int)$_GET['per-page'] : 5;
//positioning
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;
//query
$articles = $db->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM category LIMIT {$start},{$perPage}");
$articles->execute();
$articles = $articles->fetchAll(PDO::FETCH_ASSOC);
//pages
$total = $db->query("SELECT FOUND_ROWS() as total")->fetch()['total'];
$pages = ceil($total / $perPage);
    echo "<h1 class='text-center'>manage page</h1>";
    $stmt=$db->prepare("SELECT * FROM category");
    $stmt->execute();
    $cats=$stmt->fetchAll();
    ?>
    <div class="container-fluid">
    <table class="table table-border table-hover">
    <thead class="thead-dark">
    <th>category name</th>
    <th>description</th>
    <th>action</th>
    </thead>
    <?php foreach ($articles as $cat): ?>
				<div class="article">
					<p class="lead">
						<?php
						     echo "<tr>
                             <td>".$cat['catname']."</td>
                             <td>".$cat['description']."</td>
                             <td>
                             <a href='category.php?do=edit&catid=".$cat['id']."' class='btn btn-success'>edit</a>
                             
                             <a style='margin-left:35px' href='category.php?do=delete&catid=".$cat['id']."' class='btn btn-danger'>
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
  /*   foreach($cats as $cat)
    {
        echo "<tr>
        <td>".$cat['catname']."</td>
        <td>".$cat['description']."</td>
        <td>
        <a href='category.php?do=edit&catid=".$cat['id']."' class='btn btn-success'>edit</a>
        </td>

        <td>
        <a href='category.php?do=delete&catid=".$cat['id']."' class='btn btn-danger'>
        <i class='fas fa-times'></i>
        </a>
        </td>
        </tr>";
    } */
    ?>
    </table>
    <a href="category.php?do=add">
                    <i class="fas fa-plus-circle fa-2x"></i>
                    </a>

                    </div>
    <?php
}

/**start add */
elseif($do=="add")
{
    echo "<h1 class='text-center'>add page</h1>";
    ?>
    <div class="container">
    
    <a href="category.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    
    <form action="category.php?do=insert" method="post" class="form was-validated" autocomplete="off">

    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
            <label for="">category name</label>
            <input type="text" name="cat" class="form-control" placeholder="enter category name">
            <div class="valid-feedback">correct</div>
            <div class="invalid-feedback">error</div>
            </div>
       
            <div class="form-group">
            <label for="">category description</label>
            <input type="text" name="desc" class="form-control" placeholder="enter category description">
            <div class="valid-feedback">correct</div>
            <div class="invalid-feedback">error</div>
            </div>
            <input type="submit" value="add" class="btn btn-primary">
            
         </div>
         </form>
    <div class="cat-content">test</div>
    </div>
    </div>
    <?php
}
/**end add */

/**start insert */
elseif($do=="insert")
{
    echo "<h1 class='text-center'>insert page</h1>";
    ?>
    <a href="category.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    if($_SERVER['REQUEST_METHOD']=="POST")
    {
        $name=$_POST['cat'];
        $desc=$_POST['desc'];
       $stmt=$db->prepare("INSERT INTO category(catname,description) 
       VALUES(:zname,:zdesc)
       ");
       $stmt->execute(array(
        ":zname" =>$name,
        ":zdesc"=>$desc
       ));

       $count=$stmt->rowcount();
       if($count>0)
       {
           echo "<div class='alert alert-success'>
            <h1>".$count." is added to category :)</h>
           </div>";
       }
       else
       {
        echo "<div class='alert alert-danger'>
        <h1>".$count." is not added to category (:</h>
       </div>";
       }
    }
}
/**end insert */


/**start edit */
elseif($do=="edit")
{
    echo "<h1 class='text-center'>edit page</h1>";
    ?>
    <a href="category.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    $catid=isset($_GET['catid'])&&is_numeric($_GET['catid'])?intval($_GET['catid']):0;
    $stmt=$db->prepare("SELECT * FROM category WHERE id=?");
    $stmt->execute(array($catid));
    $cat=$stmt->fetch();
    ?>
    <div class="container">
    <div class="row">
    <div class="col-md-6 col-sm-12">
    <form action="category.php?do=update" method="post" class="form was-validated" autocomplete="off">

    <input type="hidden" name="id" class="form-control" value="<?php echo $cat['id']; ?>">

    <div class="form-group">
    <label for="">category name</label>
    <input type="text" name="name" class="form-control" value="<?php echo $cat['catname']; ?>">
    </div>

    <div class="form-group">
    <label for="">category description</label>
    <input type="text" name="desc" class="form-control" value="<?php echo $cat['description']; ?>">
    </div>
    <input type="submit" value="update" class="btn btn-success">
    </form>
    </div><!--end first section-->
    <div class="col-md-6 col-sm-12">test</div>
    </div><!--end row-->
    </div><!--end container-->
    <?php
}
/**end edit */

/**start update */
elseif($do=="update")
{
    echo "<h1 class='text-center'>update page</h1>";
    ?>
    <a href="category.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    if($_SERVER['REQUEST_METHOD']=="POST")
    {
        $name=$_POST['name'];
        $desc=$_POST['desc'];
        $id=$_POST['id'];
        $stmt=$db->prepare("UPDATE category SET catname=?,description=? WHERE id=?");
        $stmt->execute(array($name,$desc,$id));
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
            <h1>".$count." is not updated </h1>
            </div>";
        }
    }
    else
    {
      echo "<div class='alert alert-danger'>
      <h1>you are not authorized to login this page</h1>
      </div>";  
    }
}
/**end update */

/**start delete */
elseif($do=="delete")
{
    echo "<h1 class='text-center'>delete page</h1>";
    ?>
    <a href="category.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    $catid=isset($_GET['catid'])&&is_numeric($_GET['catid'])?intval($_GET['catid']):0;
    $stmt=$db->prepare("SELECT * FROM category WHERE id=?");
    $stmt->execute(array($catid));
    $count=$stmt->rowcount();
    if($count>0)
    {
        $stmt=$db->prepare("DELETE FROM category WHERE id=:zid");
        $stmt->execute(array(
            ":zid"=>$catid
        ));
        $delete=$stmt->rowcount();
        if($delete>0)
        {
            echo "<div class='alert alert-success'>
            <h1>".$delete." is delete successfull :)</h1>
            </div>";
        }
        else
        {
            echo "<div class='alert alert-danger'>
            <h1>".$delete. " is not deleted (:</h1>
            </div>";
        }
    }
    else
    {
        echo "<div class='alert alert-danger'>
      <h1>there field not found (:</h1>
      </div>";
    }
}
/**end update */
include $template."footer.php";
?>
