<?php
include '../../config.php';
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT b.*, p.title, CONCAT(u.firstname, ' ', u.lastname) AS name, b.schedule 
                          FROM book_list b 
                          INNER JOIN packages p ON p.id = b.package_id 
                          INNER JOIN users u ON u.id = b.user_id 
                          WHERE b.id = '{$_GET['id']}' ");
    
    while ($row = $qry->fetch_assoc()) {
        foreach ($row as $k => $v) {
            $$k = $v; // Dynamically create variables for each column
        }
    }
}
?>
<style>
    #uni_modal .modal-content > .modal-footer {
        display: none;
    }
</style>
<p><b>Package:</b> <?php echo $title ?></p>
<p><b>Details:</b> <span class="truncate"><?php echo strip_tags(stripslashes(html_entity_decode($title))) ?></span></p>

<!-- Display Schedule -->
<p><b>Schedule:</b> <?php echo !empty($schedule) ? date("F d, Y", strtotime($schedule)) : 'No schedule available'; ?></p>

<form action="" id="book-status">
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <div class="form-group">
        <label for="" class="control-label">Status</label>
        <select name="status" id="" class="select custom-select">
            <option value="0" <?php echo $status == 0 ? "selected" : '' ?>>Pending</option>
            <option value="1" <?php echo $status == 1 ? "selected" : '' ?>>Confirmed</option>
            <option value="2" <?php echo $status == 2 ? "selected" : '' ?>>Cancelled</option>
            <option value="3" <?php echo $status == 3 ? "selected" : '' ?>>Done</option>
        </select>
    </div>
</form>

<div class="modal-footer">
    <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Update</button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
</div>

<script>
    $(function() {
        $('#book-status').submit(function(e) {
            e.preventDefault();
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=update_book_status",
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                error: function(err) {
                    console.log(err);
                    alert_toast("An error occurred", 'error');
                    end_loader();
                },
                success: function(resp) {
                    if (typeof resp == 'object' && resp.status == 'success') {
                        location.reload();
                    } else {
                        console.log(resp);
                        alert_toast("An error occurred", 'error');
                    }
                    end_loader();
                }
            });
        });
    })
</script>