<?php



function user_header()
{
?>
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Notes Over Flow</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
    <?php
}

function user_navbar()
{
    ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fa-solid fa-book-open me-1 text-primary"></i>Notes Over Flow
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="all_posts.php">All Posts</a></li>
                    <li class="nav-item"><a class="nav-link" href="blogs.php">Blogs</a></li>
                    <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>
                <?php if (isset($_SESSION['user']['email'])) { ?>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="<?= "processes/" . $_SESSION['user']['user_image'] ?>" alt="User" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                <span class="ms-2"><?= $_SESSION['user']['first_name'] ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="view_profile.php"><i class="fa fa-user me-2"></i>View Profile</a></li>
                                <li><a class="dropdown-item" href="theme_settings.php"><i class="fa fa-paint-brush me-2"></i>Theme Setting</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php } else { ?>
                    <div class="d-flex">
                        <a href="login.php" class="btn btn-outline-primary btn-sm me-2">Login</a>
                        <a href="register.php" class="btn btn-primary btn-sm">Sign Up</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </nav>
    <?php
}

function user_footer()
{
    ?>



        <footer class="footer text-center py-4 mt-5" style="background-color: #2c3e50; color: white;">
            <div class="container">
                <p class="mb-1">&copy; <?php echo date('Y'); ?> Notes Over Flow. All rights reserved.</p>
                <small class="text-muted">Designed by Zeeshan Ali</small>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js"></script>
    </body>

    </html>
<?php
}

function loading_modal()
{
?>

    <div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-transparent border-0 shadow-none">
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary" role="status" style="width: 4rem; height: 4rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-white bg-dark d-inline-block px-3 py-2 rounded">Please wait...</p>
                </div>
            </div>
        </div>
    </div>

<?php
}
