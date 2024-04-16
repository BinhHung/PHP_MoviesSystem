<?php include('connect.php')?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
<?php
require_once 'vendor/autoload.php';

// init configuration
$clientID = '';
$clientSecret = '';
$redirectUri = '';

// create Client Request to access Google API
$client = new Google\Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token['access_token']);

  $google_oauth = new Google\Service\Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $email =  $google_account_info->email;
  $name =  $google_account_info->name;
  $id = $google_account_info->id;

  $user_id_suffix = substr($id, -4);

  $check_query = "SELECT * FROM users WHERE userid = '$user_id_suffix'";
  $check_result = mysqli_query($con, $check_query);
  
  if (mysqli_num_rows($check_result) == 0) {
    $sql = "INSERT INTO `users`(`email`, `name`, `userid`, `roteype`) VALUES ('$email','$name','$user_id_suffix','2')";
    $result = mysqli_query($con, $sql);
  
    if ($result) {
        $_SESSION['uid'] = $user_id_suffix;
        $_SESSION['type'] = 2; 
      
        echo "<script> alert('User information saved successfully!') </script>";
        echo "<script>window.location.href='index.php';</script>";
    } else {
        echo "<script> alert('Failed to save user information!') </script>";
    }
} else {
    $user_data = mysqli_fetch_assoc($check_result);
    $_SESSION['uid'] = $user_data['userid'];
    $_SESSION['type'] = $user_data['roteype'];
  
    if($user_data['roteype'] == 1){
      echo "<script> alert('admin login successfully!!') </script>";
      echo "<script> window.location.href='admin/dashboard.php'; </script>";
    }
    if($user_data['roteype'] == 2){
      echo "<script> alert('user login successfully!!') </script>";
      echo "<script> window.location.href='index.php'; </script>";
    }
}
  

  
  
} else {
  
?>
<section id="team" class="team section-bg">
      <div class="container aos-init aos-animate" data-aos="fade-up">

        <div class="section-title">
          <h2>Login Admin / User</h2>

        </div>

        <div class="row">

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
          <form action="login.php" method="post" role="form" class="php-email-form">
              <div class="row">
                
                <div class="col form-group mb-3">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required="">
                </div>
              </div>
              <div class="form-group mb-3">
                <input type="password" class="form-control" name="password" id="password" placeholder="your password" required="">
              </div>


              <div class="text-center"><button type="submit" name="login" class="btn btn-primary">Login</button></div>
              <a href="<?php echo $client->createAuthUrl() ?>">
                <img src="https://developers.google.com/identity/images/btn_google_signin_light_normal_web.png" alt="Login with Google">
              </a>

            </form>
          </div>


        </div>

      </div>
    </section>
    <?php } ?>
</body>
</html>
<?php
    if(isset($_POST['login'])){
      $email      = $_POST['email'];
      $password   = $_POST['password'];


      $sql = "SELECT * FROM `users` WHERE email = '$email' and password = '$password'";

      $rs = mysqli_query($con, $sql);

      if(mysqli_num_rows($rs) > 0){
        $data = mysqli_fetch_array($rs);

        $role = $data['roteype'];

        $_SESSION['uid'] = $data['userid'];
        $_SESSION['type'] = $role;

        if($role == 1){
          echo "<script> alert('admin login successfully!!') </script>";
          echo "<script> window.location.href='admin/dashboard.php'; </script>";
        }
        if($role == 2){
          echo "<script> alert('user login successfully!!') </script>";
          echo "<script> window.location.href='index.php'; </script>";
        }
      }else{
        echo "<script> alert('Invalid Email & Password!!') </script>";
      }
    }
?>


