<style>
    #uni_modal .modal-content>.modal-footer,
    #uni_modal .modal-content>.modal-header {
        display: none;
    }
</style>
<div class="container-fluid">
    <h3 class="float-left">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </h3>
    <div class="row">
        <div class="col-lg-5 border-right">
            <h3 class="text-center">Login</h3>
            <hr>
            <form action="" id="login-form">
                <div class="form-group">
                    <label for="" class="control-label">Email</label>
                    <input type="text" class="form-control form" name="username" required>
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Password</label>
                    <input type="password" class="form-control form" name="password" required>
                </div>
                <div class="form-group d-flex justify-content-end">
                    <button class="btn btn-primary btn-flat">Login</button>
                </div>
                <div class="col-8">
                    <a href="admin/login.php">Login as Administrator</a>
                </div>
            </form>
        </div>
        <div class="col-lg-7">
            <h3 class="text-center">Create New Account</h3>
            <hr class='border-primary'>
            <form action="" id="registration">
                <div class="form-group">
                    <label for="" class="control-label">Firstname</label>
                    <input type="text" class="form-control form-control-sm form" name="firstname" required>
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Lastname</label>
                    <input type="text" class="form-control form-control-sm form" name="lastname" required>
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Email</label>
                    <input type="email" class="form-control form-control-sm form" name="username" required>
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Password</label>
                    <input type="password" class="form-control form-control-sm form" name="password" required>
                </div>
                <div class="form-group">
                    <label for="" class="control-label">
                        <input type="checkbox" id="terms" required> I agree to the
                        <a href="#" id="terms-link">Terms and Conditions</a>
                    </label>
                </div>
                <div class="form-group d-flex justify-content-end">
                    <button class="btn btn-primary btn-flat">Register</button>
                </div>


            </form>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('#terms-link').click(function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Terms and Conditions',
                text: "Here, you can display the terms and conditions text that the user must agree to. Add a long description here.",
                icon: 'info',
                confirmButtonText: 'I Agree'
            });
        });


        // Submit registration form
        $('#registration').submit(function (e) {
            e.preventDefault();
            start_loader();
            if ($('.err-msg').length > 0) $('.err-msg').remove();

            $.ajax({
                url: _base_url_ + "classes/Master.php?f=register",
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                error: err => {
                    console.log(err);
                    alert_toast("An error occurred", 'error');
                    end_loader();
                },
                success: function (resp) {
                    if (typeof resp == 'object' && resp.status == 'success') {
                        end_loader();
                        askForOTP();
                    } else if (resp.status == 'failed' && !!resp.msg) {
                        var _err_el = $('<div>');
                        _err_el.addClass("alert alert-danger err-msg").text(resp.msg);
                        $('#registration').prepend(_err_el);
                        end_loader();
                    } else {
                        console.log(resp);
                        alert_toast("An error occurred", 'error');
                        end_loader();
                    }
                }
            });
        });

        // Function to prompt for OTP
        function askForOTP(invalidMessage = '') {
            Swal.fire({
                title: 'Enter Your OTP',
                input: 'text',
                inputValue: '',
                showCancelButton: false,
                allowOutsideClick: false,
                text: invalidMessage, // Show the error message if provided
                confirmButtonText: 'Submit',
                preConfirm: (inputValue) => {
                    if (!inputValue) {
                        Swal.showValidationMessage('OTP is required');
                    }
                    return inputValue;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    verifyOTP(result.value); // Call verifyOTP with the entered OTP
                }
            });
        }

        // Function to verify OTP
        function verifyOTP(inputOTP) {
            $.ajax({
                url: 'index.php', // PHP file to verify OTP
                type: 'POST',
                data: { verify_otp: inputOTP }, // Send entered OTP for verification
                success: function (response) {
                    console.log(response);
                    if (response.trim() === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'OTP Verified',
                            text: 'Account Verification Complete!'
                        }).then(() => {
                            window.location.href = 'logout.php'; // Redirect after successful verification
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid OTP',
                            text: 'Please try again.'
                        }).then(() => {
                            askForOTP('Invalid OTP. Please try again.'); // Re-ask after displaying the error
                        });
                    }
                },
                error: err => {
                    console.log(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'An error occurred',
                        text: 'Unable to verify OTP.'
                    });
                }
            });
        }
        // Handle login form submission
        $('#login-form').submit(function (e) {
            e.preventDefault();
            start_loader();
            if ($('.err-msg').length > 0) $('.err-msg').remove();
            $.ajax({
                url: _base_url_ + "classes/Login.php?f=login_user",
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                error: err => {
                    console.log(err);
                    alert_toast("An error occurred", 'error');
                    end_loader();
                },
                success: function (resp) {
                    if (typeof resp == 'object' && resp.status == 'success') {
                        alert_toast("Login Successfully", 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    } else if (resp.status == 'incorrect') {
                        var _err_el = $('<div>');
                        _err_el.addClass("alert alert-danger err-msg").text("Incorrect Credentials.");
                        $('#login-form').prepend(_err_el);
                        end_loader();
                    } else {
                        console.log(resp);
                        alert_toast("An error occurred", 'error');
                        end_loader();
                    }
                }
            });
        });
    });
</script>

<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.7/dist/sweetalert2.min.css" rel="stylesheet">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.7/dist/sweetalert2.min.js"></script>