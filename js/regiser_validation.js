function validation() {
  
  // Patterns
  const namePattern = /^(?=.{3,}$)[A-Z][a-z]+(?: [A-Z][a-z]+)*$/;
  const emailPattern = /^[\w.-]+@([\w-]+\.)+[a-zA-Z]{2,4}$/;
  const passwordPattern = /^(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{8,}$/;

  // Input Fields
  const firstName = document.getElementById("first_name").value.trim();
  const lastName = document.getElementById("last_name").value.trim();
  const email = document.getElementById("email").value.trim();
  const gender = document.querySelector("input[type='radio']:checked");
  const dob = document.getElementById("dob").value.trim();
  const address = document.getElementById("address").value.trim();
  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById("confirm_password").value;

  // Message Elements
  const firstNameMsg = document.getElementById("first_name_msg");
  const lastNameMsg = document.getElementById("last_name_msg");
  const emailMsg = document.getElementById("email_msg");
  const genderMsg = document.getElementById("gender_msg");
  const dobMsg = document.getElementById("dob_msg");
  const addressMsg = document.getElementById("address_msg");
  const passwordMsg = document.getElementById("password_msg");
  const confirmPasswordMsg = document.getElementById("confirm_password_msg");

  // Form Validity Flag
  let isValid = true;

  // First Name Validation
  if (firstName === "") {
    isValid = false;
    firstNameMsg.innerText = "Field Required";
  } else if (!namePattern.test(firstName)) {
    isValid = false;
    firstNameMsg.innerText = "First letter uppercase, min 3 letters (e.g. Zeeshan)";
  } else {
    firstNameMsg.innerText = "";
  }

  // Last Name Validation
  if (lastName === "") {
    isValid = false;
    lastNameMsg.innerText = "Field Required";
  } else if (!namePattern.test(lastName)) {
    isValid = false;
    lastNameMsg.innerText = "First letter uppercase, min 3 letters (e.g. Mallah)";
  } else {
    lastNameMsg.innerText = "";
  }

  // Email Validation
  if (email === "") {
    isValid = false;
    emailMsg.innerText = "Field Required";
  } else if (!emailPattern.test(email)) {
    isValid = false;
    emailMsg.innerText = "Invalid format (e.g. zeeshan123@gmail.com)";
  } else {
    emailMsg.innerText = "";
  }

  // Gender Validation
  if (!gender) {
    isValid = false;
    genderMsg.innerText = "Field Required";
  } else {
    genderMsg.innerText = "";
  }

  // Date of Birth Validation
if (dob === "") {
  isValid = false;
  dobMsg.innerText = "Field Required";
} else {
  const birthDate = new Date(dob);
  const today = new Date();

  let age = today.getFullYear() - birthDate.getFullYear();
  const m = today.getMonth() - birthDate.getMonth();

  
  if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }

  if (age <= 10) {
    isValid = false;
    dobMsg.innerText = "Age must be equal or greater than 10 years";
  } else {
    dobMsg.innerText = "";
  }
}


  // Address Validation
  if (address === "") {
    isValid = false;
    addressMsg.innerText = "Field Required";
  } else {
    addressMsg.innerText = "";
  }

  // Password Validation
  if (password === "") {
    isValid = false;
    passwordMsg.innerText = "Field Required";
  } else if (!passwordPattern.test(password)) {
    isValid = false;
    passwordMsg.innerText = "Min 8 chars, include a number and a special character";
  } else {
    passwordMsg.innerText = "";
  }

  // Confirm Password Validation
  if (confirmPassword === "") {
    isValid = false;
    confirmPasswordMsg.innerText = "Field Required";
  } else if (password !== confirmPassword) {
    isValid = false;
    confirmPasswordMsg.innerText = "Passwords do not match";
  } else {
    confirmPasswordMsg.innerText = "";
  }

  return isValid;
}


function fileValidation() {
  let profileImage = document.getElementById("profile_image");
  let profileImageMsg = document.getElementById("profile_image_msg");

  if (profileImage.files.length === 0) {
    profileImageMsg.innerHTML = "";
    return;
  }

  let fileName = profileImage.files[0].name;
  let fileParts = fileName.split(".");
  let fileExtension = fileParts[fileParts.length - 1].toLowerCase();

  let extensionList = ["jpg", "jpeg", "png"];

  let flag = false;

  for (let i = 0; i < extensionList.length; i++) {
    if (extensionList[i] === fileExtension) {
      flag = true;
      break;
    }
  }

  if (!flag) {
    profileImageMsg.innerHTML = "Extensions should be JPG, JPEG, or PNG.";
  } else {
    profileImageMsg.innerHTML = "";
  }
}
