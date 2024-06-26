// scripts.js
const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.querySelector('.container');

signUpButton.addEventListener('click', () => {
    container.classList.add('right-panel-active');
    document.getElementById("register_div").style.display = "none";
});

signInButton.addEventListener('click', () => {
    container.classList.remove('left-panel-active');
});
