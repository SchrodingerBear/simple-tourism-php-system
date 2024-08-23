<?php
  // Connect to your database
  $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Query to get total bookings
  $query_bookings = "SELECT COUNT(*) as total_bookings FROM book_list";
  $result_bookings = mysqli_query($conn, $query_bookings);
  $row_bookings = mysqli_fetch_assoc($result_bookings);
  $total_bookings = $row_bookings['total_bookings'];

  // Query to get total packages
  $query_packages = "SELECT COUNT(*) as total_packages FROM packages";
  $result_packages = mysqli_query($conn, $query_packages);
  $row_packages = mysqli_fetch_assoc($result_packages);
  $total_packages = $row_packages['total_packages'];

  // Query to get total users
  $query_users = "SELECT COUNT(*) as total_users FROM users";
  $result_users = mysqli_query($conn, $query_users);
  $row_users = mysqli_fetch_assoc($result_users);
  $total_users = $row_users['total_users'];

  // Query to get total inquiries
  $query_inquiries = "SELECT COUNT(*) as total_inquiries FROM inquiry";
  $result_inquiries = mysqli_query($conn, $query_inquiries);
  $row_inquiries = mysqli_fetch_assoc($result_inquiries);
  $total_inquiries = $row_inquiries['total_inquiries'];

  // Report card HTML code
  ?>
  <hr>
  <div class="container">
    <div class="row">
      <div class="col-md-3">
        <div class="card bg-warning">
          <h5 class="card-header">Total Bookings</h5>
          <div class="card-body">
            <h1 class="display-4"><?php echo $total_bookings ?></h1>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-warning">
          <h5 class="card-header">Total Packages</h5>
          <div class="card-body">
            <h1 class="display-4"><?php echo $total_packages ?></h1>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-warning">
          <h5 class="card-header">Total Users</h5>
          <div class="card-body" bg>
            <h1 class="display-4"><?php echo $total_users ?></h1>

          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-warning">
          <h5 class="card-header">Total Inquiries</h5>
          <div class="card-body">
            <h1 class="display-4"><?php echo $total_inquiries ?></h1>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php
  // Close connection
  mysqli_close($conn);
  ?>
