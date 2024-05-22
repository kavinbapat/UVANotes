<?php session_start(); ?>

<header>  
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container-fluid">            
      <a class="navbar-brand" href="/">UVANotes</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav ms-auto">
          <?php if (!isset($_SESSION['name'])) { ?>     
            <li class="nav-item">
              <a class="nav-link" href="departments.php">Departments</a>
            </li>         
            <li class="nav-item">
              <a class="nav-link" href="register.php">Register</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Sign in</a>
            </li>              
          <?php  } else { ?>   
            <li class="nav-item">
              <a class="nav-link" href="departments.php">Departments</a>
            </li>     
            <li class="nav-item">                  
              <a class="nav-link" href="schedule.php">Schedule</a>
            </li>   
            <li class="nav-item">                  
              <a class="nav-link" href="profile.php">Profile</a>
            </li>   
            <li class="nav-item">                  
              <a class="nav-link" href="signout.php">Sign out</a>
            </li>
          <?php } ?>
        
        </ul>
      </div>
    </div>
  </nav>
</header>    