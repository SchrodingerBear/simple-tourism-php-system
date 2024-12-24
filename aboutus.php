<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify_otp'])) {
  // Verify OTP from session
  if ($_POST['verify_otp'] == $_SESSION['otp']) {
    echo 'success';
  } else {
    echo 'fail';
  }

  exit;
}
?>

<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">

<?php require_once('config.php'); ?>
<?php require_once('inc/header.php') ?>


<body class="hold-transition layout-top-nav">
  <?php $page = isset($_GET['page']) ? $_GET['page'] : 'portal'; ?>
  <?php require_once('inc/topBarNav.php') ?>

    <div class="content">
            <iframe src="about-us.php" style="width: 100%; height: 100vh; border: none;"></iframe>
</div>

</body>

</html>


<style>
  .form-control:focus {
    box-shadow: none !important;
  }

  #floating-bubble {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
  }

  #floating-bubble img {
    width: 50px;
    height: 50px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    cursor: pointer;
  }
</style>
<!-- Floating Bubble Link -->
<a href="https://m.me/scvpfb" target="_blank" id="floating-bubble">
  <img src="ms.png" alt="Chat with us" />
</a>