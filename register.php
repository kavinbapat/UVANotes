<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="https://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />
  
  <title>Register</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="styles.css">  
</head>
<body>
    <h1 class="welcome">Welcome to UVA Notes!</h1>
    <h2 class="form">Register an account</h2>
        <form action="register-process.php" method="POST" class="login-form">
            <label for="username">Username</label>
            <input type="text" name="username" id="name" class="login-input" required>
            <label for="username">Computing ID</label>
            <input type="text" name="computingId" id="computingId" class="login-input" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="login-input" required>
            <button type="submit" class="login-button">Submit</button>
        </form>
        <?php
            if (isset($_SESSION['errorMessage'])){
                echo "<div class='error'>" . $_SESSION['errorMessage'] . "</div>";
            }
        ?>
</body>