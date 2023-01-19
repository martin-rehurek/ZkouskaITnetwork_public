function validateRegisterForm() {
  let e = document.forms["RegisterForm"]["email"].value;
  let p1 = document.forms["RegisterForm"]["password"].value;
  let p2 = document.forms["RegisterForm"]["passwordAgain"].value;
  if (p1.length < 6) {
    alert("Heslo musí mít alespoň 6 znaků");
    return false;
  }
  if (p1 !== p2) {
    alert("Zadaná hesla se neshodují");
    return false;
  }
  if (e.length <= 5) {
	alert("E-mail musí mít alespoň 5 znaků");
  return false;
  }
}