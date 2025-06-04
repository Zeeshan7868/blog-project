<?php
session_start();

// require("functions/user_functions.php");
require("require/layout.php");
user_header();
user_navbar();

if (isset($_SESSION['user']['role_id']) && $_SESSION['user']['role_id'] == 2) {
    header("location:index.php");
    die();
} else if (isset($_SESSION['user']['role_id']) && $_SESSION['user']['role_id'] ==  1) {
    header("location:admin/dashboard.php");
    die();
}



?>

<div class="container-fluid px-3 px-md-0 overflow-x-hidden" style="background-color: #eef1f5;">
    <div class="row justify-content-center align-items-center min-vh-100 py-5">
        <div class="col-12 col-sm-10 col-md-6 col-lg-4 bg-white p-4 rounded shadow" style="border-top: 4px solid #6366f1;">
            <h3 class="mb-4 text-center fw-bold" style="color: #4f46e5;">Login to Your Account</h3>
            <p class="text-danger"><?= $_REQUEST["msg"] ?? "" ?></p>
            <?php
            if(isset($_REQUEST['is_sent'])){
                if($_REQUEST['is_sent'] == "yes"){
                    ?>
                    <a href="<?= $_REQUEST['pdf_link'] ?>">Download Email and password</a>
                    <?php
                }
            }

            ?>
            <form method="POST" action="processes/user_process.php" onsubmit="return validation()">
                <div class="mb-3">
                    <label class="form-label text-secondary">Email Address</label>
                    <input type="text" name="email" id="email" class="form-control" placeholder="Enter Email">
                    <span id="email_msg"></span>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password">
                    <span id="password_msg"></span>
                </div>

                <div class="mb-3 text-end">
                    <a href="forgot_password.php" class="small">Forgot your password?</a>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary" name="login" value="login" style="background-color: #4f46e5; border-color: #4f46e5;">Login</button>
                    <a href="register.php" class="btn btn-outline-secondary">Create an Account</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="js/login_validation.js"></script>

<?php
user_footer();
?>