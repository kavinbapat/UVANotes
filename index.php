<?php 
session_start();
require("connect-db.php");    // include("connect-db.php");
require("database-requests.php");
?>

<!-- <?php 

// $list_of_depts = getAllDepartments();

?> -->

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="https://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />
  
  <title>UVANotes</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="styles.css">  
</head>

<body>  
<?php include('header.php') ?> 
<div class="home">
    <div class="header">NoteFinder</div>
    <p class="description">Forgot to take notes during lecture? Look no further! Find comprehensive notes for any and all courses taught at the University of Virginia uploaded by students, for students!</p>
    <a href="departments.php" class="button">Find Notes</a>
</div>
<br/><br/>
<?php include('footer.html') ?> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>