
<?php

include "connection.php";
include "init.php";
include $template."header.php";

$do=isset($_GET['do'])?$_GET['do']:"manage";

if($do=="manage")
{
    //user input
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per-page']) && $_GET['per-page'] <= 50 ? (int)$_GET['per-page'] : 5;
//positioning
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;
//query
$articles = $db->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM users LIMIT {$start},{$perPage}");
$articles->execute();
$articles = $articles->fetchAll(PDO::FETCH_ASSOC);
//pages
$total = $db->query("SELECT FOUND_ROWS() as total")->fetch()['total'];
$pages = ceil($total / $perPage);


    echo "<h1 class='text-center'>manage page</h1>";
    $stmt=$db->prepare("SELECT * FROM users");
    $stmt->execute();
    $users=$stmt->fetchAll();
    ?>
    <div class="container-fluid">

    <table class="table table-border table-hover">
    <thead class="thead-dark">
    <th>user name</th>
    <th class='h'>email</th>
    <th class='h'>password</th>
    <th class='h'>image</th>
    <th class='h'>group id</th>
    <th>action</th>
    </thead>
    <?php foreach ($articles as $user): ?>
				<div class="article">
					<p class="lead">
						<?php
						     echo "<tr>
                             <td>".$user['username']."</td>                            
                             <td class='h' style='width:150px'>".$user['email']."</td>
                             <td class='h' style='width:100px'>".$user['password']."</td>
                             <td class='h' style='width:150px'>".$user['image']."</td>
                             <td class='h'>".$user['groupid']."</td>
                             <td>
                             <a  href='user.php?do=edit&userid=".$user['userid']."' class='btn btn-success'>
                             <i class='fas fa-edit'></i>
                             </a>
                            
                             <a style='margin-left:35px' href='user.php?do=delete&userid=".$user['userid']."' class='btn btn-danger'>
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
        /* foreach($users as $user)
        {
            echo "<tr>
        <td>".$user['username']."</td>
        <td>".$user['fullname']."</td>
        <td>".$user['email']."</td>
        <td>".$user['password']."</td>
        <td>".$user['image']."</td>
        <td>".$user['groupid']."</td>
        <td>
        <a href='user.php?do=edit&userid=".$user['userid']."' class='btn btn-success'>edit</a>
        </td>

        <td>
        <a href='user.php?do=delete&userid=".$user['userid']."' class='btn btn-danger'>
        <i class='fas fa-times'></i>
        </a>
        </td>
        </tr>";
        } */

    ?>
    </table>
    <a href="user.php?do=add">
                    <i class="fas fa-plus-circle fa-2x"></i>
                    </a>   
</div>
    
    <?php
}

/**start add page */
elseif($do=="add")
{
   
    echo "<h1 class='text-center u-h1'>add page</h1>";
    ?>
    <div class="container">
    <a href="user.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    
    <div class="row">
    <div class="col-md-6 col-sm-12">
    <form action="user.php?do=insert" method="post" class="form was-validated" autocomplete="off">
    <div class="form-group"><!--start username-->
        <label for="">user name</label>
        <input type="text" name="name" class="form-control" required placeholder="enter user name">
        <div class="valid-feedback">correct</div>
        <div class="invalid-feedback">Please fill out this field.</div>
    </div>

    

    <div class="form-group"><!--start email-->
        <label for="">email</label>
        <input type="email" name="email" class="form-control" required placeholder="enter email">
        <div class="valid-feedback">correct</div>
        <div class="invalid-feedback">error</div>
    </div>

    <div class="form-group"><!--start password-->
        <label for="">password</label>
        <input type="password" name="pass" class="form-control" required placeholder="enter password">
        <div class="valid-feedback">correct</div>
        <div class="invalid-feedback">Please fill out this field.</div>
    </div>

    <div class="form-group"><!--start image-->
        <label for="">image</label>
        <input type="file" name="image" class="form-control" required>
        <div class="valid-feedback">correct</div>
        <div class="invalid-feed">Please fill out this field.</div>
    </div>

    <div class="form-group"><!--start group id-->
        <label for="">groupid</label>
        <input type="text" name="group" class="form-control" required placeholder="enter group id">
        <div class="valid-feedback">correct</div>
        <div class="invalid-feedback">Please fill out this field.</div>
    </div>
    <input type="submit"  value="add new user !" class="btn btn-primary">
    </div>

    <div class="col-md-6 col-sm-12">test</div>
    </div><!--end row-->
    </div><!--end container-->
    </form>
    <?php

}
/**end add page */

/**start insert */
elseif($do=="insert")
{
    ?>
    <a href="user.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    echo "<h1 class='text-center'>insert page</h>";
    if($_SERVER['REQUEST_METHOD']=="POST")
    {
        $formerror=[];
        $name=$_POST['name'];
        $email=$_POST['email'];
        $pass=$_POST['pass'];
        $image=$_POST['image'];
        $group=$_POST['group'];
        $hashpass=sha1($pass);
        $val_email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $val_name=filter_var ( $name, FILTER_SANITIZE_STRING);
        $stmt=$db->prepare("INSERT INTO users (username,password,email,image,groupid)
        VALUES(:zu,:zp,:ze,:zi,:zg)
        ");
        $stmt->execute(array(
            ":zu" =>$val_name,
            ":zp"=>$hashpass,
            ":ze"=>$val_email,
            ":zi"=>$image,
            ":zg"=>$group
        ));
        $rows=$stmt->rowcount();
        if($rows>0)
        {
            echo "<div class='alert alert-success'>
            <h2>".$rows." is added to users :)</h2>
            </div>";
            echo "<a href='user.php?do=add' class='btn btn-info'>back</a>";
        }
        else{
            echo "<div class='alert alert-danger'>
            <h2>".$rows." can not be add to users :)</h2>
            </div>";
            echo "<a href='user.php?do=add' class='btn btn-info'>back</a>";
        }
    }
}
/**end insert page */

