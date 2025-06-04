<?php

require("../require/db_connection/connection.php");


if (isset($_POST['action']) && $_POST['action'] == 'add_comment') {
    // echo "<pre>";

    // print_r($_POST);
    // echo "</pre>";
    extract($_POST);

    $query = "INSERT INTO post_comment (post_id, user_id, comment,is_active,created_at) VALUES ($post_id, $user_id, '$comment', 'Active', NOW())";

    $result = mysqli_query($connection, $query);

    if ($result) {
        echo "Comment Added Successfully!...";
    } else {
        echo "Error adding comment: " . mysqli_error($connection);
    }
} else if (isset($_POST['action']) && $_POST['action'] == "show_comments") {
    session_start(); // make sure session is started
    $logged_in_user_id = $_SESSION['user']['user_id'] ?? 0;

    $post_id = (int) $_POST['post_id'];
    $query = "SELECT pc.*, pc.created_at AS commented_at, u.first_name, u.last_name 
              FROM post_comment pc 
              JOIN user u ON pc.user_id = u.user_id  
              WHERE post_id = $post_id 
              AND pc.is_active = 'Active'
              ORDER BY pc.created_at DESC";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']) ?></strong><br>
                            <small class="text-muted">
                                <?= date('d M Y, g:i A', strtotime($row['commented_at'])) ?>
                            </small>
                        </div>
                        <?php if ($row['user_id'] == $logged_in_user_id): ?>
                        <div>
                            <!-- <a href="#" class="btn btn-sm btn-outline-secondary" onclick="edit_comment(<?= $row['post_comment_id'] ?>)"><i class="fa fa-edit me-1"></i>Edit</a> -->
                            <button class="btn btn-sm btn-outline-danger" onclick="delete_comment(<?= $row['post_comment_id'] ?>)"><i class="fa fa-trash me-1"></i>Delete</button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <p class="mt-3 mb-0"><?= $row['comment'] ?></p>
                </div>
            </div>
            <?php
        }
    } else {
        echo '<div class="text-muted">No comments yet.</div>';
    }

} else if (isset($_POST['action']) and $_POST['action'] == "show_comments_in_dashboard") {
    $post_id = $_POST['post_id'];
    $comment_query = "SELECT pc.*, u.first_name, u.last_name 
                  FROM post_comment pc 
                  JOIN user u ON pc.user_id = u.user_id 
                  WHERE pc.post_id = $post_id 
                  ORDER BY pc.created_at DESC";

    $comment_result = mysqli_query($connection, $comment_query);

    ?>
    <table id="commentsTable" class="table table-hover align-middle display nowrap" style="width:100%">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Comment</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($data = mysqli_fetch_assoc($comment_result)) {
            ?>
                <tr>
                    <td><?= $data['post_comment_id'] ?></td>
                    <td><?= $data['first_name'] . " " . $data['last_name']  ?></td>
                    <td><?= $data['comment'] ?></td>
                    <td><span class="badge bg-success"><?= $data['is_active'] ?></span></td>
                    <td><?= $data['created_at'] ?></td>
                    <td class="d-flex flex-row flex-nowrap align-items-center gap-1">
                        <button
                            class="btn btn-sm btn-outline-warning"
                            onclick="toggle_comment_status(<?= $data['post_comment_id'] ?>, '<?= $data['is_active'] ?>')">
                            <?= $data['is_active'] == "Active" ? "InActive" : "Active" ?>
                        </button>

                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
<?php
}else if (isset($_POST['action']) && $_POST['action'] == "toggle_comment_status") {
    $comment_id = (int)$_POST['comment_id'];
    $status = $_POST['status'] == "Active" ? "InActive" : "Active";

    $query = "UPDATE post_comment SET is_active = '$status' WHERE post_comment_id = $comment_id";
    $result = mysqli_query($connection, $query);

    echo $result ? "success" : "error";
}

else if (isset($_POST['action']) && $_POST['action'] == "delete_comment") {
    session_start();
    $comment_id = (int) $_POST['comment_id'];
    $user_id = $_SESSION['user']['user_id'];

    // Make sure only the comment owner can delete it
    $check_query = "SELECT * FROM post_comment WHERE post_comment_id = $comment_id AND user_id = $user_id";
    $check_result = mysqli_query($connection, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $delete_query = "DELETE FROM post_comment WHERE post_comment_id = $comment_id";
        if (mysqli_query($connection, $delete_query)) {
            echo "Comment deleted successfully.";
        } else {
            echo "Error deleting comment: " . mysqli_error($connection);
        }
    } else {
        echo "Something went wrong!...";
    }
}

?>