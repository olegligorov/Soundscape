<!DOCTYPE html>
<html>

<head>
  <title>Register</title>
  <link rel="stylesheet" href="./static/css/form.css" />
  <script src="./static/js/registration_form.js" defer></script>
</head>

<body>
  <header class="header-section">
    <div>
      <a href="index.php">
        <img src="./static/images/soundscape_logo.png" alt="soundscape-small-logo" class="soundscape-small-logo">
      </a>
    </div>
  </header>
  <div class="form-body">
    <div class="form-heading-text">
      <h2>Sign up</h2>
      <p>Enter your details to get started</p>
    </div>

    <form method="POST" action="modules/register.php" class="registration_form" id="registration_form">
      <div class="input-container">
        <label for="name">Full name</label>
        <input type="text" id="name" name="name" required placeholder="Full name"><br><br>
      </div>

      <div class="input-container">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required placeholder="Email"><br><br>
      </div>

      <div class="input-container">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required placeholder="Password"><br><br>
      </div>

      <div class="input-container">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm password"><br><br>
      </div>

      <div class="backend_error">
        <?php if (isset($_GET['error'])) : ?>
          <span><?php echo $_GET['error']; ?></span>
        <?php endif; ?>
      </div>
      
      <div id="error" class="error">
      </div>

      <div class="submit-btn-container">
        <input id="submit-btn" type="submit" value="Sign up">
      </div>
    </form>

    <div>
      <p>Already have an account? <a href="login_form.php">Sign in</a></p>
    </div>

  </div>



</body>

</html>