/**start edit */
elseif($do=="edit")
{
    ?>
    <a href="user.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    echo "<h1 class='text-center'>edit page</h1>";
    $userid=isset($_GET['userid'])&&is_numeric($_GET['userid'])?intval($_GET['userid']):0;
    $stmt=$db->prepare("SELECT * FROM users WHERE userid=?");
    $stmt->execute(array($userid));
    $data=$stmt->fetch();
    ?>
   <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <form action="user.php?do=update" method="post" autocomplete="off" class="form was-validated">
                <input type="hidden" name="id" class="form-control" value="<?php echo $data['userid']; ?>">
                    <div class="form-group"><!--start username-->
                        <label for="">username</label>
                        <input type="text" name="user" class="form-control" value="<?php echo $data['username']; ?>">
                        <div class="valid-feedback">correct</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                 

                    <div class="form-group"><!--start email-->
                        <label for="">email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $data['email']; ?>">
                        <div class="valid-feedback">correct</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <div class="form-group"><!--start password-->
                        <label for="">password</label>
                        <input type="password" name="pass" class="form-control" value="<?php echo $data['password']; ?>">
                        <div class="valid-feedback">correct</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <div class="form-group"><!--start image-->
                        <label for="">image</label>
                        <input type="file" name="image" class="form-control" value="<?php echo $data['image']; ?>">
                        <div class="valid-feedback">correct</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <div class="form-group"><!--start groupid-->
                        <label for="">group id</label>
                        <input type="text" name="group" class="form-control" value="<?php echo $data['groupid']; ?>">
                        <div class="valid-feedback">correct</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <input type="submit" class="btn btn-success" value="update record">
                </form>
            </div>

            <div class="col-md-6 col-sm-12">test</div>
        </div><!--end row-->
   </div> <!--end container
   <?php
}
/**end edit */

/**start update */
elseif($do=="update")
{
    ?>
    <a href="user.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    echo "<h1 class='text-center'>update page</h>";
    if($_SERVER['REQUEST_METHOD']=="POST")
    {
        $id=$_POST['id'];
        $user=$_POST['user'];
       
        $pass=$_POST['pass'];
        $email=$_POST['email'];
        $image=$_POST['image'];
        $group=$_POST['group'];

        $stmt=$db->prepare("UPDATE users SET username=?,password=?,email=?,image=?,groupid=? WHERE userid=?");
        $stmt->execute(array($user,$pass,$email,$image,$group,$id));
        $rows=$stmt->rowcount();
        if($rows>0)
        {
            echo "<div class='alert alert-success'>
            <h2>".$rows." is updated :)</h2>
            </div>";
            echo "<a href='user.php?do=edit' class='btn btn-info'>back</a>";
        }
        else{
            echo "<div class='alert alert-danger'>
            <h2>".$rows." can not be updated :)</h2>
            </div>";
            echo "<a href='user.php?do=edit' class='btn btn-info'>back</a>";
        }
    }
}
/**end update */

/**start delete */
elseif($do=="delete")
{
    ?>
    <a href="user.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    echo "<h1 class='text-center'>delete page</h>";
    $userid=isset($_GET['userid'])&&is_numeric($_GET['userid'])?intval($_GET['userid']):0;
    $stmt=$db->prepare("SELECT * FROM users WHERE userid=?");
    $stmt->execute(array($userid));
    $rows=$stmt->rowcount();
    if($rows>0)
    {
        $stmt=$db->prepare("DELETE FROM users WHERE userid=:zid");
        $stmt->execute(array(
            ":zid"=>$userid
        ));
        $count=$stmt->rowcount();
        if($count>0)
        {
            echo "<div class='alert alert-success'>
            <h2>".$rows." was deleted :)</h2>
            </div>";
            echo "<a href='user.php?do=delete' class='btn btn-info'>back</a>";
        }
        else{
            echo "<div class='alert alert-danger'>
            <h2>".$rows." can not be deleted :)</h2>
            </div>";
            echo "<a href='user.php?do=delete' class='btn btn-info'>back</a>";
        }
    }
    
    
    else
    {
        echo "<div class='alert alert-danger'>
            <h2>".$rows." no record found !!!</h2>
            </div>";
    }
}

/*end delete */
elseif($do=="approved")
    {
        $stmt=$db->prepare("UPDATE USERS set status=1");
        $stmt->execute();
        $count=$stmt->rowcount();
        if($count>0)
        {
            echo "<div class='alert alert-success'>approved</div>";
            echo "<a href='admin.php' class='btn btn-info'>back</a>";
        }
    }
include $template."footer.php";

?>
    