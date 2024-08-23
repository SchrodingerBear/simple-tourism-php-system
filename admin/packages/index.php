<?php if($_settings->chk_flashdata('success')): ?>
<script>
    alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">List of Packages</h3>
        <div class="card-tools">
            <a href="?page=packages/manage" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Create New</a>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <table class="table table-bordered">
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="20%">
                    <col width="30%">
                    <col width="10%">
                    <col width="10%">
                    <col width="15%">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date Created</th>
                        <th>Package</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Booking Limit</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    $qry = $conn->query("SELECT * FROM `packages` ORDER BY date(date_created) DESC");
                    while($row = $qry->fetch_assoc()):
                        $row['description'] = strip_tags(stripslashes(html_entity_decode($row['description'])));
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                                <td><?php echo $row['title'] ?></td>
                                <td><p class="truncate-1 m-0"><?php echo $row['description'] ?></p></td>
                                <td class="text-center">
                                    <?php if($row['status'] == 1): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?php echo $row['booking_limit']; ?></td>
                                <td align="center">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href='?page=packages/manage&id=<?php echo $row['id'] ?>'">
                                        <span class="fas fa-edit text-white"></span>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm delete_data" data-id="<?php echo $row['id'] ?>">
                                        <span class="fa fa-trash text-white"></span>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('.delete_data').click(function(){
                _conf("Are you sure to delete this package permanently?", "delete_package", [$(this).attr('data-id')])
            })
            $('.table').dataTable();
        })
    
        function delete_package($id){
            start_loader();
            $.ajax({ 
                url: _base_url_+"classes/Master.php?f=delete_package",
                method: "POST",
                data: {id: $id},
                dataType: "json",
                error: err => {
                    console.log(err)
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                },
                success: function(resp){
                    if(typeof resp == 'object' && resp.status == 'success'){
                        location.reload();
                    } else {
                        alert_toast("An error occurred.", 'error');
                        end_loader();
                    }
                }
            })
        }
    </script>