<?php
require_once "connection.php";

$email = $password = $confirm_password = $role =  "";
$email_err = $password_err = $confirm_password_err = $role_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST"){
 

    // Check if username is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Username cannot be blank";
    }
    else{
        $sql = "SELECT id FROM user WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set the value of param username
            $param_email = trim($_POST['email']);

            // Try to execute this statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $email_err = "This email is already taken"; 
                }
                else{
                    $email = trim($_POST['email']);
                }
            }
            else{
                echo "Something went wrong";
            }
        }
    }

    mysqli_stmt_close($stmt);


     //check for role
     if(empty(trim($_POST["role"]))){
      $role_err = "Role cannot be blank";
  }
  else{
      $sql = "SELECT id FROM user WHERE role = ?";
      $stmt = mysqli_prepare($conn, $sql);
      if($stmt)
      {
          mysqli_stmt_bind_param($stmt, "s", $param_role);

          // Set the value of param username
          $param_role = trim($_POST['role']);

          // Try to execute this statement
          if(mysqli_stmt_execute($stmt)){
              mysqli_stmt_store_result($stmt);
              if(mysqli_stmt_num_rows($stmt) == 1)
              {
                  $role_err = "This role is already taken"; 
              }
              else{
                  $role = trim($_POST['role']);
              }
          }
          else{
              echo "Something went wrong";
          }
      }
  }

  mysqli_stmt_close($stmt);
     
// Check for password
if(empty(trim($_POST['password']))){
    $password_err = "Password cannot be blank";
}
elseif(strlen(trim($_POST['password'])) < 5){
    $password_err = "Password cannot be less than 5 characters";
}
else{
    $password = trim($_POST['password']);
}

// Check for confirm password field
if(trim($_POST['password']) !=  trim($_POST['confirm_password'])){
    $password_err = "Passwords should match";
}


// If there were no errors, go ahead and insert into the database
if(empty($email_err) && empty($role_err) && empty($password_err) && empty($confirm_password_err))
{
    $sql = "INSERT INTO user (email , role,  password) VALUES (?,?,  ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt)
    {
        mysqli_stmt_bind_param($stmt, "sss", $param_email, $param_role, $param_password);

        // Set these parameters
        $param_role = $role;
        $param_email = $email;
        $param_password = password_hash($password, PASSWORD_DEFAULT);

        // Try to execute the query
        if (mysqli_stmt_execute($stmt))
        {
            header("location: login.php");
        }
        else{
            echo "Something went wrong... cannot redirect!";
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
}

?>




<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>PHP login system!</title>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">Php Login System</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
  <ul class="navbar-nav">
      <!-- <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li> -->
      <li class="nav-item">
        <a class="nav-link" href="signup.php">Register</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="login.php">Login</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>
  
     
    </ul>
  </div>
</nav>

<div class="container mt-4">
<h3>Register Page</h3>
<hr>
<form action="" method="post">
    <div class="form-group ">
      <label for="Email">Email</label>
      <input type="text" class="form-control" name="email" id="email" placeholder="Email">
    </div>
    <div class="form-group ">
      <label for="Password">Password</label>
      <input type="password" class="form-control" name ="password" id="password" placeholder="Password">
    </div>
      <div class="form-group">
      <label for="ConfirmPassword">Confirm Password</label>
      <input type="password" class="form-control" name ="confirm_password" id="confirmPassword" placeholder="Confirm Password">
    </div>
     <div class="form-group ">
      <label for="Role">Role</label>
      <select id="role" class="form-control" name="role">
        <option selected>Admin</option>
        <option>Master</option>
        <option>User</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Sign in</button>
    <div class="form-group">
    <label for="login">Already have an Account ?<a href="login.php">Login</a></label>
  </div>
</form>
</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
