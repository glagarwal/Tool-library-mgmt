<?php
include('config.php');
if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] != "") {
    // if logged in send to dashboard page
    header("Location: index.php");
    die();
} ?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="">
    <meta name="author" content="">
    <title>SB Admin - Start Bootstrap Template</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin.css" rel="stylesheet">

  </head>

  <body class="bg-dark">

    <div class="container">

      <div class="card card-login mx-auto mt-5">
        <div class="card-header">
          Login
        </div>
        <div class="card-body">
          <!-- <form> -->
            <form action ="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="form-group">
              <label for="exampleInputEmail1">Email address</label>
              <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Password</label>
              <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>
            <!-- edit by Gaurav on 09/27/17
            <div class="form-group">
              <div class="form-check">
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input">
                  Remember Password
                </label>
              </div>
            </div> -->

            <div class="form-group has-error">
              <span class="help-block" id="chk_cred"></span>
            </div>

            <!-- <a class="btn btn-primary btn-block" href="index.html">Login</a> -->
            <button class="btn btn-primary btn-block" type="submit">Login</button>
            <!-- end of edit by Gaurav -->
          </form>
          <div class="text-center">
            <a class="d-block small mt-3" href="register.php">Register an Account</a>
            <a class="d-block small" href="forgot-password.php">Forgot Password?</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

  </body>

</html>

<!-- edit by Gaurav on 09-27-17 -->
<?php 
$role = $email = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["email"])) {
    // $emailErr = "Email is required";
    echo "<script>alert('Email is required');</script>";
  } else {
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      // $emailErr = "Invalid email format"; 
      echo "<script>alert('Invalid email format');</script>";
    }
    else{
      $passwordFromPost = $_POST['password'];
      $pass_fetch_sql = 'SELECT pass, role FROM Users WHERE email_ID = :email';
      $stmt = $pdo->prepare($pass_fetch_sql);
      $stmt->execute(['email'=> $email]);
      if($stmt->rowCount() < 1){
        echo '<script>document.getElementById("chk_cred").innerHTML="Your credentials don\'t match."</script>';
      }
      else {
        $hashedPasswordFromDB = $stmt->fetch(0)->pass;
        if (password_verify($passwordFromPost, $hashedPasswordFromDB)) {
            // echo 'Password is valid!';
            if ($_SESSION["errorType"] != "" && $_SESSION["errorMsg"] != "" ) {
              $ERROR_TYPE = $_SESSION["errorType"];
              $ERROR_MSG = $_SESSION["errorMsg"];
              $_SESSION["errorType"] = "";
              $_SESSION["errorMsg"] = "";
            }
            $_SESSION["errorType"] = "success";
            $_SESSION["errorMsg"] = "You have successfully logged in.";
            $_SESSION["user_id"] = $email;
            $_SESSION["role"] = $stmt->fetch()->role;

            header("Location: index.php");
            die();
        } else {
            echo '<script>document.getElementById("chk_cred").innerHTML="Your credentials don\'t match."</script>';
        }
      }
    }
  }
}
?>