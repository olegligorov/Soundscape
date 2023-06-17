<!DOCTYPE html>
<html>

<head>
  <title>Login</title>
  <link rel="stylesheet" href="./static/css/form.css" />
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
      <h2>Sign in</h2>
      <p>Enter your details</p>
    </div>

    <form method="POST" action="modules/login.php" class="registration_form">
      <div class="input-container">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required placeholder="Email"><br><br>
      </div>

      <div class="input-container">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required placeholder="Password"><br><br>
      </div>

      <div class="backend_error">
        <?php if (isset($_GET['error'])) : ?>
          <span><?php echo $_GET['error']; ?></span>
        <?php endif; ?>
      </div>

      <div class="submit-btn-container">
        <input id="submit-btn" type="submit" value="Sign in">
      </div>
    </form>

    <div>
      <p>Don't have an account? <a href="registration_form.php">Sign up</a></p>
    </div>

  </div>

</body>

</html>