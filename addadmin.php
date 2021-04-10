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
$articles = $db->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM users WHERE groupid=1 LIMIT {$start},{$perPage}");
$articles->execute();
$articles = $articles->fetchAll(PDO::FETCH_ASSOC);
//pages
$total = $db->query("SELECT FOUND_ROWS() as total")->fetch()['total'];
$pages = ceil($total / $perPage);


    echo "<h1 class='text-center'>manage page</h1>";
    $stmt=$db->prepare("SELECT * FROM users WHERE groupid=1");
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
                             <td class='h'>".$user['email']."</td>
                             <td class='h'>".$user['password']."</td>
                             <td class='h'>".$user['image']."</td>
                             <td class='h'>".$user['groupid']."</td>
                             <td>
                             <a href='addadmin.php?do=edit&adminid=".$user['userid']."' class='btn btn-success'>
                             <i class='fas fa-edit'></i>
                             </a>
                             </td>
                     
                             <td>
                             <a href='addadmin.php?do=delete&adminid=".$user['userid']."' class='btn btn-danger'>
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
    
    </table>
    <a href="addadmin.php?do=add">
                    <i class="fas fa-plus-circle fa-2x"></i>
                    </a>   
</div>
    
    <?php
}

/**start add admin */
elseif($do=="add")
{
    ?>
     <a href="addadmin.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
        <h1 class="text-center">add new admin</h1>
        <form action="addadmin.php?do=insert" enctype="multipart/form-data" method="post" class="form was-validated" autocomplete="off">
            <div class="form-group">
                <label for="">admin name</label>
                    <input type="text" name="user" required class="form-control" placeholder="enter admin name">
                    <div class="valid-feedback">correct</div>
                    <div class="invalid-feedback">this field is required</div>
            </div>

            <div class="form-group">
                <label for="">admin email</label>
                    <input type="email" name="email" required class="form-control" placeholder="enter admin email">
                    <div class="valid-feedback">correct</div>
                    <div class="invalid-feedback">this field is required</div>
            </div>

            <div class="form-group">
                <label for="">admin password</label>
                    <input type="password" name="pass" required class="form-control" placeholder="enter admin password">
                    <div class="valid-feedback">correct</div>
                    <div class="invalid-feedback">this field is required</div>
            </div>

            <div class="form-group">
                <label for="">admin avatar</label>
                    <input type="file" name="image" required class="form-control" placeholder="enter admin avatar">
                    <div class="valid-feedback">correct</div>
                    <div class="invalid-feedback">this field is required</div>
            </div>
            <input type="submit" value="add new admin" class="btn btn-primary">
        </form>
    <?php
}
/**end add admin */

