var signupButton = document.getElementById('signup-button')
var loginButton = document.getElementById('login-button')
var userForms = document.getElementById('user_options-forms')
signupButton.addEventListener('click', function () {
  userForms.classList.remove('bounceRight')
  userForms.classList.add('bounceLeft')
}, !1)
loginButton.addEventListener('click', function () {
  userForms.classList.remove('bounceLeft')
  userForms.classList.add('bounceRight')
}, !1)
