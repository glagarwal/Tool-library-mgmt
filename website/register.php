<?php
include('config.php');
?>
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

    <script type="text/javascript">
      $(document).ready(function () {
        $("#InputPassword", "#ConfirmPassword").keyup(checkPasswordMatch);
      });
      function checkPasswordMatch() {
        var password = $("#InputPassword").val();
        var confirmPassword = $("#ConfirmPassword").val();

        if (password != confirmPassword)
            $("#CheckPasswordMatch").html("Passwords do not match!");
        else
            $("#CheckPasswordMatch").html("Passwords match.");
      }
    </script>

  </head>

  <body class="bg-dark">

    <div class="container">

      <div class="card card-register mx-auto mt-5">
        <div class="card-header">
          Register an Account
        </div>
        <div class="card-body">
          <!-- <form> -->
          <form action ="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="form-group">
              <div class="form-row">
                <div class="col-md-6">
                  <label for="exampleInputName">First name</label>
                  <input type="text" class="form-control" name="f_name" id="exampleInputName" aria-describedby="nameHelp" placeholder="Enter first name">
                </div>
                <div class="col-md-6">
                  <label for="exampleInputLastName">Last name</label>
                  <input type="text" class="form-control" name="l_name" id="exampleInputLastName" aria-describedby="nameHelp" placeholder="Enter last name">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Email address</label>
              <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
            </div>
            <!-- start of edit by Gaurav on 09-28-17 -->
            <div class="form-group">
              <label for="UGA_ID">UGA ID</label>
              <input type="text" class="form-control" name="uga_id" id="UGA_ID" pattern="[1-9]{1}[0-9]{9}" placeholder="Enter 10 digit UGA ID">
            </div>
            <!-- end of edit by Gaurav -->
            <div class="form-group">
              <div class="form-row">
                <div class="col-md-6">
                  <label for="InputPassword">Password</label>
                  <input type="password" class="form-control" name="pass1" id="InputPassword" placeholder="Password">
                </div>
                <div class="col-md-6">
                  <label for="ConfirmPassword">Confirm password</label>
                  <input type="password" class="form-control" name="pass2" id="ConfirmPassword" placeholder="Confirm password">
                </div>
              </div>
            </div>
            <div class="form-group has-error">
              <span class="help-block" id="CheckPasswordMatch" color="Red"></span>
            </div>
            <button class="btn btn-primary btn-block" type="submit">Register</button>
          </form>
          <div class="text-center">
            <a class="d-block small mt-3" href="login.php">Login Page</a>
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

<!-- edit by Gaurav on 09-28-17 -->
<?php 
$uga_id = $email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST['f_name']) || empty($_POST["email"]) || empty($_POST["uga_id"]) || empty($_POST['pass1'])) {
    // $emailErr = "Email is required";
    echo "<script>alert('Please input all the required fields!');</script>";
  } else {
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      // $emailErr = "Invalid email format"; 
      echo "<script>alert('Invalid email format');</script>";
    }
    else{
      $new_password = $_POST['pass1'];
      $confirm_password = $_POST['pass2'];
      $f_name = test_input($_POST['f_name']);
      $l_name = test_input($_POST['l_name']);
      $uga_id = test_input($_POST['uga_id']);

      if($new_password != $confirm_password){
        echo "<script>alert('Passwords do not match!');</script>";
      }
      else{
        $pdo->beginTransaction();
        try{
          $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
          $insert_user_sql = 'Insert into Users VALUES(:email, :pass, :role)';
          $stmt = $pdo->prepare($insert_user_sql); 
          $stmt->execute(['email'=> $email, 'pass'=> $hashed_password, 'role'=> 'Student']);

          $insert_student_sql = 'Insert into Student(email_ID, f_name, l_name, UGA_ID) VALUES(:email, :fname, :lname, :ugaid)';
          $stmt = $pdo->prepare($insert_student_sql); 
          $stmt->execute(['email'=> $email, 'fname'=> $f_name, 'lname'=> $l_name, 'ugaid'=> $uga_id]);

          $pdo->commit();

          header("Location: login.php");
          die();
        } Catch(Exception $e){
            $pdo->rollback();
            throw $e;
        }
      }
    }
  }
}
?>