<?php
session_start();
require("../require/dashboard_layout.php");
require("../require/layout.php");
require("../require/db_connection/connection.php");

if (isset($_SESSION['user']['email']) && $_SESSION['user']['role_id'] == 2) {
    header("location:../index.php");
    die();
}


$flag = false;

if(isset($_GET['id'])){
    $flag = true;
}

$attachment = null;

if ($flag) {
    $id = $_GET['id'];
    $editQuery = "SELECT * FROM post_atachment WHERE post_atachment_id = $id";
    $editResult = mysqli_query($connection, $editQuery);
    $attachment = mysqli_fetch_assoc($editResult);
}

$postQuery = "SELECT post_id, post_title FROM post";
$postResult = mysqli_query($connection, $postQuery);

admin_header();
admin_navbar();
?>

<main class="col-12 col-md-9 dashboard p-4 mt-5">
    <div class="col-lg-8 mx-auto bg-white p-4 rounded shadow-sm">
        <h3 class="mb-4 text-center fw-bold text-primary"><?= $flag ? 'Edit Attachment' : 'Add Attachment' ?></h3>

        <form action="../processes/attachment_process.php" method="POST" enctype="multipart/form-data">
            <?php if ($flag){ ?>
                <input type="hidden" name="attachment_id" value="<?= $attachment['post_atachment_id'] ?>">
                
            <?php } 
            if(isset($_GET['from'])){   
                ?>
                <input type="hidden" name="from" value="<?= $_GET['from'] ?>">
                <input type="hidden" name="post_id" value="<?= $_GET['post_id'] ?>">
                <?php
            }
            
            ?>

            <div class="mb-3">
                <label class="form-label">Select Post</label>
                <select name="post_id" class="form-select" required>
                    <option value="">-- Choose Post --</option>
                    <?php while ($post = mysqli_fetch_assoc($postResult)) { ?>
                        <option value="<?= $post['post_id'] ?>" <?= $flag && $post['post_id'] == $attachment['post_id'] ? 'selected' : '' ?>>
                            <?= $post['post_title'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Attachment Title</label>
                <input type="text" name="attachment_title" class="form-control" value="<?= $flag ? $attachment['post_attachment_title'] : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><?= $flag ? 'Replace File (optional)' : 'Choose File' ?></label>
                <input type="file" name="attachment_file" class="form-control" <?= $flag ? '' : 'required' ?>>
                <?php if ($flag){ ?>
                    <small class="text-muted">
                        Current File:
                        <a href="<?= "../processes/" . $attachment['post_attachment_path'] ?>" target="_blank">
                            <?= $attachment['post_attachment_title'] ?>
                        </a>
                    </small>
                <?php } ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-select">
                    <option value="Active" <?= !$flag || $attachment['is_active'] === 'Active' ? 'selected' : '' ?>>Active</option>
                    <option value="InActive" <?= $flag && $attachment['is_active'] === 'InActive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <div class="d-flex justify-content-center">
                <input type="hidden" name="action" value="<?= $flag ? 'update' : 'create' ?>">

                <button type="submit" class="btn btn-<?= $flag ? 'success' : 'primary' ?> me-2"><?= $flag ? 'Update' : 'Upload Attachment' ?></button>
                <a href="attachments.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</main>

<?php admin_footer(); ?>