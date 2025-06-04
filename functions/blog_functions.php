<?php

function insertBlog($connection, $user_id, $blog_title, $posts_per_page, $cover_image_path, $blog_status)
{
    $query = "INSERT INTO blog (user_id, blog_title, post_per_page, blog_background_image, blog_status,created_at) 
              VALUES ('$user_id', '$blog_title', '$posts_per_page', '$cover_image_path', '$blog_status',NOW())";
    $result = mysqli_query($connection, $query);

    return $result;
}

function fetch_all_blogs_with_users($connection) {
    $query = "SELECT b.blog_id, u.first_name,b.blog_title,b.post_per_page,b.status FROM blog b JOIN user u ON b.user_id = u.user_id";
    $result = mysqli_query($connection, $query);
    return $result;
}

function get_blog_status_by_id($connection, $blog_id) {
    $query = "SELECT blog_status FROM blog WHERE blog_id = " . $blog_id;
    $result = mysqli_query($connection, $query);
    return $result;
}

function set_blog_status_inactive($connection, $blog_id) {
    $query = "UPDATE blog SET blog_status = 'InActive', updated_at = NOW() WHERE blog_id = " . $blog_id;
    $result = mysqli_query($connection, $query);
    return $result;
}
function set_blog_status_active($connection, $blog_id) {
    $query = "UPDATE blog SET blog_status = 'Active' , updated_at = NOW() WHERE blog_id = " . $blog_id;
    $result = mysqli_query($connection, $query);
    return $result;
}

function get_blog_row_by_id($connection, $id) {
    $query = "SELECT blog_background_image FROM blog WHERE blog_id = $id";
    $result = mysqli_query($connection, $query);
    return $result;
}

function updateBlog($connection, $blog_id, $blog_title, $posts_per_page, $blog_background_image, $blog_status) {
    $query = "UPDATE blog SET 
                blog_title = '$blog_title',
                post_per_page = '$posts_per_page',
                blog_background_image = '$blog_background_image',
                blog_status = '$blog_status',
                updated_at = NOW()
              WHERE blog_id = " . $blog_id;

    $result = mysqli_query($connection, $query);
    return $result;
}
