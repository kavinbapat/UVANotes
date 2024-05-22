<?php
session_start();

require("connect-db.php");
require("database-requests.php");

if (!isset($_SESSION['name']) || !isset($_SESSION['computingId'])){
    header("Location: index.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (!isset($_POST['usernameChange']) || $_POST['usernameChange'] === ''){
        $_SESSION['profileError'] = "You must submit a new username!";
    }
    else{
        $success = updateUser($_POST['usernameChange']);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="https://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />
  
  <title>Profile</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="styles.css">  
</head>
<body>
<?php include('header.php') ?> 
<div style="padding-bottom: 10px;">
    <h1 style="text-align: center; padding-top: 20px;">Profile</h1>
    <div class="profile-information-container">
        <p>Username: <?php echo $_SESSION['name'] ?></p>
        <p>Computing ID: <?php echo $_SESSION['computingId'] ?></p>
        <p>Password: We do not display password. Please click 'Change Password' to change it.</p>
    </div>
    <h1 style="text-align: center; padding-top: 20px;">Change Profile Information</h1>
    <form action="profile.php" method="POST" style="display: flex; flex-direction: column; gap: 1rem; align-items: center;">
        <label for="name">Change Username</label>
        <input type="text" name="usernameChange" class="login-input">
        <button type="submit" class="login-button" style="width:200px;">Submit Changes</button>
    </form>
    <?php if (isset($_SESSION['profileError'])){
        echo "<p style='color: red; text-align: center; padding-top: 10px;'>" . $_SESSION['profileError'] . "</p>";
    }
    ?>
</div>
<?php include('footer.html') ?> 
</body>