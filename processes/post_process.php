<?php
session_start();
require("../require/db_connection/connection.php");

// echo "<pre>";
// print_r($_POST);
// print_r($_FILES);
// echo "</pre>";

if (isset($_POST['action']) && $_POST['action'] === "add_post") {
    // extract($_POST);

    // Save featured image
    $featured_image_path = "";
    if (isset($_FILES['featured_image']['name']) && $_FILES['featured_image']['name'] !== '') {
        $file = $_FILES['featured_image'];
        $folder = "post_images";
        if (!is_dir($folder)) {
            mkdir($folder);
        }
        $new_name = time() . "_" . $file['name'];
        $upload_path = "$folder/$new_name";

        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            $featured_image_path = $upload_path;
        }
    }

    // Insert post
    $insert_post = "INSERT INTO post (blog_id, post_title, post_summary, post_description, featured_image, post_status, is_comment_allowed , created_at) VALUES (" . (int)$_POST['blog_id'] . ",'" . mysqli_real_escape_string($connection,$_POST['post_title'])  . "', '" . mysqli_real_escape_string($connection,$_POST['post_summary']) . "', '" . mysqli_real_escape_string($connection,$_POST['post_description']) . "', '" . $featured_image_path . "',  '" . $_POST['post_status'] . "', " . (int)$_POST['allow_comments'] . ", NOW())";

    $result = mysqli_query($connection, $insert_post);
    // var_dump($result);
    // die();

    if ($result) {
        $post_id = mysqli_insert_id($connection);
        if (isset($_POST['category_ids'])) {
            foreach ($_POST['category_ids'] as $cat_id) {
                $cat_id = (int)$cat_id;
                mysqli_query($connection, "INSERT INTO post_category (post_id, category_id) VALUES ($post_id, $cat_id)");
            }
        }


        
        if (isset($_FILES['attachments']['name'])) {
            $attachments_folder = "post_attachments";
            if (!is_dir($attachments_folder)) {
                mkdir($attachments_folder);
            }

            foreach ($_FILES['attachments']['name'] as $index => $file_name) {

                $tmp_name = $_FILES['attachments']['tmp_name'][$index];
                $new_name = time() . "_" . $file_name;
                $path = "$attachments_folder/$new_name";

                if (move_uploaded_file($tmp_name, $path)) {
                    $title = $_POST['attachment_titles'][$index];
                    $attach_res = mysqli_query($connection, "INSERT INTO post_atachment (post_id, post_attachment_title, post_attachment_path,is_active,created_at) VALUES ('$post_id', '$title', '$path','Active', NOW())");
                }
            }
        }

        header("Location: ../admin/posts.php?msg=Post added successfully");
        
    } else {
        header("Location: ../admin/posts.php?msg=Failed to add post");
        
    }
} else if (isset($_REQUEST['action']) and $_REQUEST['action'] == "edit") {
    $post_id = $_REQUEST['post_id'];
    $query = "SELECT * FROM post where post_id= $post_id";
    $result = mysqli_query($connection, $query);
} elseif (isset($_POST['action']) && $_POST['action'] === "update_post") {

    $post_id = $_POST['post_id'];
    $post_title = $_POST['post_title'];
    $post_summary = $_POST['post_summary'];
    $post_description = $_POST['post_description'];
    $post_status = $_POST['post_status'];
    $allow_comments = (int)$_POST['allow_comments'];
    $blog_id = $_POST['blog_id'];

    
    $featured_image_path = $_POST['current_image'];
    if (isset($_FILES['featured_image']['name']) && $_FILES['featured_image']['name'] !== '') {
        $file = $_FILES['featured_image'];
        $folder = "post_images";
        if (!is_dir($folder)) {
            mkdir($folder);
        }
        $new_name = time() . "_" . $file['name'];
        $upload_path = "$folder/$new_name";

        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            $featured_image_path = $upload_path;
        }
    }

    
    $update_query = "UPDATE post SET 
        blog_id = $blog_id, 
        post_title = '$post_title', 
        post_summary = '$post_summary', 
        post_description = '$post_description', 
        featured_image = '$featured_image_path',
        post_status = '$post_status',
        is_comment_allowed = $allow_comments,
        updated_at = NOW()
        WHERE post_id = $post_id";

    $result = mysqli_query($connection, $update_query);

    if ($result) {

        mysqli_query($connection, "DELETE FROM post_category WHERE post_id = $post_id");
        if (isset($_POST['category_ids'])) {
            foreach ($_POST['category_ids'] as $cat_id) {
                mysqli_query($connection, "INSERT INTO post_category (post_id, category_id) VALUES ($post_id, $cat_id)");
            }
        }

        if (isset($_FILES['attachments']['name'])) {
            $attachments_folder = "post_attachments";
            if (!is_dir($attachments_folder)) {
                mkdir($attachments_folder);
            }

            foreach ($_FILES['attachments']['name'] as $index => $file_name) {
                if ($file_name !== '') {
                    $tmp_name = $_FILES['attachments']['tmp_name'][$index];
                    $new_name = time() . "_" . $file_name;
                    $path = "$attachments_folder/$new_name";

                    if (move_uploaded_file($tmp_name, $path)) {
                        $title = $_POST['attachment_titles'][$index];
                        $insert_attachment = "INSERT INTO post_atachment 
                            (post_id, post_attachment_title, post_attachment_path, is_active, created_at) 
                            VALUES ($post_id, '$title', '$path', 'Active', NOW())";
                        mysqli_query($connection, $insert_attachment);
                    }
                }
            }
        }

        header("Location: ../admin/posts.php?msg=Post updated successfully");
    } else {
        header("Location: ../admin/posts.php?msg=Failed to update post");
    }
}
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "show_posts") {
    
    $type = isset($_POST['type']) ? $_POST['type'] : 'all';

    
    switch ($type) {
        case 'active':
            $where = "WHERE p.post_status = 'Active'";
            break;
        case 'inactive':
            $where = "WHERE p.post_status = 'Inactive'";
            break;
        case 'comments_enabled':
            $where = "WHERE p.is_comment_allowed = 1";
            break;
        case 'comments_disabled':
            $where = "WHERE p.is_comment_allowed = 0";
            break;
        default:
            $where = "";
    }

    $query = "
      SELECT p.post_id, b.blog_title, p.post_title, p.post_status,
             p.is_comment_allowed, p.created_at, p.updated_at
        FROM post p
        JOIN blog b ON p.blog_id = b.blog_id
        $where
    ";
    $result = mysqli_query($connection, $query);
?>
    <table id="blogsTable" class="table table-hover align-middle display nowrap" style="width:100%">
        <thead class="table-light">
            <tr>
                <th>Post ID</th>
                <th>Blog</th>
                <th>Title</th>
                <th>Status</th>
                <th>Comments Allowed</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['post_id'] ?></td>
                    <td><?= htmlspecialchars($row['blog_title']) ?></td>
                    <td>
                        <a href="view_post.php?post_id=<?= $row['post_id'] ?>" class="text-decoration-none">
                            <?= substr(htmlspecialchars($row['post_title']), 0, 30) ?>
                        </a>
                    </td>
                    <td>
                        <span class="badge <?= $row['post_status'] === 'Active' ? 'bg-success' : 'bg-danger' ?>">
                            <?= $row['post_status'] ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge <?= $row['is_comment_allowed'] ? 'bg-info text-dark' : 'bg-secondary' ?>">
                            <?= $row['is_comment_allowed'] ? 'Yes' : 'No' ?>
                        </span>
                    </td>
                    <td><?= $row['created_at'] ?></td>
                    <td><?= $row['updated_at'] ?></td>
                    <td class="d-flex flex-row flex-nowrap align-items-center gap-1">
                        <button class="btn btn-sm btn-outline-warning"
                            onclick="togglePostStatus(<?= $row['post_id'] ?>, '<?= $row['post_status'] ?>')">
                            <?= $row['post_status'] === 'Active' ? 'Inactive' : 'Active' ?>
                        </button>
                        <a href="add_post.php?action=edit&post_id=<?= $row['post_id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <button class="btn btn-sm btn-outline-info"
                            onclick="toggleComments(<?= $row['post_id'] ?>, <?= $row['is_comment_allowed'] ?>)">
                            <?= $row['is_comment_allowed'] ? 'Disable Comments' : 'Enable Comments' ?>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deletePost(<?= $row['post_id'] ?>)">
                            Delete
                        </button>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php
} else if (isset($_POST['action']) && $_POST['action'] === 'toggle_post_status') {
    $post_id = $_POST['post_id'];
    $new_status = $_POST['post_status'] === 'Active' ? 'Active' : 'Inactive';

    $update_status = "UPDATE post SET post_status = '$new_status' , updated_at= NOW() WHERE post_id = $post_id";
    $res = mysqli_query($connection, $update_status);
    echo $res ? "success" : "error";
} else if (isset($_POST['action']) && $_POST['action'] === 'toggle_comments') {
    $post_id = $_POST['post_id'];
    $allow_comments = (int)$_POST['allow_comments']; 

    $update_comments = "UPDATE post SET is_comment_allowed = $allow_comments , updated_at= NOW() WHERE post_id = $post_id";
    $res = mysqli_query($connection, $update_comments);
    echo $res ? "success" : "error";
}
elseif (isset($_POST['action']) && $_POST['action'] === "delete_post") {
    $post_id =$_POST['post_id'];
    mysqli_query($connection, "DELETE FROM post_atachment WHERE post_id = $post_id");
    mysqli_query($connection, "DELETE FROM post_category WHERE post_id = $post_id");
    mysqli_query($connection, "DELETE FROM post WHERE post_id = $post_id");
}
