<?php
session_start();
require("../require/dashboard_layout.php");
require("../require/db_connection/connection.php");

// require("../require/layout.php");
// require("../functions/user_functions.php");


if (isset($_SESSION['user']['email']) and $_SESSION['user']['role_id'] == 2) {
    header("location:../index.php");
    die();
}



// user_navbar();
admin_header();
admin_navbar();




if (isset($_GET['post_id'])) {
    $query = "SELECT * FROM post_atachment where post_id =" . $_GET['post_id'];
    $result = mysqli_query($connection, $query);
?>
    <main class="col-12 col-md-9 dashboard p-4 mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="heading-2">Post Attachments</h2>

            
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAttachmentModal">
                + New Attachment
            </button>
        </div>

        
        <div class="modal fade" id="addAttachmentModal" tabindex="-1" aria-labelledby="addAttachmentLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="../processes/attachment_process.php" method="POST" enctype="multipart/form-data" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAttachmentLabel">Add Attachment for Post ID <?= $_GET['post_id'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="action" value="create">
                        <input type="hidden" name="post_id" value="<?= $_GET['post_id'] ?>">

                        <div class="mb-3">
                            <label for="attachment_title" class="form-label">Attachment Title</label>
                            <input type="text" class="form-control" name="attachment_title" id="attachment_title" required>
                        </div>

                        <div class="mb-3">
                            <label for="attachment_file" class="form-label">Choose File</label>
                            <input type="file" class="form-control" name="attachment_file" id="attachment_file" required>
                        </div>

                        <div class="mb-3">
                            <label for="is_active" class="form-label">Status</label>
                            <select class="form-select" name="is_active" id="is_active">
                                <option value="Active" selected>Active</option>
                                <option value="InActive">Inactive</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Upload Attachment</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>


        <div class="table-responsive" id="table-responsive" style="overflow-x:auto;">
            <table id="attachmentsTable" class="table table-hover align-middle display nowrap" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Attachment ID</th>
                        <th>Post ID</th>
                        <th>Title</th>
                        
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {

                    ?>
                        <tr>
                            <td><?= $row['post_atachment_id'] ?></td>
                            <td><?= $row['post_id'] ?></td>
                            <td><?= $row['post_attachment_title'] ?></td>


                            <td class="d-flex flex-row flex-nowrap align-items-center gap-1">



                                <a href="add_attachment.php?id=<?= $row['post_atachment_id'] ?>&from=edit_attachments&post_id=<?= $row['post_id'] ?>" class="btn btn-sm btn-outline-dark">Edit</a>
                                <a href="#" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Delete this attachment?');">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>


                </tbody>
            </table>
    </main>
<?php

}
admin_footer();
?>