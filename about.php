<?php
session_start();
require("require/layout.php");
user_header();
user_navbar();

// if(!isset($_SESSION['user']['email'])){
//   header("location:login.php?msg=Please Login First!...");
// }

?>

<section class="py-5">
    <div class="container">
        <h1 class="text-center fw-bold mb-4">About Us</h1>
        
        <div class="row mb-5">
            <div class="col-md-8 offset-md-2">
                <p class="text-muted">
                    Welcome to <strong>Notes Over Flow</strong>, a platform where you can explore and share valuable notes, blogs, and insights on a variety of topics. Whether you're a student, professional, or simply a curious learner, our website provides you with a place to find knowledge and engage with like-minded individuals.
                </p>
                <p>
                    Our mission is to make learning and sharing knowledge easy and accessible. We aim to create a community where people can grow, discover, and share their expertise through posts, blogs, and discussions.
                </p>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-8 offset-md-2">
                <h3 class="fw-semibold">Our Mission</h3>
                <p>
                    At Notes Over Flow, our mission is simple: to create a platform that empowers individuals to access valuable knowledge, share ideas, and collaborate with others to grow personally and professionally. We strive to provide a diverse range of topics and resources that inspire creativity and foster intellectual growth.
                </p>
            </div>
        </div>

     
    </div>
</section>

<?php
user_footer();
?>

