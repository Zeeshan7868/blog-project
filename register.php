<?php
session_start();

$errors = $_SESSION['errors'] ?? [];
$old    = $_SESSION['old']    ?? [];

require("require/layout.php");
user_header();
user_navbar();


if(isset($_SESSION['user']['role_id']) && $_SESSION['user']['role_id'] == 2 ){
    header("location:index.php");
    die();
}else if(isset($_SESSION['user']['role_id']) && $_SESSION['user']['role_id'] ==  1){
    header("location:admin/dashboard.php");
    die();
}

?>

<div class="container-fluid px-0 overflow-x-hidden">
  <div class="row justify-content-center py-5" style="background-color: #eef1f5;">
    <div class="col-md-6 col-lg-5 bg-white p-4 rounded shadow" style="border-top: 4px solid #6366f1;">
      <h3 class="mb-4 text-center fw-bold" style="color: #4f46e5;">Create Your Account</h3>
      <form action="processes/user_process.php" method="post" enctype="multipart/form-data" onsubmit="return validation()">

        <div class="row">
          
          <div class="mb-3 col-lg-6">
            <label class="form-label text-secondary">First Name</label>
            <input
              type="text"
              name="first_name"
              id="first_name"
              class="form-control"
              placeholder="Enter First Name"
              value="<?= $old['first_name'] ?? ""  ?>">
            <span id="first_name_msg" class="text-danger">
              <?= $errors['first_name'] ?? '' ?>
            </span>
          </div>
          
          <div class="mb-3 col-lg-6">
            <label class="form-label text-secondary">Last Name</label>
            <input
              type="text"
              name="last_name"
              id="last_name"
              class="form-control"
              placeholder="Enter Last Name"
              value="<?= $old['last_name'] ?? "" ?>">
            <span id="last_name_msg" class="text-danger">
              <?= $errors['last_name'] ?? '' ?>
            </span>
          </div>
        </div>

        
        <div class="mb-3">
          <label class="form-label text-secondary">Email Address</label>
          <input
            type="text"
            name="email"
            id="email"
            class="form-control"
            onblur="checkEmail()"
            placeholder="Enter Email"
            value="<?= $old['email'] ?? "" ?>">
          <span id="email_msg" class="text-danger">
            <?= $errors['email'] ?? '' ?>
          </span>
          <span id="checkemail"></span>
        </div>

   
        <div class="mb-3">
          <label class="form-label text-secondary d-block">Gender</label>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="radio"
              name="gender"
              id="genderMale"
              value="Male"
              <?= (isset($old['gender']) && $old['gender'] === 'Male') ? 'checked' : '' ?>>
            <label class="form-check-label" for="genderMale">Male</label>
          </div>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="radio"
              name="gender"
              id="genderFemale"
              value="Female"
              <?= (isset($old['gender']) && $old['gender'] === 'Female') ? 'checked' : '' ?>>
            <label class="form-check-label" for="genderFemale">Female</label>
          </div>
          <span id="gender_msg" class="text-danger">
            <?= $errors['gender'] ?? '' ?>
          </span>
        </div>

        <!-- Date of Birth -->
        <div class="mb-3">
          <label class="form-label text-secondary">Date of Birth</label>
          <input
            type="date"
            name="dob"
            id="dob"
            class="form-control"
            value="<?= $old['dob'] ?? '' ?>">
          <span id="dob_msg" class="text-danger">
            <?= $errors['dob'] ?? '' ?>
          </span>
        </div>

        <!-- Profile Image -->
        <div class="mb-3">
          <label class="form-label text-secondary">Profile Image</label>
          <input
            class="form-control"
            id="profile_image"
            type="file"
            name="profile_image"
            onchange="fileValidation()">
          <span id="profile_image_msg" class="text-danger">
            <?= $errors['profile_image'] ?? '' ?>
          </span>
        </div>

        
        <div class="mb-3">
          <label class="form-label text-secondary">Address</label>
          <textarea
            class="form-control"
            id="address"
            name="address"
            rows="3"
            placeholder="Enter your address"><?= $old['address'] ?? '' ?></textarea>
          <span id="address_msg" class="text-danger">
            <?= $errors['address'] ?? '' ?>
          </span>
        </div>

        <div class="row">
          
          <div class="mb-3 col-md-6">
            <label class="form-label text-secondary">Password</label>
            <input
              type="password"
              name="password"
              id="password"
              class="form-control"
              placeholder="Enter Password">
            <span id="password_msg" class="text-danger">
              <?= $errors['password'] ?? '' ?>
            </span>
          </div>
          
          <div class="mb-3 col-md-6">
            <label class="form-label text-secondary">Confirm Password</label>
            <input
              type="password"
              name="confirm_password"
              id="confirm_password"
              class="form-control"
              placeholder="Confirm Password">
            <span id="confirm_password_msg" class="text-danger">
              <?= $errors['confirm_password'] ?? '' ?>
            </span>
          </div>
        </div>

        <div class="d-flex justify-content-center">
          <button
            type="submit"
            name="register"
            value="register"
            class="btn btn-primary me-2"
            id="register_btn"
            style="background-color: #4f46e5; border-color: #4f46e5;">
            Register
          </button>
          <button type="reset" name="cancel" class="btn btn-outline-secondary">
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function checkEmail() {
    const email = document.getElementById("email").value.trim();
    const emailMsg = document.getElementById("checkemail");
    const submitBtn = document.getElementById("register_btn");


    emailMsg.innerHTML = "";
    submitBtn.disabled = false;

    if (email === "") {
      return;
    }

    const ajax_request = new XMLHttpRequest();
    
    ajax_request.onreadystatechange = function() {
      if (ajax_request.readyState === 4 && ajax_request.status === 200) {
        emailMsg.innerHTML = ajax_request.responseText;

        if (ajax_request.responseText.includes("already exists")) {
          submitBtn.disabled = true;
        }

      }
    };
    ajax_request.open("POST", "processes/user_process.php");
    ajax_request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_request.send("action=checkemail&email=" + email);
  }
</script>


<script src="js/regiser_validation.js"></script>

<?php
user_footer();
?>