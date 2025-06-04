<?php
session_start();
require("require/layout.php");
user_header();
user_navbar();
?>

<section class="py-5" style="height: 80vh;">
    <div class="container">
        <p class="text-text-success"> <?= $_GET['msg'] ?? "" ?> </p>
        <h1 class="text-center fw-bold mb-4">Contact Us</h1>

        <div class="row">
            <div class="col-md-8 offset-md-2">
                <form action="processes/feedback_process.php" method="POST">
                    <?php if (isset($_SESSION['user']['email'])){ ?>
                        
                        <input type="hidden" name="user_id" value="<?= $_SESSION['user']['user_id'] ?>">
                        <input type="hidden" name="username" value="<?= $_SESSION['user']['first_name'] ?>">
                        <input type="hidden" name="email" value="<?= $_SESSION['user']['email'] ?>">

                        <div class="mb-3">
                            <label for="feedback" class="form-label">Your Feedback</label>
                            <textarea class="form-control" id="feedback" name="feedback" rows="5" required></textarea>
                        </div>

                    <?php }else{ ?>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Your Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="feedback" class="form-label">Your Feedback</label>
                            <textarea class="form-control" id="feedback" name="feedback" rows="5" required></textarea>
                        </div>
                    <?php } ?>

                    <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit Feedback</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
user_footer();
?>