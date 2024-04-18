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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theater</title>
</head>
<body>

    <?php include('header.php')?>
    <?php

        if(isset($_GET['editid'])){
            $editid = $_GET['editid'];
            $sql = "SELECT * FROM `categories` WHERE catid= '$editid'";
            $res = mysqli_query($con, $sql);
    
            $editdata = mysqli_fetch_array($res);
        

    ?>
    <div class="container">
        <div class="row">

            <div class="col-lg-6">
                <form action="theater.php" method="post">
                    
                    

                </form>
    
        <?php 
        }else{
            ?>
            <div class="container">
        <div class="row">

            <div class="col-lg-6">
            <form action="theater.php" method="post" enctype="multipart/form-data">
            <div class="form-group mb-4">
                        <select name="movieid" class="form-control">
                            <option value="">Select Movies</option>
                        <?php
                            $sql = "SELECT * FROM `movies`";
                            $res = mysqli_query($con, $sql);
                            if(mysqli_num_rows($res) > 0){
                                while($data = mysqli_fetch_array($res)){
                                ?> <option value="<?=$data['movieid']?>"> <?=$data['title']?></option> <?php

                                }
                            } else{
                                ?> <option value="">No Movies found</option> <?php
                            }
                        ?>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                    <input type="time" class="form-control" name="timing" value="" >
                    </div>

                    <div class="form-group mb-4">
                    <input type="text" class="form-control" name="days" value="" placeholder="enter days">
                    </div>

                    <div class="form-group mb-4">
                    <input type="date" class="form-control" name="date" value="" >
                    </div>

                    <div class="form-group mb-4">
                    <input type="number" class="form-control" name="price" value="" placeholder="enter price">
                    </div>

                    <div class="form-group mb-4">
                    <input type="text" class="form-control" name="theater_name" value="" placeholder="enter theater name">
                    </div>
                    
                    <div class="form-group mb-4">
                    <input type="text" class="form-control" name="location" value="" placeholder="enter location">
                    </div>
                    
                    

                   
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Add Theater" name="add">
                    </div>

            </form>
            <?php
        }
            
        ?>    
            </div>
            <div class="col-lg-6">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Days/Time</th>
                        <th>Ticket</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>

                    <?php
                        $sql = "SELECT theater.*, movies.title, categories.catname
                        FROM theater
                        inner join movies on movies.movieid = theater.movieid
                        inner join categories on categories.catid = movies.catid";
                        $res = mysqli_query($con, $sql);
                        if(mysqli_num_rows($res) > 0){
                            while($data = mysqli_fetch_array($res)){

                                ?>
                                <tr>
                                        <td><?= $data['theaterid']?></td>
                                        <td><?= $data['title']?></td>
                                        <td><?= $data['catname']?></td>
                                        <td><?= $data['date']?></td>
                                        <td><?= $data['days']?> - <?= $data['timing']?></td>
                                        <td><?= $data['price']?></td>
                                        <td><?= $data['location']?></td>


                                        <td>
                                            <a href="theater.php?editid=<?=$data['movieid']?>" class="btn btn-primary">Edit</a>
                                            <a href="theater.php?deleteid=<?=$data['movieid']?>" class="btn btn-danger">Delete</a>

                                        </td>
                                </tr>
                                <?php
                            }
                        }else{
                            echo 'no category found';
                        }
                    ?>
                </table>
            </div>
    </div>


    <?php include('footer.php')?>
    
</body>
</html>


<?php 
    if(isset($_POST['add'])){
        $movieid = $_POST['movieid'];
        $days = $_POST['days'];
        $timming = $_POST['timing'];
        $price = $_POST['price'];
        $date = $_POST['date'];
        $location = $_POST['location'];
        $theater_name = $_POST['theater_name'];


        $sql = "INSERT INTO `theater`(`timing`, `days`, `date`, `price`, `location`, `movieid`, `theater_name`) VALUES ('$timming',' $days','$date','$price','$location', '$movieid', '$theater_name')";

        if(mysqli_query($con, $sql)){
            echo "<script> alert('theater added') </script>";
            echo "<script> window.location.href='theater.php'</script>";
        }else{
            echo "<script> alert('theater not added') </script>";

        }
    }
    if(isset($_POST['update'])){
        
        $sql = "";

        if(mysqli_query($con, $sql)){
            echo "<script> alert('category updated') </script>";
            echo "<script> window.location.href='movies.php'</script>";
        }else{
            echo "<script> alert('category not update') </script>";

        }
    }
    if(isset($_GET['deleteid'])){
        $deleteid = $_GET['deleteid'];
        $sql = "DELETE FROM `categories` WHERE catid = '$deleteid'";
        if(mysqli_query($con, $sql)){
            echo "<script> alert('categories deleted')</script>";
            echo "<script> window.location.href='movies.php'</script>";
        }
        else
        {
            echo "<script> alert('categories not deleted')</script>";

        }
    }
?>