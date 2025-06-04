<?php

session_start();


require("../require/db_connection/connection.php");
require("../functions/blog_functions.php");

// echo "<pre>";
// print_r($_REQUEST);
// // // print_r($_FILES);
// echo "</pre>";

if (isset($_REQUEST['action']) and $_REQUEST['action'] == "add") {

    extract($_REQUEST);

    $cover_image_path = "";
    if (!empty($_FILES['blog_background_image']['name'])) {
        $fileName = $_FILES['blog_background_image']['name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (in_array($ext, $allowed)) {
            $folder = 'BlogImages';
            if (!is_dir($folder)) {
                mkdir($folder);
            }

            $newName = time() . '_' . $fileName;
            $uploadPath = "$folder/$newName";

            if (move_uploaded_file($_FILES['blog_background_image']['tmp_name'], $uploadPath)) {
                $cover_image_path = $uploadPath;
            } else {
                $cover_image_path = "";
            }
        }
    }



    $user_id = $_SESSION['user']['user_id'];
    $result = insertBlog($connection, $user_id, $blog_title, $posts_per_page, $cover_image_path, $blog_status);


    if ($result) {
        header("location:../admin/add_blog.php?msg=Blog Added Successfully!...");
    } else {
        header("location:../admin/add_blog.php?msg=Could not add blog!...");
    }
} else if (isset($_REQUEST['action']) and $_REQUEST['action'] == "show_blogs") {

    $result = mysqli_query($connection, "SELECT b.blog_id, u.first_name,b.blog_title,b.post_per_page,b.blog_status,b.created_at,b.updated_at FROM blog b JOIN user u ON b.user_id = u.user_id");

?>

    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Created_by</th>
            <th>Blog Title</th>
            <th>Posts Per Page</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>

        <?php
        while ($row = mysqli_fetch_assoc($result)) {

            extract($row);
        ?>
            <tr>

                <td><?= $blog_id ?></td>

                <td>
                    <?= $first_name ?>
                </td>
                <td><a href="view_blog.php?blog_id=<?= $blog_id ?>"><?= $blog_title ?></a></td>
                <td><?= $post_per_page ?></td>
                <td> <span id="is_active<?= $blog_id ?>"> <?= $blog_status ?></span></td>
                <td><?= $created_at ?></td>
                <td><?= $updated_at ?></td>
                <td>
                    <button class="btn btn-sm btn-outline-info" onclick="active_blog(<?= $blog_id ?>)">
                        <?php
                        if ($blog_status == "Active") {
                            echo "InActive";
                        } else {
                            echo "Active";
                        }
                        ?>
                    </button>
                    <a href="../admin/add_blog.php?action=edit&blog_id=<?= $blog_id ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                    <button onclick="delete_blog(<?= $blog_id ?>)" class="btn btn-sm btn-outline-danger">Delete</button>

                </td>
            </tr>
        <?php
        }

        ?>
    </tbody>



<?php
} else if (isset($_REQUEST['action']) and $_REQUEST['action'] == "is_active") {


    $blog_id = $_REQUEST['blog_id'];
    $result = get_blog_status_by_id($connection, $blog_id);

    $row = mysqli_fetch_assoc($result);

    if ($row['blog_status'] == "Active") {
        set_blog_status_inactive($connection, $blog_id);
    } else if ($row['blog_status'] == "InActive") {
        set_blog_status_active($connection, $blog_id);
    }
}

// $action = $_POST['action'] ?? '';

if (isset($_POST['action']) and $_POST['action'] === 'edit') {
    $id = $_POST['blog_id'];

    
    $result = get_blog_row_by_id($connection, $id);
    $row = mysqli_fetch_assoc($result);
    $oldImage = $row['blog_background_image'];

    $imageToSave = $oldImage;



    if (!empty($_FILES['blog_background_image']['name'])) {
        $file = $_FILES['blog_background_image'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (in_array($ext, $allowed)) {
            $folder = 'BlogImages';
            if (!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }

            $newName = time() . '_' . $file['name'];
            $path = "$folder/$newName";
            if (move_uploaded_file($file['tmp_name'], $path)) {
                $imageToSave = $path;

                
            }
        }
    }

    $blog_title = $_POST['blog_title'];
    $posts_per_page = $_POST['posts_per_page'];
    $blog_status = $_POST['blog_status'];

    $result = updateBlog($connection, $id, $blog_title, $posts_per_page, $imageToSave, $blog_status);

    if ($result) {
        header("Location: ../admin/add_blog.php?action=edit&blog_id=$id&msg=Blog updated successfully!...");
    } else {
        

        header("Location: ../admin/add_blog.php?action=edit&blog_id=$id&msg=Failed to update blog!...");
    }
}
 else if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $blog_id = $_POST['blog_id'];

    
    $result = get_blog_row_by_id($connection, $blog_id);
    if ($row = mysqli_fetch_assoc($result)) {
        if (!empty($row['blog_background_image']) && file_exists($row['blog_background_image'])) {
            unlink($row['blog_background_image']);
        }
    }

    
    $delete_query = "DELETE FROM blog WHERE blog_id = $blog_id";
    if (mysqli_query($connection, $delete_query)) {
        echo "Blog deleted successfully!";
    } else {
        echo "Failed to delete blog!";
    }
}
