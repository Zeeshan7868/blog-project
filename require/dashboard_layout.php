<?php


function admin_header() {
    
    ?>
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dashboard</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
        <link rel="stylesheet" href="../css/admin_style.css">
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <body>
    <?php
}


function admin_navbar() {
    // require("../functions/user_functions.php");
    ?>
    <nav class="navbar navbar-light bg-white border-bottom d-md-none">
        <div class="container-fluid">
            <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sideNav" aria-controls="sideNav">
                <i class="fas fa-bars fa-2x"></i>
            </button>
            <span class="navbar-brand mb-0 h1">Dashboard</span>
        </div>
    </nav>

    <div class="container-fluid px-0">
        <div class="row g-0">

            
            <aside class="col-md-3 d-none d-md-block" style="height: 100%;">
                <div class="side-nav">
                    <div class="logo text-center">
                        <p class="heading">Dashboard</p>
                    </div>
                    <div class="admin-div text-center">
                        <img src="<?= "../processes/".$_SESSION['user']['user_image'] ?>" alt="">
                        <p class="pb-0 mb-0"><?= $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name'] ?></p>
                        <div class="d-grid mx-3 mt-2">
                            <a href="view_profile.php" class="btn btn-primary">View Profile</a>
                        </div>
                    </div>
                    <nav class="nav flex-column list-group list-group-flush px-2">
                        <a href="dashboard.php" class="list-group-item list-group-item-action">Home</a>
                        <a href="blogs.php" class="list-group-item list-group-item-action">Blogs</a>
                        <a href="accounts.php" class="list-group-item list-group-item-action">Accounts</a>
                        <a href="posts_overview.php" class="list-group-item list-group-item-action">Posts</a>
                        <a href="attachment_overview.php" class="list-group-item list-group-item-action">Attachments</a>
                        <a href="category_overview.php" class="list-group-item list-group-item-action">Categories</a>
                        <a href="feedbacks.php" class="list-group-item list-group-item-action">Feedbacks</a>
                        <a href="logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
                    </nav>
                </div>
            </aside>

            <!-- Offcanvas menu for small screens -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="sideNav" aria-labelledby="sideNavLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="sideNavLabel">Dashboard</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body p-0">
                    <div class="side-nav">
                        <div class="admin-div text-center">
                            <img src="https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg?semt=ais_hybrid&w=740" alt="">
                            <p class="pb-0 mb-0">this is admin</p>
                            <div class="d-grid mx-3 mt-2">
                                <button class="btn btn-primary">Update Profile</button>
                            </div>
                        </div>
                        <nav class="nav flex-column list-group list-group-flush px-2">
                            <a href="dashboard.php" class="list-group-item list-group-item-action">Home</a>
                            <a href="blogs.php" class="list-group-item list-group-item-action">Blogs</a>
                            <a href="accounts.php" class="list-group-item list-group-item-action">Accounts</a>
                            <a href="posts_overview.php" class="list-group-item list-group-item-action">Posts</a>
                            <a href="attachments.php" class="list-group-item list-group-item-action">Attachments</a>
                            <a href="categories.php" class="list-group-item list-group-item-action">Categories</a>
                            <a href="feedbacks.php" class="list-group-item list-group-item-action">Feedbacks</a>
                            <a href="logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
                        </nav>
                    </div>
                </div>
            </div>
    <?php
}


function admin_footer() {
    ?>
            </div> 
        </div> 
        <!-- <script>
            $('#blogsTable').DataTable({
                    // destroy: true, 
                    responsive: true,
                    autoWidth: false,
                    lengthChange: true,
                    pageLength: 5,
                    scrollX: true
                });
        </script> -->

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


        <footer class="footer text-center">
            &copy; <?php echo date('Y'); ?> by <span>Mr. Web Designer</span> | All rights reserved!
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>
    <?php
}