/**start insert admin */
elseif($do=="insert")
{
    ?>
 <a href="addadmin.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
    <?php
    if($_SERVER['REQUEST_METHOD']=="POST")
    {
        $user=$_POST['user'];
        $email=$_POST['email'];
        $pass=$_POST['pass'];
        $haspass=sha1($pass);
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
        if(strlen($user)<=4)
        {
            $formerror[]="the user name must be greater than 4 character";
        }
        elseif(empty($user))
        {
            $formerror[]="the user name can not be empty";
        }
        elseif(strlen($pass)<=8)
        {
            $formerror[]="the password must be greater than 8 character";
        }
        foreach($formerror as $error)
        {
            echo "<div class='alert alert-danger'>".$error."</div>";
        }

        if(empty($formerror))
        {
            $stmt=$db->prepare("INSERT INTO users (username,email,password,image,groupid)
            VALUES(:zn,:ze,:zp,:zi,1)
            ");
            $stmt->execute(array(
                ":zn"=>$user,
                ":ze"=>$email,
                ":zp"=>$haspass,
                ":zi"=>$avatar
            ));
            $count=$stmt->rowcount();
            if($count>0)
            {
                echo "<div class='alert alert-success'>one admin is added</div>";
            }
            else
            {
                echo "<div class='alert alert-danger'>error 404 team try agin please !!</div>";
            }
        }
    }
}
/**end insert admin */

/**start edit admin */
elseif($do=="edit")
{

    $adminid=isset($_GET['adminid'])&&is_numeric($_GET['adminid'])?$_GET['adminid']:0;
    $stmt=$db->prepare("SELECT * FROM users WHERE userid=?");
    $stmt->execute(array($adminid));
    $admin=$stmt->fetch();
    ?>
     <a href="addadmin.php">
    <i class="fas fa-angle-double-left fa-2x"></i>
    </a>
        <h1 class="text-center">add new admin</h1>
        <form action="addadmin.php?do=update" enctype="multipart/form-data" method="post" class="form was-validated" autocomplete="off">
            <div class="form-group">
            <input type="hidden" name="id"  class="form-control" value="<?php echo $admin['userid'] ?>" placeholder="enter admin name">

                <label for="">admin name</label>
                    <input type="text" name="user" required class="form-control" value="<?php echo $admin['username'] ?>" placeholder="enter admin name">
                    <div class="valid-feedback">correct</div>
                    <div class="invalid-feedback">this field is required</div>
            </div>

            <div class="form-group">
                <label for="">admin email</label>
                    <input type="email" name="email" required class="form-control" value="<?php echo $admin['email'] ?>" placeholder="enter admin email">
                    <div class="valid-feedback">correct</div>
                    <div class="invalid-feedback">this field is required</div>
            </div>
            <input type="hidden" name="oldpass"  value="<?php echo $admin['password'] ?>" class="form-control" placeholder="enter admin password">

            <div class="form-group">
                <label for="">admin password</label>
                    <input type="password" name="newpass" required value="<?php echo $admin['password'] ?>" class="form-control" placeholder="enter admin password">
                    <div class="valid-feedback">correct</div>
                    <div class="invalid-feedback">this field is required</div>
            </div>

            
            <input type="submit" value="update admin" class="btn btn-primary">
        </form>
    <?php
}

/**end edit admin */

/**start update admin */
elseif($do=="update")
{
    ?>
    <a href="addadmin.php">
       <i class="fas fa-angle-double-left fa-2x"></i>
       </a>
       <?php
    if($_SERVER['REQUEST_METHOD']=="POST")
    {
        $id=$_POST['id'];
        $user=$_POST['user'];
        $email=$_POST['email'];
        $newpass=$_POST['newpass'];
        $haspass=sha1($newpass);
        $oldpass=$_POST['oldpass'];
        $formerror=[];
        if(strlen($user)<=4)
        {
            $formerror[]= "username must be larger than 4 characters";
        }
        elseif(strlen($newpass)<=8)
        {
            $formerror[]= "password must be larger than 8 characters";
        }
        elseif(empty($user))
        {
            $formerror[]= "username can not be empty";
        }
        elseif($newpass==$oldpass)
        {
            $formerror[]= "password can not match the old password";
        }
        foreach($formerror as $error)
        {
            echo "<div class='alert alert-danger'>".$error."</div>";
        }
        if(empty($formerror))
        {
            $stmt=$db->prepare("UPDATE users SET username=?,email=?,password=? WHERE userid=?");
            $stmt->execute(array($user,$email,$haspass,$id));
            $count=$stmt->rowcount();
            if($count>0)
            {
                echo "<div class='alert alert-success'>one record updated</div>"; 
            }
            else
            {
                echo "<div class='alert alert-danger'>no record updated try again</div>";
            }
        }

    }
}

/**end update admin */


/*start delete admin/ */
elseif($do=="delete")
{
    ?>
    <a href="addadmin.php">
       <i class="fas fa-angle-double-left fa-2x"></i>
       </a>
       <?php
       $adminid=isset($_GET['adminid'])&&is_numeric($_GET['adminid'])?$_GET['adminid']:0;
       $stmt=$db->prepare("SELECT * FROM users WHERE userid=?");
       $stmt->execute(array($adminid));
       $count=$stmt->rowcount();
       if($count>0)
       {
            $stmt=$db->prepare("DELETE FROM users WHERE userid=:zid");
        $stmt->execute(array(":zid"=>$adminid));
        $count=$stmt->rowcount();
        if($count>0)
        {
            echo "<div class='alert alert-success'>one record is deleted</div>";
        }
        else{
            echo "<div class='alert alert-danger'>no record deleted try again</div>";
        }
       }
       else
       {
        echo "<div class='alert alert-danger'>no record found try again</div>";
       }
}
/*start delete admin/ */
else
{
    echo "page not found";
}
include $template ."footer.php";