<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clue Chaos - Welcome</title>
        <div id="loader-placeholder"></div>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <style>
            body {
                margin: 0;
                font-family: Arial, sans-serif;
            }

            .welcome-page {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 100vh;
                text-align: center;
                background-image: url('./images/gradient.png');
                background-size: cover;
                background-position: center;
                color: white;
                backdrop-filter: blur(5px);
            }

            .quote {
                font-size: 2.5rem;
                margin-bottom: 20px;
                font-style: italic;
                color: #FFFFFF;
            }

            h1 {
                color: #1A1A2E;
            }

            #style_button {
                width: 165px;
                height: 62px;
                cursor: pointer;
                color: #000000;
                font-size: 28px;
                border-radius: 1rem;
                border: none;
                position: relative;
                background: #ffffff;
                transition: 0.1s;
            }

            #style_button::after {
                content: '';
                width: 100%;
                height: 100%;
                background-image: radial-gradient(circle farthest-corner at 10% 20%, rgba(255, 94, 247, 1) 17.8%, rgba(2, 245, 255, 1) 100.2%);
                filter: blur(15px);
                z-index: -1;
                position: absolute;
                left: 0;
                top: 0;
            }

            #style_button:hover {
                transform: scale(0.9) rotate(0.5deg);
                background: radial-gradient(circle farthest-corner at 10% 20%, rgba(0, 123, 255, 1) 17.8%, rgba(0, 82, 193, 1) 100.2%);
                transition: 0.5s;
                color: white;
            }
        </style>
    </head>
    <body>

    
  <script>
    // Fetch and include loader.html content
    fetch('loader.html')
      .then(response => response.text())
      .then(data => document.getElementById('loader-placeholder').innerHTML = data);
  </script>


  <script>
// loader.js
window.onload = function() {
  // Show the loader for 3 seconds
  setTimeout(function() {
    // Hide the loader after 3 seconds
    document.querySelector('.loader-container').style.display = 'none';
    
    // Show the main page content or redirect to another page
    document.querySelector('.content').style.display = 'block';
    
    // Or if you want to redirect to another page:
    // window.location.href = "your-main-page.html";
  }, 2000); // 3000ms = 3 seconds
};

</script>

        <div class="welcome-page">
            <h1>Clue Chaos</h1>
            <p class="quote">"Who is Undercover Among Us?"</p>
            <button data-bs-toggle="modal" id="style_button" data-bs-target="#actionModal">Play</button>
        </div>
<!-- Choice Modal -->
<div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="actionModalLabel">Choose an Option</h5>
            </div>
            <div class="modal-body text-center">
                <!-- Play as Guest Button -->
                <button class="btn btn-primary mb-3" id="style_button" style="font-size: 20px;" onclick="showGuestPage()">Play as Guest</button>
                
                <!-- Login Button -->
                <button class="btn btn-secondary mb-3" id="style_button" style="font-size: 20px;" onclick="openLoginModal()">Login</button>

                <!-- Recommended message with icon below the Login button -->
                <div class="recommendation">
                    <!-- Small icon (Font Awesome) -->
                    <i class="fa fa-info-circle" style="font-size: 20px; color: #007bff;"></i>
                    <span class="text-muted" style="font-size: 14px; cursor:pointer; margin-left: 5px;" data-bs-toggle="tooltip" title="For better user experience, it's recommended to login.">Recommended</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Initialize Tooltip (Bootstrap 5) -->
<script>
    // Enable tooltips on page load
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>


        

        <!-- Custom Alert Modal -->
        <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="alertModalLabel">Alert</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="alertModalBody">
                        <!-- Alert message will go here -->
                    </div>
                </div>
            </div>
        </div>

        <script>

function showGuestPage() {
    showAlert("Redirecting to the Game as a Guest...<br>Your Scores May not be Saved.");
    setTimeout(() => {
        window.location.href = "./start.php"; 
    }, 3000);
}


function showAlert(message) {
    document.getElementById('alertModalBody').innerHTML = message;
    const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
    alertModal.show();
    setTimeout(() => {
        alertModal.hide();
    }, 3000);
}

function openLoginModal(){
    window.location.href="./login.php";
}

// function openLoginModal() {
//     const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
//     loginModal.show();
// }

