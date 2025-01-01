<?php
require_once('../config.php');
require '../vendor/autoload.php'; // Include Composer autoload
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class Master extends DBConnection
{
	private $settings;
	public function __construct()
	{
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	function save_package()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'description'))) {
				if (!empty($data))
					$data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}

		// Add the description field if it exists
		if (isset($_POST['description'])) {
			if (!empty($data))
				$data .= ",";
			$data .= " `description`='" . addslashes(htmlentities($description)) . "' ";
		}

		// Add the booking_limit field if it exists
		if (isset($_POST['booking_limit'])) {
			if (!empty($data))
				$data .= ",";
			$data .= " `booking_limit`='" . intval($booking_limit) . "' "; // Ensure it's an integer
		}

		if (empty($id)) {
			// Insert new package
			$sql = "INSERT INTO `packages` SET {$data} ";
			$save = $this->conn->query($sql);
			$id = $this->conn->insert_id;
		} else {
			// Update existing package
			$sql = "UPDATE `packages` SET {$data} WHERE id = '{$id}' ";
			$save = $this->conn->query($sql);
		}

		if ($save) {
			// Handle image uploads
			if (isset($_FILES['img']) && count($_FILES['img']['tmp_name']) > 0) {
				if (!is_dir(base_app . 'uploads/package_' . $id)) {
					mkdir(base_app . 'uploads/package_' . $id);
					$data = " `upload_path`= 'uploads/package_{$id}' ";
				} else {
					$data = " `upload_path`= 'uploads/package_{$id}' ";
				}
				$this->conn->query("UPDATE `packages` SET {$data} WHERE id = '{$id}' ");
				foreach ($_FILES['img']['tmp_name'] as $k => $v) {
					move_uploaded_file($_FILES['img']['tmp_name'][$k], base_app . 'uploads/package_' . $id . '/' . $_FILES['img']['name'][$k]);
				}
			}

			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "New Package successfully saved.");
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_p_img()
	{
		extract($_POST);
		if (is_file($path)) {
			if (unlink($path)) {
				$resp['status'] = 'success';
			} else {
				$resp['status'] = 'failed';
				$resp['error'] = 'unlink file failed.';
			}
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = 'unlink file failed. File do not exist.';
		}
		return json_encode($resp);
	}
	function book_tour()
	{
		extract($_POST);

		// Ensure that package_id is included only once
		$package_id = $this->conn->real_escape_string($package_id); // Assuming package_id is passed in POST
		$schedule = $this->conn->real_escape_string($schedule); // Assuming schedule is passed in POST

		// Step 1: Get the booking limit for the package
		$package_query = $this->conn->query("SELECT booking_limit FROM `packages` WHERE id = '$package_id'");

		if ($package_query->num_rows > 0) {
			$package = $package_query->fetch_assoc();
			$booking_limit = $package['booking_limit'];

			// Step 2: Count current bookings for the package on the same date
			$current_bookings_query = $this->conn->query("SELECT COUNT(*) as total_bookings FROM `book_list` WHERE package_id = '$package_id' AND schedule = '$schedule'");
			$current_bookings = $current_bookings_query->fetch_assoc()['total_bookings'];

			// Step 3: Check if adding this booking exceeds the limit for the same day
			if ($current_bookings >= $booking_limit) {
				return json_encode(['status' => 'warning', 'error' => 'Booking Limit Reached for this date']); // Changed status to 'warning'
			}

			// Step 4: Prepare data for insertion
			$data = " user_id = '" . $this->settings->userdata('id') . "', package_id = '$package_id', schedule = '$schedule' ";
			foreach ($_POST as $k => $v) {
				if ($k !== 'package_id' && $k !== 'schedule') { // Skip package_id and schedule since they're already added
					$data .= ", `{$k}` = '{$this->conn->real_escape_string($v)}' "; // Use real_escape_string for safety
				}
			}

			// Step 5: Insert the booking
			$save = $this->conn->query("INSERT INTO `book_list` SET $data");
			if ($save) {
				$resp['status'] = 'success';
			} else {
				$resp['status'] = 'failed';
				$resp['error'] = $this->conn->error; // Provide the actual database error
			}
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = 'Package not found.';
		}

		return json_encode($resp);
	}

	function register()
	{
		extract($_POST);
		$data = "";

		// Hash the password
		$_POST['password'] = md5($password);

		// Prepare data for insertion
		foreach ($_POST as $k => $v) {
			if (!empty($data))
				$data .= ",";
			$data .= " `{$k}`='{$v}' ";
		}

		// Check if username already exists
		$check = $this->conn->query("SELECT * FROM `users` WHERE username='{$username}' ")->num_rows;
		if ($check > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Username already taken.";
			return json_encode($resp);
		}

		// Generate OTP
		$otp = rand(100000, 999999);
		$_SESSION['otp'] = $otp;

		// Save user data
		$save = $this->conn->query("INSERT INTO `users` SET $data");
		if ($save) {
			// Send OTP email
			$sendStatus = $this->sendEmail($username, "Your OTP is: $otp");

			if ($sendStatus['status'] === 'success') {
				$resp['status'] = 'success';
				$resp['msg'] = 'Registration successful. OTP sent to your email.';
				$this->settings->set_userdata('id', $this->conn->insert_id);
			} else {
				$resp['status'] = 'failed';
				$resp['msg'] = 'Registration successful, but OTP email failed.';
				$resp['email_error'] = $sendStatus['message'];
			}
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}

		return json_encode($resp);
	}

	function sendEmail($to, $message)
	{
		$mail = new PHPMailer(true);
		try {
			$mail->isSMTP();
			$mail->Host = 'smtp.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Username = 'seancvpugosa@gmail.com';
			$mail->Password = 'cyow ivck ffdk etea'; // Replace with App Password
			$mail->SMTPSecure = 'tls';
			$mail->Port = 587;

			// Enable debugging
			$mail->SMTPOptions = [
				'ssl' => [
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true,
				],
			];

			// Recipients
			$mail->setFrom('seancvpugosa@gmail.com', 'Human Explore Travel and Tours');
			$mail->addAddress($to);

			// Content
			$mail->isHTML(true);
			$mail->Subject = 'OTP Verification';
			$mail->Body = $message;

			$mail->send();
			return ['status' => 'success', 'message' => 'Email sent successfully.'];
		} catch (Exception $e) {
			return ['status' => 'error', 'message' => 'Mail Error: ' . $e->getMessage()];
		}
	}

	function update_account()
	{
		extract($_POST);
		$data = "";
		if (!empty($password)) {
			$_POST['password'] = md5($password);
			if (md5($cpassword) != $this->settings->userdata('password')) {
				$resp['status'] = 'failed';
				$resp['msg'] = "Current Password is Incorrect";
				return json_encode($resp);
				exit;
			}

		}
		$check = $this->conn->query("SELECT * FROM `users`  where `username`='{$username}' and `id` != $id ")->num_rows;
		if ($check > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Username already taken.";
			return json_encode($resp);
			exit;
		}
		foreach ($_POST as $k => $v) {
			if ($k == 'cpassword' || ($k == 'password' && empty($v)))
				continue;
			if (!empty($data))
				$data .= ",";
			$data .= " `{$k}`='{$v}' ";
		}
		$save = $this->conn->query("UPDATE `users` set $data where id = $id ");
		if ($save) {
			foreach ($_POST as $k => $v) {
				if ($k != 'cpassword')
					$this->settings->set_userdata($k, $v);
			}

			$this->settings->set_userdata('id', $this->conn->insert_id);
			$resp['status'] = 'success';
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}

	function save_inquiry()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!empty($data))
				$data .= ",";
			$data .= " `{$k}`='{$v}' ";
		}
		$save = $this->conn->query("INSERT INTO `inquiry` set $data");
		if ($save) {
			$resp['status'] = 'success';
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function rate_review()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if ($k == 'review')
				$v = addslashes(htmlentities($v));
			if (!empty($data))
				$data .= ",";
			$data .= " `{$k}`='{$v}' ";
		}
		$data .= ", `user_id`='" . $this->settings->userdata('id') . "' ";

		$save = $this->conn->query("INSERT INTO `rate_review` set $data");
		if ($save) {
			$resp['status'] = 'success';
			// $this->settings->set_flashdata("success","Rate & Review submitted.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function delete_inquiry()
	{
		$del = $this->conn->query("DELETE FROM `inquiry` where id='{$_POST['id']}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata("success", "Inquiry Deleted.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function delete_review()
	{
		$del = $this->conn->query("DELETE FROM `rate_review` where id='{$_POST['id']}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata("success", "Feedback Deleted.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function delete_booking()
	{
		$del = $this->conn->query("DELETE FROM `book_list` where id='{$_POST['id']}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata("success", "Booking Deleted.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	function update_book_status()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			extract($_POST);

			// Check if id and status are set
			if (!isset($id) || !isset($status)) {
				return json_encode(['status' => 'failed', 'error' => 'ID and status are required.']);
			}

			try {
				// Start a transaction
				$this->conn->begin_transaction();

				// Update the booking status
				$stmt = $this->conn->prepare("UPDATE book_list SET status = ? WHERE id = ?");
				$stmt->bind_param("ii", $status, $id);

				if ($stmt->execute()) {
					// Fetch the user_id from the book_list table
					$stmt = $this->conn->prepare("SELECT user_id FROM book_list WHERE id = ?");
					$stmt->bind_param("i", $id);
					$stmt->execute();
					$result = $stmt->get_result();

					if ($result->num_rows > 0) {
						$row = $result->fetch_assoc();
						$user_id = $row['user_id'];

						// Fetch the user's email
						$stmt = $this->conn->prepare("SELECT username FROM users WHERE id = ?");
						$stmt->bind_param("i", $user_id);
						$stmt->execute();
						$user_result = $stmt->get_result();

						if ($user_result->num_rows > 0) {
							$user_row = $user_result->fetch_assoc();
							$email = $user_row['username'];

							// Send the email
							$status_text = $status === 1 ? "Approved" : ($status === 0 ? "Pending" : "Rejected");
							$message = "Your booking has been updated to: $status_text.";

							$email_resp = $this->sendEmail($email, $message);

							if ($email_resp['status'] === 'success') {
								$resp['status'] = 'success';
								$this->settings->set_flashdata("success", "Booking status updated and email sent successfully.");
							} else {
								throw new Exception("Booking status updated but email failed: " . $email_resp['message']);
							}
						} else {
							throw new Exception("User not found for user_id: $user_id.");
						}
					} else {
						throw new Exception("Booking not found for id: $id.");
					}
				} else {
					throw new Exception("Failed to update booking status: " . $this->conn->error);
				}

				// Commit the transaction
				$this->conn->commit();
			} catch (Exception $e) {
				// Rollback the transaction on error
				$this->conn->rollback();
				$resp['status'] = 'failed';
				$resp['error'] = $e->getMessage();
			} finally {
				$stmt->close();
			}
		} else {
			return json_encode(['status' => 'failed', 'error' => 'Invalid request method.']);
		}

		return json_encode($resp);
	}

}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_package':
		echo $Master->save_package();
		break;
	case 'delete_package':
		echo $Master->delete_package();
		break;
	case 'delete_p_img':
		echo $Master->delete_p_img();
		break;
	case 'book_tour':
		echo $Master->book_tour();
		break;
	case 'update_book_status':
		echo $Master->update_book_status();
		break;
	case 'register':
		echo $Master->register();
		break;
	case 'update_account':
		echo $Master->update_account();
		break;
	case 'save_inquiry':
		echo $Master->save_inquiry();
		break;
	case 'rate_review':
		echo $Master->rate_review();
		break;
	case 'delete_inquiry':
		echo $Master->delete_inquiry();
		break;
	case 'delete_booking':
		echo $Master->delete_booking();
		break;
	case 'delete_review':
		echo $Master->delete_review();
		break;
	default:
		// echo $sysset->index();
		break;
}