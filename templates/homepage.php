<?php
include('../services/user_service.php');
$user_id = $_SESSION["currentUser"];

if (!$user_id) {
  header("Location: ../modules/login.php");
  exit;
}

$user = fetch_user_data($user_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../static/css/homepage.css" />
</head>

<body>
  <main class="page-container">

    <aside class="left-section">
      <div class="header-section">
        <img src="../static/images/soundscape_logo.png" alt="soundscape-small-logo" class="soundscape-small-logo">
      </div>

      <nav class="navigation">
        <ul class="navigation_list">
          <li class="navigation_item">
            <img src="../static/images/home.svg" alt="home" class="navigation_icon">
            <span>Home</span>
          </li>
          <li class="navigation_item">
            <img src="../static/images/search.svg" alt="sarch" class="navigation_icon">
            <span>Search</span>
          </li>
        </ul>
      </nav>


    </aside>
    <section class="right-section">
      <div class="heading">
        <p>
          <?php
          echo "Wellcome " . $user['name'];
          ?>
        </p>
        <button class="logout_btn"><a href="../modules/logout.php">Logout</a></button>
      </div>


    </section>

  </main>


</body>

</html>