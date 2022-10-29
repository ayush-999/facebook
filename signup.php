<?php

require 'connect/DB.php';
require 'core/load.php';

if (isset($_POST['first-name']) && !empty($_POST['first-name'])) {
  $upFirst = $_POST['first-name'];
  $upLast = $_POST['last-name'];
  // $upScreenName = $_POST['screenName'];
  $upEmailMobile = $_POST['email-mobile'];
  $upPassword = $_POST['up-password'];
  $birthDay = $_POST['birth-day'];
  $birthMonth = $_POST['birth-month'];
  $birthYear = $_POST['birth-year'];
  if (!empty($_POST['gen'])) {
    $upgen = $_POST['gen'];
  }
  $birth = '' . $birthYear . '-' . $birthMonth . '-' . $birthDay . '';

  if (empty($upFirst) or empty($upLast) or empty($upEmailMobile) or empty($upPassword) or empty($birth) or empty($upgen)) {
    $error = 'All felids are required';
  } else {
    $first_name = $loadFromUser->checkInput($upFirst);
    $last_name = $loadFromUser->checkInput($upLast);
    $email_mobile = $loadFromUser->checkInput($upEmailMobile);
    $password = $loadFromUser->checkInput($upPassword);
    // check User in Database
    // $screenName = $loadFromUser->checkInput($upScreenName);
    $screenName = '' . $first_name . '_' . $last_name . '';
    if (DB::query('SELECT screenName FROM users WHERE screenName = :screenName', array(':screenName' => $screenName))) {
      $screenRand = rand();
      $userLink = '' . $screenName . '' . $screenRand . '';
    } else {
      $userLink = $screenName;
    }

    if (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email_mobile)) {
      if (!preg_match("^[0-9]{10}^", $email_mobile)) {
        $error = 'Email or mobile number is not correct. Please try again.';
      }
    } else {
      if (!filter_var($email_mobile)) {
        $error = "Invalid Email Format";
      } else if (strlen($first_name) > 20) {
        $error = "Name must be between 2-20 character";
      } else if (strlen($password) < 5 && strlen($password) >= 60) {
        $error = "The password is either too short or too long.";
      } else {
        if ((filter_var($email_mobile, FILTER_VALIDATE_EMAIL)) && $loadFromUser->checkEmail($email_mobile) === true) {
          $error = "Email is already is use";
        } else {
          $loadFromUser->create('users', array('first_name' => $first_name, 'last_name' => $last_name, 'email' => $email_mobile, 'password' => password_hash($password, PASSWORD_BCRYPT), 'screenName' => $screenName, 'userLink' => $userLink, 'birthday' => $birth, 'gender' => $upgen));
        }
      }
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facebook</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
  <!-- fontawesome -->
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <header id="header">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <h1 class="home-title mb-2">
            Facebook
          </h1>
        </div>
        <div class="col-lg-6">
          <form class="row">
            <div class="col-lg-5 col-sm-12">
              <input type="text" class="form-control shadow-none rounded-0 border-0  mb-2" placeholder="Email or Phone">
            </div>
            <div class="col-lg-5 col-sm-12">
              <input type="password" class="form-control shadow-none rounded-0 border-0" placeholder="Password">
              <div class="forget-class mb-2" class="form-text">
                <a href="">
                  Forgotten password?
                </a>
              </div>
            </div>
            <div class="col-lg-2 col-sm-12">
              <input class="btn btn-success shadow-none rounded-0 home-loginBtn mb-3" type="submit" value="Login">
            </div>
          </form>
        </div>
      </div>
    </div>
  </header>
  <main id="main">
    <div class="container">
      <div class="row align-items-center pt-3 pb-3">
        <div class="col-lg-6">
          <div class="">
            <img src="assets/images/facebook-map.png" class="img-fluid mb-2 mt-2" alt="Facebook Map">
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card shadow-none rounded-0 border-0">
            <div class="card-body">
              <h1 class="home-card-title">Create an account</h1>
              <h4 class="home-card-sub-title">It's free and always will be</h4>
              <form class="row" action="signup.php" method="post" name="user-sign-up">
                <div class="col-lg-6 col-sm-12 mb-3">
                  <input type="text" class="form-control shadow-none rounded-0" name="first-name" id="first-name" placeholder="First Name">
                </div>
                <div class="col-lg-6 col-sm-12 mb-3">
                  <input type="text" class="form-control shadow-none rounded-0" name="last-name" id="last-name" placeholder="Last Name">
                </div>
                <!-- <div class="col-lg-12 col-sm-12 mb-3">
                  <input type="text" class="form-control shadow-none rounded-0" name="screenName" id="screenName" placeholder="Enter username">
                </div> -->
                <div class="col-lg-12 mb-3">
                  <input type="text" class="form-control shadow-none rounded-0" name="email-mobile" id="up-email" placeholder="Mobile number or email address">
                </div>
                <div class="col-lg-12 mb-3">
                  <input type="password" class="form-control shadow-none rounded-0" name="up-password" id="up-password" placeholder="Enter your password">
                </div>
                <div class="col-lg-6 mb-3">
                  <label class="birthday-label">Birthday</label>
                  <div class="row">
                    <div class="col-4">
                      <select class="form-select shadow-none rounded-0 custom-padding" name="birth-day" id="days"></select>
                    </div>
                    <div class="col-4">
                      <select class="form-select shadow-none rounded-0 custom-padding" name="birth-month" id="months"></select>
                    </div>
                    <div class="col-4">
                      <select class="form-select shadow-none rounded-0 custom-padding" name="birth-year" id="years"></select>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 mb-3">
                  <label class="gender-label">Gender</label>
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gen" id="male" value="male">
                        <label class="form-check-label" for="male">Male</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gen" id="female" value="female">
                        <label class="form-check-label" for="female">Female</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12 mb-3">
                  <small>
                    By clicking Sign Up. you agree to our terms, data policy and cookie policy. You may receive SMS notification from us and can opt out at any time.
                  </small>
                </div>
                <div class="col-lg-12">
                  <input class="btn btn-1 shadow-none rounded-0 border-0" type="submit" value="Sign Up">
                </div>
                <div class="col-lg-12">
                  <?php if (!empty($error)) {
                    echo '<div class="alert alert-danger alert-dismissible fade show rounded-0 mt-2 mb-2" role="alert">' .
                      $error
                      . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                  } ?>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <footer class="d-flex justify-content-center mt-3 mb-3">
    <a href="">
      @facebook
    </a>
  </footer>
  <!-- JQuery -->
  <script src="assets/js/jQuery-v3.6.1.min.js"></script>
  <!-- Bootstrap -->
  <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Custom JS -->
  <script src="assets/js/main.js"></script>


</body>

</html>