function toggleDropdown() {
    document.getElementById("userDropdown").classList.toggle("show");
}

function toggleLoginForm() {
    var loginForm = document.getElementById("loginForm");
    loginForm.style.display = (loginForm.style.display === "none") ? "block" : "none";
}

function toggleRegisterForm() {
    var registerForm = document.getElementById("registerForm");
    registerForm.style.display = (registerForm.style.display === "none") ? "block" : "none";
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.fas.fa-user')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}