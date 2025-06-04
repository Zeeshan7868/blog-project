<?php

function insertCategory($connection, $title, $desc, $status) {
    $query = "INSERT INTO category (category_title, category_description, category_status, created_at) 
              VALUES ('$title', '$desc', '$status', NOW())";
    $result = mysqli_query($connection, $query);
    return $result;
}

function updateCategory($connection, $category_id, $title, $desc, $status) {
    $query = "UPDATE category SET 
                category_title = '$title', 
                category_description = '$desc', 
                category_status = '$status', 
                updated_at = NOW() 
              WHERE category_id = $category_id";
    return mysqli_query($connection, $query);
}
function getAllCategories($connection) {
    $query = "SELECT * FROM category";
    return mysqli_query($connection, $query);
}

function updateCategoryStatus($connection, $category_id, $new_status) {
    $query = "UPDATE category SET category_status = '$new_status', updated_at = NOW() WHERE category_id = '$category_id'";
    $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
    return $result;
}

function getCategoriesByStatus($connection, $status) {
  $query = "SELECT * FROM category WHERE category_status = '$status' ORDER BY created_at DESC";
  return mysqli_query($connection, $query);
}


?>