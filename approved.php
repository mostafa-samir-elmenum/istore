<?php
include "connection.php";
include "init.php";
include $template."header.php";


?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-border table-hover">
                <thead class="thead-dark">
                <th>number</th>
                    <th>user name</th>
                    <th class='h'>image</th>
                    <th class='h'>email</th>
                    <th class='h'>group id</th>
                    <th >approved</th>
                </thead>
                <?php
                          $stmt=$db->prepare("SELECT * FROM users WHERE status=0 order by userid DESC ");
                          $stmt->execute();
                          $users=$stmt->fetchAll();
                          foreach($users as $user)
                          {
                              echo "<tr>
                              <td>".$user['userid']."</td>
                              <td>".$user['username']."</td>
                              <td class='h'>".$user['image']."</td> 
                              <td class='h'>".$user['email']."</td>
                              <td class='h'>".$user['groupid']."</td>
                              <td>
                              <a href='user.php?do=approved' class='btn btn-success'><i class='fas fa-check'></i></a>
                              </td>
                              </tr>";
                          }
                          ?>
            </table>
        </div>
    </div>
</div>

<?php

include $template."footer.php";