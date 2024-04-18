<?php 
include('connect.php');

if(!isset($_SESSION['uid'])){
  echo "<script> window.location.href='login.php';  </script>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Booking</title>
    <!-- Favicons -->
<link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>
<body>
    
<!-- <?php


    $theaterid = $_GET['id'];

?> -->

<section id="team" class="team section-bg">
      <div class="container aos-init aos-animate" data-aos="fade-up">
        <?php

            $sql = "SELECT theater.*, movies.*
            FROM theater
            INNER JOIN movies ON movies.movieid = theater.movieid
            WHERE theater.theaterid = '$theaterid'";
            $res = mysqli_query($con, $sql);

            ?>

            <section id="team" class="team section-bg">
            <div class="container aos-init aos-animate" data-aos="fade-up">

            <div class="section-title">
            <h2>Ticket Booking for Theater</h2>
            </div>
            <?php
            if(mysqli_num_rows($res) > 0){
            while($data = mysqli_fetch_array($res)){
            ?>
              <div class="row">
                <div class="col-lg-3 col-md-6 d-flex align-items-stretch aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                <form action="booking.php" method="post" >
                    <div class="row">

                      <input type="hidden" name="theaterid" value="<?=$theaterid?>">
                      <div class="member-info">
                        <h4>Phim: <?= $data['title'] ?> </span></h4>
                        <h6>Rạp: <?= $data['theater_name'] ?></h6>
                        <span>Ngày chiếu: <?= (new DateTime($data['date']))->format('d/m/Y') ?></span>
                        <input type="hidden" name="date" id="dateInput" value="<?= $data['date'] ?>">
                        <h6>Giá Vé: <?= $data['price'] ?>.000đ</h6>
                      </div>
                      <div class="col form-group mb-3">
                            <input type="number" class="form-control" name="person" id="person" placeholder="Enter no of People" required="">
                        </div>
                        <div class="col form-group mb-3">
                            <input type="text" class="form-control" name="totalPrice" id="totalPrice" readonly>
                        </div>

                        <script>
                            var personInput = document.getElementById('person');
                            var pricePerTicket = <?= $data['price'] ?>;

                            personInput.addEventListener('input', function() {
                                var numberOfPeople = parseInt(personInput.value);

                                var totalPrice = numberOfPeople * pricePerTicket;

                                var totalPriceInput = document.getElementById('totalPrice');
                                totalPriceInput.value = totalPrice.toLocaleString('en-US') + '.000đ';
                            });
                        </script>
                    </div>
                  
                    <div class="text-center"><button type="submit" class="btn btn-primary" name="ticketbook">Đặt Ngay</button></div>
                  </form>
                </div>


         </div>
            <?php
            }
            }
            ?>
          
        

      </div>
</section>

</body>
</html>

<?php

if(isset($_POST['ticketbook'])){

  $person     = $_POST['person'];
  $date       = $_POST['date'];
  $theaterid  = $_POST['theaterid'];

  $uid = $_SESSION['uid'];

  $totalprice = $_POST['totalPrice'];

  $sql = "INSERT INTO `booking`(`theaterid`, `bookingdate`, `person`, `userid`, `totalprice`) VALUES ('$theaterid','$date','$person','$uid','$totalprice')";

  if(mysqli_query($con, $sql)){
     echo "<script> alert('Ticket book successfully!!') </script>";
     echo "<script> window.location.href='index.php';  </script>";

  }else{
    echo "<script> alert('ticket not book')";
  }

}

?>

