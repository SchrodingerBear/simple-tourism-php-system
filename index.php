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
  <?php
  if (!file_exists($page . ".php") && !is_dir($page)) {
    include '404.html';
  } else {
    if (is_dir($page))
      include $page . '/index.php';
    else
      include $page . '.php';

  }
  ?>
  <script>
    $(function () {
      if ($('header.masthead').lengt <= 0)
        $('#mainNav').addClass('navbar-shrink')
    })
  </script>
  <?php require_once('inc/footer.php') ?>
  <div class="modal fade text-dark" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmation</h5>
        </div>
        <div class="modal-body">
          <div id="delete_content"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade text-dark rounded-0" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
      <div class="modal-content rounded-0">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id='submit'
            onclick="$('#uni_modal form').submit()">Save</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade text-dark" id="uni_modal_right" role='dialog'>
    <div class="modal-dialog modal-full-height  modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span class="fa fa-arrow-right"></span>
          </button>
        </div>
        <div class="modal-body">
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade text-dark" id="viewer_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
        <img src="" alt="">
      </div>
    </div>
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