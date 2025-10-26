// ===========================
// TOGGLE FUNCTIONALITY
// This script switches between
// login and registration forms
// ===========================

// Select elements
const container = document.querySelector('.container');
const registerBtn = document.querySelector('.register-btn');
const loginBtn = document.querySelector('.login-btn');

// Show registration form
registerBtn.addEventListener('click', () => {
    container.classList.add('active');
});

// Show login form
loginBtn.addEventListener('click', () => {
    container.classList.remove('active');
});
