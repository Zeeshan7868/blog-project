function validation() {

  const emailPattern = /^[\w.-]+@([\w-]+\.)+[a-zA-Z]{2,4}$/;

  const email = document.getElementById("email").value.trim();

  const emailMsg = document.getElementById("email_msg");
  
  let isValid = true;


  if (email === "") {
    emailMsg.innerText = "Field Required";
    emailMsg.style.color = "red";
    isValid = false;
  } else if (!emailPattern.test(email)) {
    emailMsg.innerText = "Invalid format (e.g. example@gmail.com)";
    emailMsg.style.color = "red";
    isValid = false;
  } else {
    emailMsg.innerText = "";
  }


  if (password === "") {
    passwordMsg.innerText = "Field Required";
    passwordMsg.style.color = "red";
    isValid = false;
  }{
    passwordMsg.innerText = "";
  }

  return isValid;
}