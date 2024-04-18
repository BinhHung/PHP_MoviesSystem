<?php 
include('../connect.php');

if(!isset($_SESSION['uid'])){
  echo "<script> window.location.href='../login.php';  </script>";
}

$userid = $_SESSION['uid'];
$sql = "SELECT roteype FROM `users` WHERE userid = '$userid'";
$result = mysqli_query($con, $sql);


if(mysqli_num_rows($result) > 0){
    $userData = mysqli_fetch_assoc($result);
    $roteype = $userData['roteype'];
    
    if($roteype == 2){
        echo "<script> window.location.href='../index.php'; </script>";
    } 
} else {
    echo "<script> alert('User not found!'); </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
</head>
<body>


<?php include('header.php')  ?>

<div class="container" style="margin-top:100px!important;">
  <div class="col-md-12 head">
    <div class="float-end">
      <a href="export.php" class="btn btn-success"> <i class="dwn"></i>Export</a>
    </div>
  </div>
  <form action="viewallbooking.php" method="post">
    <div class="row">
      <div class="col-lg-3">
        <input type="date" name="start" class="form-control">
      </div>
      <div class="col-lg-3">
        <input type="date" name="end" class="form-control">
      </div>
      <div class="col-lg-3">
         <select name="status" id="" class="form-control">
          <option value="">Select Status</option>
          <option value="0">Pending</option>
          <option value="1">Approve</option>
         </select>
      </div>
      <div class="col-lg-3">
        <input type="submit" name="btnsearch" value="Search" class="btn btn-success">
        <input type="submit" name="btncancel" value="Cancel" class="btn btn-success">
      </div>
    </div>
  </form>
</div>

<div class="container">
   
<div class="row mt-5">


  <div class="col-lg-12">
  
     <table class="table">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Movie</th>
        <th>Date</th>
        <th>Days/Time</th>
        <th>Person</th>
        <th>Total</th>
        <th>Location</th>
        <th>User</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
      
      <?php

      if(isset($_POST['btnsearch'])){

        $start  = $_POST['start'];
        $end    = $_POST['end'];
        $status = $_POST['status'];

        $total_sale = 0;

        $sql = "SELECT booking.bookingid, booking.person, booking.bookingdate, booking.person, theater.theater_name, theater.timing, theater.days, booking.totalprice, theater.location, movies.title,  categories.catname, users.name as 'username',
        booking.status
        from booking
        inner join theater on theater.theaterid = booking.theaterid
        inner join users on users.userid = booking.userid
        inner join movies on movies.movieid = theater.movieid
        inner join categories on categories.catid = movies.catid
        where booking.bookingdate between '$start' AND '$end' and booking.status = '$status'";
        $res  = mysqli_query($con, $sql);
        if(mysqli_num_rows($res) > 0){
          while($data = mysqli_fetch_array($res)){

            $total_sale = $total_sale + $data['totalprice'];
  
            ?>

          <tr>
          <td><?= $data['bookingid'] ?></td>
            <td><?= $data['theater_name'] ?></td>
            <td><?= $data['title'] ?></td>
            <td><?= $data['bookingdate'] ?></td>
            <td><?= $data['days'] ?> - <?= $data['timing'] ?></td>       
            <td><?= $data['person'] ?></td>
            <td><?= $data['totalprice'] ?>.000đ</td>
            <td><?= $data['location'] ?></td>
            <td><?= $data['username'] ?></td>
     
            <td>

              <?php

              if($data['status'] == 0){
                echo "<a href='#' class='btn btn-warning' > Pending </a>";
              }else{
                echo "<a href='#' class='btn btn-success' > Approved </a>";
              }

              ?>


            </td>
           
            <td>
              <?php

                if($data['status'] == 1){
                  echo "<button type='button' class='btn btn-light' disabled> Completed </button>";
                }else{
                  echo "<a href='viewallbooking.php?bookingid=".$data['bookingid']."' class='btn btn-primary'> Approve</a>";
                }
              ?>
              
          </td>
          </tr>

            <?php
          }
            echo "<tr> <td>Total Sale: <strong> ".$total_sale.".000đ</strong></td> </tr>";
        }

      }else{


      $sql = "SELECT booking.bookingid, booking.bookingdate, booking.person, theater.theater_name, theater.timing, theater.days, booking.totalprice, theater.location, movies.title,  categories.catname, users.name as 'username',
      booking.status
      from booking
      inner join theater on theater.theaterid = booking.theaterid
      inner join users on users.userid = booking.userid
      inner join movies on movies.movieid = theater.movieid
      inner join categories on categories.catid = movies.catid 
      ";
      $res  = mysqli_query($con, $sql);
      if(mysqli_num_rows($res) > 0){
        while($data = mysqli_fetch_array($res)){

          ?>

        
          <tr>
          <td><?= $data['bookingid'] ?></td>
            <td><?= $data['theater_name'] ?></td>
            <td><?= $data['title'] ?></td>
            <td><?= $data['bookingdate'] ?></td>
            <td><?= $data['days'] ?> - <?= $data['timing'] ?></td>       
            <td><?= $data['person'] ?></td>
            <td><?= $data['totalprice'] ?>.000đ</td>
            <td><?= $data['location'] ?></td>
            <td><?= $data['username'] ?></td>
     
            <td>

              <?php

              if($data['status'] == 0){
                echo "<a href='#' class='btn btn-warning' > Pending </a>";
              }else{
                echo "<a href='#' class='btn btn-success' > Approved </a>";
              }

              ?>

            </td>
           
            <td>
            <?php

              if($data['status'] == 1){
                echo "<button type='button' class='btn btn-light' disabled> Completed </button>";
              }else{
                echo "<a href='viewallbooking.php?bookingid=".$data['bookingid']."' class='btn btn-primary'> Approve</a>";
              }
              ?>
          </td>
          </tr>


       <?php
        }
      }else{
        echo 'no booking found';
      }

    }
   

      ?>


     </table>

  </div>
</div>
  

</div>


<?php include('footer.php')  ?>

</body>
</html>

<?php

if(isset($_GET['bookingid'])){

  $bookingid  = $_GET['bookingid'];
  $sql = "UPDATE `booking` SET `status`= 1 WHERE bookingid = '$bookingid'";

  if(mysqli_query($con,$sql)){
    echo "<script> alert('booking approved successfully!!') </script>";
    echo "<script> window.location.href='viewallbooking.php';  </script>";
  }else{
    echo "<script> alert('not approved successfully!!') </script>";
  }
}
?>

