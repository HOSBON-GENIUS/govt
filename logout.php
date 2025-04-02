<?php 
include "navbar.php";
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logout - Government Projects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .logout-container {
            background: rgba(0,0,0,0.8);
            padding: 30px;
            border-radius: 10px;
            color: white;
            margin-top: 50px;
            text-align: center;
        }
        .countdown {
            font-size: 2rem;
            color: #ce1126;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="logout-container">
                    <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                    <h2 class="mt-3">Logged Out Successfully</h2>
                    <p>Thank you for using Government Projects Portal</p>
                    <p>Redirecting in <span id="countdown" class="countdown">5</span> seconds...</p>
                    <a href="login_page.php" class="btn btn-outline-light mt-3">Login Again</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        let seconds = 5;
        const countdownElement = document.getElementById('countdown');
        
        const countdown = setInterval(() => {
            seconds--;
            countdownElement.textContent = seconds;
            
            if (seconds <= 0) {
                clearInterval(countdown);
                window.location.href = 'index.php';
            }
        }, 1000);
    </script>
</body>
</html>
