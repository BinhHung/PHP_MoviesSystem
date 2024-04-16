<?php 
include('../connect.php');

if(!isset($_SESSION['uid'])){
  echo "<script> window.location.href='../login.php';  </script>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>
<body>


<?php include('header.php')  ?>


<div class="container">
   
<div class="row">
 
  <div class="col-lg-12">
  
     <table class="table">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role Type</th>      
        <th>Action</th>
      </tr>
      
      <?php
      $sql = "SELECT * FROM `users`";
      $res  = mysqli_query($con, $sql);
      if(mysqli_num_rows($res) > 0){
        while($data = mysqli_fetch_array($res)){

          ?>

          <tr>
            <td><?= $data['userid'] ?></td>
            <td><?= $data['name'] ?></td>
            <td><?= $data['email'] ?> </td>
            <td>

             <?php

               if($data['roteype'] == 1){
                echo "ADMIN";
               }else{
                echo "USER";
               }

             ?>

            </td>
     
          
           
            <td>
              <a href="viewallusers.php?editid=<?= $data['userid'] ?>" class="btn btn-primary"> Update </a>
              <a href="viewallusers.php?deleteid=<?= $data['userid'] ?>" class="btn btn-danger"> Delete </a>
            
          </td>
          </tr>


       <?php
        }
      }else{
        echo 'no user found';
      }
    

      ?>


     </table>

  </div>
</div>
<?php

if(isset($_GET['editid'])){

    $editid  = $_GET['editid'];
    $sql = "SELECT *
    FROM `users` 
    WHERE userid= '$editid'";
    $res  = mysqli_query($con, $sql);
    $editdata = mysqli_fetch_array($res);

    ?> 
    <form action="viewallusers.php" method="post" >
      <input type="hidden" name="userid" value="<?=$editdata['userid']?>">
      <select name="role" class="form-control">
          <option value="1" <?= ($editdata['roteype'] == 1) ? 'selected' : '' ?>>Admin</option>
          <option value="2" <?= ($editdata['roteype'] == 2) ? 'selected' : '' ?>>User</option>
      </select>
      <input type="submit" class="btn btn-primary" value="Update" name="update">
    </form>


    <?php

}

?>
  

</div>


<?php include('footer.php')  ?>

</body>
</html>

<?php

if(isset($_GET['deleteid'])){

  $deleteid = $_GET['deleteid'];
  $sql = "DELETE FROM `users` WHERE userid  = '$deleteid'";
 
  if(mysqli_query($con, $sql)){
    echo "<script> alert('Delete successfully')</script>";
      echo "<script> window.location.href='viewallusers.php' </script>";
  }else{
    echo "<script> alert('cateogry not deleted')</script>";
  }
 
}


if(isset($_POST['update'])){
  $userid = $_POST['userid'];
  
  $role = $_POST['role'];
  
  $sql = "UPDATE `users` SET `roteype`='$role' WHERE `userid`='$userid'";
  
  if(mysqli_query($con, $sql)){
      echo "<script> alert('Role updated successfully')</script>";
      echo "<script> window.location.href='viewallusers.php' </script>";
  } else {
      echo "<script> alert('Failed to update role')</script>";
  }
}




?>