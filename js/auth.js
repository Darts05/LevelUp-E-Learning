const signupForm = document.getElementById('signupForm');

if (signupForm) {
    signupForm.addEventListener('submit', (e) => {
        e.preventDefault(); // Stop page from refreshing

        const name = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // Create a user object
        const userData = { name, email, password };

        // Save to Browser Storage (Simulating a database)
        localStorage.setItem(email, JSON.stringify(userData));

        alert("Account Created Successfully! You can now login.");
        window.location.href = "login.html"; // Redirect to login
    });
}