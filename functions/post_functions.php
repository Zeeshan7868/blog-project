
<?php



function fetch_all($connection, $table) {

    $query = "SELECT * FROM $table";
    $result = mysqli_query($connection, $query);
    
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }
    
    return $result;
}

function fetch_by_post_Id($connection, $table, $id) {

    $query = "SELECT * FROM $table WHERE post_id = $id";
    $result = mysqli_query($connection, $query);
    
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }
    
    return $result;
}

?>