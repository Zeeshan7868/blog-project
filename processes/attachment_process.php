<?php
require("../require/db_connection/connection.php");

// echo "<pre>";
// print_r($_POST);
// print_r($_FILES);
// echo "</pre>";
// die();

if (isset($_POST['action']) && $_POST['action'] === 'create') {

    $post_id = $_POST['post_id'];
    $title = $_POST['attachment_title'];
    $is_active = $_POST['is_active'] ?? 'Active';
    $attachment_folder = "post_attachments";

    if (!is_dir($attachment_folder)) {
        mkdir($attachment_folder);
    }

    $file = $_FILES['attachment_file'];
    $new_file_name = time() . "_" . $file['name'];
    $upload_path = "$attachment_folder/$new_file_name";

    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        $query = "INSERT INTO post_atachment (post_id, post_attachment_title, post_attachment_path, is_active, created_at)
                  VALUES ($post_id, '$title', '$upload_path', '$is_active', NOW())";
        $result = mysqli_query($connection, $query);

        if ($result) {
            header("Location: ../admin/edit_attachments.php?post_id=" . $post_id . "&msg=Attachment added successfully");
        } else {
            header("Location: ../admin/edit_attachments.php?post_id=" . $post_id . "&msg=Failed to save attachment");
        }
    } else {
        header("Location: ../admin/edit_attachments.php?post_id=" . $post_id . "&msg=File upload failed");
    }

} elseif (isset($_POST['action']) && $_POST['action'] === 'update') {

    $id = $_POST['attachment_id'];
    $post_id = $_POST['post_id'];
    $title = $_POST['attachment_title'];
    $is_active = $_POST['is_active'] ?? 'Active';
    $upload_path = null;

    
    if (isset($_FILES['attachment_file']) && $_FILES['attachment_file']['error'] == 0) {
        $file = $_FILES['attachment_file'];
        $new_file_name = time() . "_" . $file['name'];
        $upload_folder = "post_attachments";
        if (!is_dir($upload_folder)) {
            mkdir($upload_folder);
        }
        $upload_path = "$upload_folder/$new_file_name";
        move_uploaded_file($file['tmp_name'], $upload_path);
    }

    if ($upload_path) {
        $query = "UPDATE post_atachment SET 
                    post_id = $post_id, 
                    post_attachment_title = '$title', 
                    post_attachment_path = '$upload_path',
                    is_active = '$is_active',
                    updated_at = NOW() 
                  WHERE post_atachment_id = $id";
    } else {
        $query = "UPDATE post_atachment SET 
                    post_id = $post_id, 
                    post_attachment_title = '$title',
                    is_active = '$is_active',
                    updated_at = NOW() 
                  WHERE post_atachment_id = $id";
    }

    $result = mysqli_query($connection, $query);
    if ($result) {
        if(isset($_POST['from']) && $_POST['from'] === 'edit_attachments' && isset($_POST['post_id'])) {
            header("Location: ../admin/edit_attachments.php?post_id=" . $_POST['post_id'] . "&msg=Attachment updated successfully");
        } else {
            header("Location: ../admin/attachments.php?msg=Attachment updated successfully");
        }
    } else {
        if(isset($_POST['from']) && $_POST['from'] === 'edit_attachments' && isset($_POST['post_id'])) {
            header("Location: ../admin/edit_attachments.php?post_id=" . $_POST['post_id'] . "&msg=Failed to update attachment");
        } else {
            header("Location: ../admin/attachments.php?msg=Failed to update attachment");
        }
    }

} else if (isset($_POST['action']) && $_POST['action'] === 'is_active') {
    $id = $_POST['attachment_id'];
    $newStatus = $_POST['active_value'];

    $query = "UPDATE post_atachment SET is_active = '$newStatus', updated_at = NOW() WHERE post_atachment_id = $id";
    $result = mysqli_query($connection, $query);

    echo $newStatus;
}
else if(isset($_POST['action']) && $_POST['action'] == "show_all"){

    $type = isset($_POST['type']) ? $_POST['type'] : 'all';

    $where = "";
    if ($type == 'active') {
        $where = "WHERE is_active = 'Active'";
    } elseif ($type == 'inactive') {
        $where = "WHERE is_active = 'InActive'";
    }

    $query = "SELECT * FROM post_atachment $where ORDER BY post_atachment_id DESC";
    $result = mysqli_query($connection, $query);
?>
    <table id="attachmentsTable" class="table table-hover align-middle display nowrap" style="width:100%">
      <thead class="table-light">
        <tr>
          <th>Attachment ID</th>
          <th>Post ID</th>
          <th>Title</th>
          <th>Download Link</th>
          <th>Active</th>
          <th>Created At</th>
          <th>Updated At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><?= $row['post_atachment_id'] ?></td>
            <td><?= $row['post_id'] ?></td>
            <td><?= $row['post_attachment_title'] ?></td>
            <td>
              <a href="<?= "../processes/" . $row['post_attachment_path'] ?>" target="_blank" class="text-decoration-none">
                Download
              </a>
            </td>
            <td> 
              <span id="is_active<?= $row['post_atachment_id'] ?>" class="badge <?= $row['is_active'] == 'Active' ? 'bg-success' : 'bg-danger' ?>">
                <?= $row['is_active'] ?>
              </span>
            </td>
            <td><?= $row['created_at'] ?></td>
            <td><?= $row['updated_at'] ?></td>
            <td class="d-flex flex-row flex-nowrap align-items-center gap-1">
              <button
                onclick="toggleAttachmentStatus(<?= $row['post_atachment_id'] ?>, '<?= $row['is_active'] ?>')"
                class="btn btn-sm btn-outline-warning">
                <?= $row['is_active'] == 'Active' ? 'InActive' : 'Active' ?>
              </button>
              <a href="add_attachment.php?id=<?= $row['post_atachment_id'] ?>" class="btn btn-sm btn-outline-dark">Edit</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
<?php
}
?>