// function flipToRegister() {
//     document.getElementById('loginModalLabel').textContent = "Register";
//     document.getElementById('loginModalBody').innerHTML = `
//         <form id="registerForm" method="POST">
//             <input type="hidden" name="action" value="register">
//             <div class="mb-3">
//                 <label for="name" class="form-label">Full Name</label>
//                 <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
//             </div>
//             <div class="mb-3">
//                 <label for="email" class="form-label">Email address</label>
//                 <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
//             </div>
//             <div class="mb-3">
//                 <label for="password" class="form-label">Password</label>
//                 <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
//             </div>
//             <div class="mb-3">
//                 <label for="confirmPassword" class="form-label">Confirm Password</label>
//                 <input type="password" name="confirmPassword" class="form-control" id="confirmPassword" placeholder="Confirm Password" required>
//             </div>
//             <div class="text-center">
//                 <button type="submit" id="style_button" class="btn btn-primary mb-3" style="font-size: 20px;">Register</button>
//                 <br>
//                 <button type="button" class="btn btn-link" onclick="flipToLogin()">Already have an account? Login</button>
//             </div>
//         </form>
//     `;

//     // Add client-side validation for confirm password
//     document.getElementById('registerForm').addEventListener('submit', function (e) {
//         const password = document.getElementById('password').value;
//         const confirmPassword = document.getElementById('confirmPassword').value;

//         if (password !== confirmPassword) {
//             e.preventDefault(); // Prevent form submission
//             showAlert("Passwords do not match! Please try again.");
//         }
//     });
// }


// function flipToLogin() {
//     document.getElementById('loginModalLabel').textContent = "Login";
//     document.getElementById('loginModalBody').innerHTML = `
//         <form id="loginForm" method="POST">
//             <div class="mb-3">
//                 <label for="email" class="form-label">Email address</label>
//                 <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com">
//             </div>
//             <div class="mb-3">
//                 <label for="password" class="form-label">Password</label>
//                 <input type="password" name="password" class="form-control" id="password" placeholder="Password">
//             </div>
//             <div class="text-center">
//                 <button type="submit" id="style_button" class="btn btn-primary" style="font-size: 20px;">Login</button>
//                 <br>
//                 <button type="button" class="btn btn-link" onclick="flipToRegister()">Don't have an account? Register</button>
//             </div>
//         </form>
//     `;
//     const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
//     loginModal.show();
// }
            
//             // Ensure the modal body content doesn't get overridden if it's already showing the login form
//             document.querySelectorAll('.btn-link').forEach(button => {
//                 button.addEventListener('click', function(event) {
//                     if (this.innerText === "Already have an account? Login") {
//                         // Don't execute flipToRegister() when we're in the login modal
//                         event.preventDefault();
//                         flipToLogin();
//                     }
//                 });
//             });

//             document.addEventListener('DOMContentLoaded', function () {
//     const registerForm = document.getElementById('registerForm');
//     if (registerForm) {
//         registerForm.addEventListener('submit', function (e) {
//             e.preventDefault();
            
//             const formData = new FormData(this);
//             fetch('index.php', {
//                 method: 'POST',
//                 body: formData,
//             })
//                 .then(response => response.json())
//                 .then(data => {
//                     if (data.success) {
//                         // Display the OTP form
//                         document.getElementById('otpVerificationForm').style.display = 'block';
//                         showAlert("Registration successful. Please verify OTP.");
//                     } else {
//                         showAlert(data.message);
//                     }
//                 })
//                 .catch(error => {
//                     console.error('Error:', error);
//                     showAlert("An unexpected error occurred. Please try again.");
//                 });
//         });
//     }
// });


// document.getElementById('otpVerificationForm').addEventListener('submit', function (e) {
//     e.preventDefault();

//     const formData = new FormData(this);
//     fetch('index.php', {
//         method: 'POST',
//         body: formData,
//     })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 showAlert("Registration complete! Redirecting to login...");
//                 setTimeout(() => {
//                     window.location.href = 'index.php'; // Redirect to login
//                 }, 2000);
//             } else {
//                 showAlert(data.message);
//             }
//         })
//         .catch(error => {
//             console.error('Error:', error);
//             showAlert("An unexpected error occurred. Please try again.");
//         });
// });


        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>