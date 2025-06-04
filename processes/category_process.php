<?php
session_start();
require("../require/db_connection/connection.php");
require("../functions/category_functions.php");

if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $title  = $_POST['title'];
    $desc   = $_POST['description'];
    $status = $_POST['status'];
    $result = insertCategory($connection, $title, $desc, $status);
    if ($result) {
        header("Location: ../admin/categories.php");
        die();
    }
    echo "Failed to insert category: " . mysqli_error($connection);
} elseif (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $category_id = $_POST['category_id'];
    $title       = $_POST['title'];
    $desc        = $_POST['description'];
    $status      = $_POST['status'];
    $result = updateCategory($connection, $category_id, $title, $desc, $status);
    if ($result) {
        header("Location: ../admin/categories.php");
        die();
    }
    echo "Failed to update category: " . mysqli_error($connection);
} elseif (isset($_POST['action']) && $_POST['action'] === 'show_categories') {
    $type = isset($_POST['type']) ? $_POST['type'] : 'all';

    if ($type === 'active') {
        $res = getCategoriesByStatus($connection, 'Active');
    } elseif ($type === 'inactive') {
        $res = getCategoriesByStatus($connection, 'Inactive');
    } else {
        $res = getAllCategories($connection);
    }
?>
    <table id="categoriesTable" class="table table-hover display nowrap" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($res)) { ?>
                <tr>
                    <td><?= $row['category_id'] ?></td>
                    <td><?= $row['category_title'] ?></td>
                    <td><?= $row['category_description'] ?></td>
                    <td>
                        <span id="status<?= $row['category_id'] ?>">
                            <?= $row['category_status'] ?>
                        </span>
                    </td>
                    <td><?= $row['created_at'] ?></td>
                    <td><?= $row['updated_at'] ?></td>
                    <td>
                        <button
                            class="btn btn-sm btn-outline-warning"
                            onclick="toggle_category(<?= $row['category_id'] ?>)">
                            <?= $row['category_status'] === 'Active' ? 'InActive' : 'Active' ?>
                        </button>
                        <a
                            href="add_category.php?action=edit&category_id=<?= $row['category_id'] ?>"
                            class="btn btn-sm btn-outline-secondary">Edit</a>
                        <button
                            class="btn btn-sm btn-outline-danger"
                            onclick="delete_category(<?= $row['category_id'] ?>)">
                            Delete
                        </button>
                    </td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php

} elseif (isset($_POST['action']) && $_POST['action'] === 'toggle_status') {
    $category_id = $_POST['category_id'];
    $current     = $_POST['current_status'];
    $new_status  = $current === 'Active' ? 'Inactive' : 'Active';

    updateCategoryStatus($connection, $category_id, $new_status);
} elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $category_id = $_POST['category_id'];
    $query = "DELETE FROM category WHERE category_id = $category_id";
    $result = mysqli_query($connection, $query);

    if ($result) {
        echo "success";
    } else {
        echo "error";
    }
}
