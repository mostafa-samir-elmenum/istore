
<?php
session_start();
include "connection.php";
include "init.php";
include $template."header.php";

$do=isset($_GET['do'])?$_GET['do']:"manage";

if($do=="manage")
{
            //comment input
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per-page']) && $_GET['per-page'] <= 50 ? (int)$_GET['per-page'] : 5;
//positioning
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;
//query
$articles = $db->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM comment LIMIT {$start},{$perPage}");
$articles->execute();
$articles = $articles->fetchAll(PDO::FETCH_ASSOC);
//pages
$total = $db->query("SELECT FOUND_ROWS() as total")->fetch()['total'];
$pages = ceil($total / $perPage);
    echo "<h1 class='text-center'>manage page</h1>";
    $stmt=$db->prepare("SELECT * FROM comment
    
    ");
    $stmt->execute();
    $comms=$stmt->fetchAll();
    ?>
    <div class="container-fluid">
    <table class="table table-border table-hover">
        <thead class="thead-dark">
            <th>content</th>
            <th class='h'>date</th>
            <th class='h'>user name</th>
            <th class='h'>item name</th>
            <th>action</th>
        </thead>
        <?php foreach ($articles as $comm): ?>
				<div class="article">
					<p class="lead">
						<?php
						      echo "<tr>
                              <td>".$comm['content']."</td>
                              <td class='h'>".$comm['date']."</td>
                              <td class='h'>".$comm['userid']."</td>
                              <td class='h'>".$comm['itemid']."</td>
                              <td>
                              <a href='comment.php?do=edit&commentid=".$comm['id']."' class='btn btn-success'>
                              <i class='fas fa-edit'></i>
                              </a>
                              
                              <a style='margin-left:35px' href='comment.php?do=delete&commentid=".$comm['id']."' class='btn btn-danger'>
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
          /*  foreach($comms as $comm)
           {
            echo "<tr>
            <td>".$comm['content']."</td>
            <td>".$comm['date']."</td>
            <td>".$comm['userid']."</td>
            <td>".$comm['itemid']."</td>
            <td>
            <a href='comment.php?do=edit&commentid=".$comm['id']."' class='btn btn-success'>edit</a>
            </td>
            <td>
                <a href='comment.php?do=delete&commentid=".$comm['id']."' class='btn btn-danger'>
                <i class='fas fa-times'></i>
                </a>
            </td>
            </tr>";
        
           } */
        ?>
    </table>
        </div>
    <?php
}



/**start edit */
elseif($do=="edit")
{
    ?>
    <a href="comment.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    echo "<h1 class='text-center'>edit page</h1>";
    $commentid=isset($_GET['commentid'])&&is_numeric($_GET['commentid'])?intval($_GET['commentid']):0;
    $stmt=$db->prepare("SELECT comment.*,users.username ,items.name
    FROM comment
    inner join users on comment.userid=users.userid
    inner join items on comment.itemid=items.id
     where comment.id=?");
    $stmt->execute(array($commentid));
    $com=$stmt->fetch();
   ?>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-12">
            <form action="comment.php?do=update" method="post" class="form was-validated" autocomplete="off">
            <input type="hidden" class="form-control" name="id" value="<?php echo $com['id'] ?>">
        <div class="form-group">
            <label for="">comment content</label>
            <input type="text" class="form-control" name="content" value="<?php echo $com['content'] ?>">
        </div>

        <div class="form-group">
            <label for="">user name</label>
            <input type="text" class="form-control" disabled value="<?php echo $com['username'] ?>">
        </div>

        <div class="form-group">
            <label for="">item name </label>
            <input type="text" class="form-control" disabled value="<?php echo $com['name'] ?>">
        </div>
        <input type="submit" class="btn btn-success" value="update">
        
    </form>
            </div>
            <div class="col-md-6 col-sm-12">test</div>
        </div><!--end row-->
    </div><!--end container-->

    <?php

}
/**end insert */

/**start update */
elseif($do=="update")
{
    ?>
    <a href="comment.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    echo "<h1 class='text-center'>update page</h1>";
    if($_SERVER['REQUEST_METHOD']=="POST")
    {
        $content=$_POST['content'];
        $id=$_POST['id'];
        $stmt=$db->prepare("UPDATE comment set content=? WHERE id=?");
        $stmt->execute(array(
        $content,$id
        ));
        $count=$stmt->rowcount();
        if($count>0)
        {
            echo "<div class='alert alert-info'>
            <h2>".$count." is updated :)<h2>
            </div>";
        }
        else
        {
            echo "<div class='alert alert-danger'>
            <h2>no record updated<h2>
        </div>";
        }
    }
    else
    {
        echo "<div class='alert alert-danger'>
            <h2>no data found<h2>
        </div>";
    }
}
/**end update */

/**start delete */
elseif($do=="delete")
{
    ?>
    <a href="comment.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    echo "<h1 class='text-center'>delete page</h1>";
    $commentid=isset($_GET['commentid'])&&is_numeric($_GET['commentid'])?intval($_GET['commentid']):0;
    $stmt=$db->prepare("SELECT * FROM comment WHERE id=?");
    $stmt->execute(array($commentid));
    $count=$stmt->rowcount();
    if($count>0)
    {
        $stmt=$db->prepare("DELETE FROM comment WHERE id=:zid");
        $stmt->execute(array(
            ":zid" =>$commentid
        ));
        $count=$stmt->rowcount();
        if($count>0)
        {
            echo "<div class='alert alert-success'>
            <h2>".$count." id deleted :)<h2>
        </div>";
        }
    }
    else{
        echo "<div class='alert alert-danger'>
        <h2>no data found<h2>
    </div>"; 
    }
}
/**end delete */
include $template."header.php";
?>
