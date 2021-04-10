<?php
session_start();
include "connection.php";
include "init.php";
include $function."backfunction.php";
include $template."frontheader.php";
include $template."frontnav.php";

$do=isset($_GET['do'])?$_GET['do']:"notfound";
?>
<div class="container">
    <div class="row">
        <?php
        if($do=='notfound')
        {
            echo "<div class='alert alert-danger'>error 404</div>";
        }
        elseif($do=='show')
        {
            $num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

            $catid=isset($_GET['catid'])&&is_numeric($_GET['catid'])?intval($_GET['catid']):0;
            $stmt=$db->prepare("SELECT items.*,category.* FROM items
            inner join category on items.catid=category.id
            WHERE catid=?
            ");
            $stmt->execute(array($catid));
            $allitems=$stmt->fetchAll();
            $count=$stmt->rowcount();
            if($count>0)
            {
            foreach($allitems as $item)
            {   

                /**start column */
                $itid=getitemid($item['name']);
                echo "<div class='col-md-4 col-sm-12'>
                    
                    <div class='card showitem' style='height:450px'>
                        <div class='card-body'>
                            <img class='img-thumbnails' width='100%' height='250px' src='upload/post photo/".$item['image']."'>
                            <h2>".$item['name']."</h2>
                            <p class='lead'>".$item['description']."</p>
                            <p class='lead' style='font-weight:bolder'>".$item['price']."</p>
                            
                            <button class='btn btn-success'>
                                <a href='showitems.php?do=details&itemid=".$itid."'>show details</a>
                            </button>
                            

                        </div>
                    </div>";
                    
                    

                echo "</div>";
                
                /**end column */
                
            }
        }
        else
        {
            echo "<div class='alert alert-danger no-data'>there's no items to show</div>";
        }
        }

        /**start show page */
        elseif($do=='details')
        {
            
            $itemid=isset($_GET['itemid'])&&is_numeric($_GET['itemid'])?intval($_GET['itemid']):0;
            $stmt=$db->prepare("SELECT items.*,category.catname FROM items 
            inner join category on items.catid=category.id
            WHERE items.id=?
            ");
            $stmt->execute(array($itemid));
            $all=$stmt->fetchAll();
            $username=getitemuser($itemid);
            

            foreach($all as $some)
            {   
               
                echo "<div class='col-md-4 col-sm-12 details-left'>
                <h2>".$some['name']."</h2>
                <img class='img-thumbnails' width='100%' height='250px' src='upload/post photo/".$some['image']."'>
                </div>";
                echo "<div class='col-md-4 col-sm-12 details-center'>
                <h3 >description : </h3>
                <p class='lead'>".$some['description']."</p>
                <h3>price : </h3>
                <p>".$some['price']."</p>
                <h3>made in : </h3>
                <p>".$some['countrymade']."</p>
                <h3>date : </h3>
                <p>".$some['date']."</p>
                <h3>rate : </h3>
                <p>".$some['rate']."</p>
                <h3>category : </h3>
                <p>".$some['catname']."</p>
                
                </div>";
                // Include and show the requested page
                    
                
                
            }
            ?>

            <div class="col-md-4 col-sm-12 details-right">
                    
                <?php
                if(isset($_SESSION['username']))
                {
                    ?>
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" class="form" autocomplete="off">
                <div class="form-group">
                        <label for="#comment">Comment:</label>
                        <textarea class="form-control" rows="5" id="comment" name='comment'></textarea>
                    </div>
                    <input type="submit" value="send" class="btn btn-primary">
                </form>
                <?php
                $uid=$_SESSION['username'];
                $stmt=$db->prepare("SELECT userid FROM users WHERE username=?");
                $stmt->execute(array($uid));
                $userid=$stmt->fetch();
                /* echo $userid['userid'];
                echo $itemid; */
                if($_SERVER['REQUEST_METHOD']=="POST")
                {
                    $comment=$_POST['comment'];
                    if(empty($comment))
                    {
                        echo "<div class='alert alert-danger'>this field can not be empty</div>";
                    }
                     $userid['userid'];
                     $itemid;
                     $stmt=$db->prepare("INSERT INTO comment(content,userid,itemid)
                    VALUES(:zc,:zu,:zi)
                    ");
                    $stmt->execute(array(
                        ":zc"=>$comment,
                        ":zu"=>$userid['userid'],
                        ":zi"=>$itemid
                    ));
                    $count=$stmt->rowcount();
                    if($count>0)
                    {
                        echo "<div class='alert alert-success'>your comment add successfully</div>";
                    }
                    else{
                        echo "<div class='alert alert-danger'>your comment can not be added try again</div>";
                    } 
                }
                }

                ?>

                <p class="all-comment">allcomment</p>
                <?php
               $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
               $perPage = isset($_GET['per-page']) && $_GET['per-page'] <= 50 ? (int)$_GET['per-page'] : 5;
               //positioning
               $start = ($page > 1) ? ($page * $perPage) - $perPage : 0;
               //query
               $articles = $db->prepare("SELECT SQL_CALC_FOUND_ROWS comment.*,users.username FROM comment
               inner join users on users.userid=comment.userid
                LIMIT {$start},{$perPage}");
               $articles->execute();
               $articles = $articles->fetchAll(PDO::FETCH_ASSOC);
               //pages
               $total = $db->query("SELECT FOUND_ROWS() as total")->fetch()['total'];
               $pages = ceil($total / $perPage);
                   $stmt=$db->prepare("SELECT comment.*,users.username FROM comment
                               inner join users on users.userid=comment.userid
                                WHERE itemid=? ORDER BY id desc limit 10");
                               $stmt->execute(array($itemid));
                               $comments=$stmt->fetchAll();
                               $count=$stmt->rowcount();
                   
                   if($count>0)
                               {
                   ?>
                 
                   <?php foreach ($articles as $comment): ?>
                               <div class="article">
                                   <p class="lead">
                                       <?php
                 echo "<div class='comment'>
                                       <p class='name'>".$comment['username']."</p>
                                       <p style='text-indent:15px' class='lead content'>".$comment['content']."</p>
                                       <p style='text-indent:15px' class='lead date'>".$comment['date']."</p>
                                       </div>";
               
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
                               }
                               else
                               {
                                   echo "<div class='alert alert-danger'>no comment related to this item to show</div>";
                               }

                ?>
                    
                    </div><!--end column-->
        <?php
                }
        /**end show page */
?>
        </div><!--end row-->
    </div><!--end container-->


     <!-- Start footer  -->
     <footer style="margin-top: 58px;">
     <h2>copy right &copy;<?php echo date('Y')?> mostafa samir </h2>
      </footer>
<!-- End footer -->
<?php
include $template."footer.php";
if(isset($_SESSION['username']))
{
    include $template."specialfooter.php"; 
}


?>