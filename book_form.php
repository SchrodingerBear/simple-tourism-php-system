<div class="container-fluid">
    <form action="" id="book-form">
        <div class="form-group">
            <input name="package_id" type="hidden" value="<?php echo $_GET['package_id'] ?>" >
            <input type="date" class='form form-control' required   name='schedule'>
        </div>
    </form>
</div>
<script>
  $(function(){
    $('#book-form').submit(function(e){
        e.preventDefault();
        start_loader();

        // Debugging: Log the serialized data
        var formData = $(this).serialize();
        console.log("Form Data:", formData); // Log the data being sent

        $.ajax({
            url: _base_url_ + "classes/Master.php?f=book_tour",
            method: "POST",
            data: formData,
            dataType: "json",
            error: err => {
                console.log("AJAX Error:", err); // Log the error response
                alert_toast("An error occurred", 'error');
                end_loader();
            },
            success: function(resp) {
                console.log("Server Response:", resp); // Log the server response
                if (typeof resp == 'object' && resp.status == 'success') {
                    alert_toast("Book Request Successfully sent.");
                    $('.modal').modal('hide');
                } else if (typeof resp == 'object' && resp.status == 'warning') {
                    // Display the warning message directly
                    alert_toast(resp.error, 'warning'); // Show the warning message without additional text
                } else {
                    alert_toast("An unexpected error occurred: " + (resp.error || "Unknown error"), 'error'); // Handle other errors
                }
                end_loader();
            }
        });
    });
});
</script> 