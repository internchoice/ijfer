<?php
// Optional: you can add PHP logic later (e.g., countdown end date, email signup)
$launchDate = "2026-06-01"; // example — change to your actual target date
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coming Soon - IJFER</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">

  <style>
    body {
      background: #f8f9fa;
      color: #2b2b2b;
    }

    .coming-soon-wrapper {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 3rem 1rem;
      background: linear-gradient(135deg, rgba(11,31,59,0.04) 0%, rgba(198,167,94,0.04) 100%);
    }

    .coming-soon-card {
      max-width: 780px;
      width: 100%;
      background: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 12px 48px rgba(11,31,59,0.12);
      text-align: center;
      padding: 4.5rem 2.5rem;
    }

    .logo-container {
      margin-bottom: 2.5rem;
    }

    .logo-container img {
      max-width: 220px;
      height: auto;
    }

    .main-heading {
      font-size: 3.8rem;
      font-weight: 800;
      color: #0b1f3b;
      margin-bottom: 1rem;
      letter-spacing: -1px;
    }

    .sub-heading {
      font-size: 1.45rem;
      color: #5a5a5a;
      margin-bottom: 3rem;
      max-width: 640px;
      margin-left: auto;
      margin-right: auto;
    }

    .countdown-container {
      display: flex;
      justify-content: center;
      gap: 1.8rem;
      margin: 3rem 0 4rem;
      flex-wrap: wrap;
    }

    .countdown-box {
      background: #0b1f3b;
      color: white;
      padding: 1.4rem 1.8rem;
      border-radius: 12px;
      min-width: 110px;
      text-align: center;
      box-shadow: 0 6px 16px rgba(11,31,59,0.25);
    }

    .countdown-number {
      font-size: 3.2rem;
      font-weight: 700;
      line-height: 1;
    }

    .countdown-label {
      font-size: 1rem;
      opacity: 0.9;
      margin-top: 0.5rem;
    }

    .email-form {
      max-width: 520px;
      margin: 0 auto 3rem;
    }

    .email-form .input-group {
      border-radius: 50px;
      overflow: hidden;
      box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    }

    .email-form .form-control {
      border: none;
      padding: 1.1rem 1.6rem;
      font-size: 1.1rem;
    }

    .email-form .btn {
      background: #c6a75e;
      border: none;
      color: #0b1f3b;
      font-weight: 600;
      padding: 0 2.5rem;
      transition: all 0.3s;
    }

    .email-form .btn:hover {
      background: #b8964a;
      transform: translateY(-2px);
    }

    .social-icons a {
      color: #2f5d8a;
      font-size: 2rem;
      margin: 0 1rem;
      transition: all 0.3s;
    }

    .social-icons a:hover {
      color: #c6a75e;
      transform: translateY(-4px);
    }

    @media (max-width: 576px) {
      .main-heading { font-size: 2.8rem; }
      .countdown-box { min-width: 85px; padding: 1rem; }
      .countdown-number { font-size: 2.4rem; }
    }
  </style>
</head>
<body>

<?php include __DIR__ . "/components/header.php"; ?>
<div id="navbar-placeholder"></div>

<div class="coming-soon-wrapper">
  <div class="coming-soon-card">
    <div class="logo-container">
      <img src="assets/images/logo.png" alt="IJFER Logo">
    </div>

    <h1 class="main-heading">Coming Soon</h1>
    <p class="sub-heading">
      We're preparing something exciting for the research community. Stay tuned — launching very soon!
    </p>

    <!-- Countdown (static for now — can make live with JS later) -->
    <!-- <div class="countdown-container">
      <div class="countdown-box">
        <div class="countdown-number">00</div>
        <div class="countdown-label">Days</div>
      </div>
      <div class="countdown-box">
        <div class="countdown-number">00</div>
        <div class="countdown-label">Hours</div>
      </div>
      <div class="countdown-box">
        <div class="countdown-number">00</div>
        <div class="countdown-label">Minutes</div>
      </div>
      <div class="countdown-box">
        <div class="countdown-number">00</div>
        <div class="countdown-label">Seconds</div>
      </div>
    </div> -->

    <!-- Optional email notification form -->
    <!-- <form class="email-form">
      <div class="input-group">
        <input type="email" class="form-control" placeholder="Enter your email for launch updates..." required>
        <button class="btn px-4" type="submit">Notify Me</button>
      </div>
    </form> -->

    <!-- Social links -->
    <!-- <div class="social-icons mt-5">
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="#"><i class="fab fa-linkedin-in"></i></a>
      <a href="#"><i class="fab fa-facebook-f"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
    </div>

    <p class="text-muted mt-5 small">
      International Journal of Futuristic Engineering Research<br>
      © <?= date('Y') ?> IJFER — All Rights Reserved
    </p> -->
  </div>
</div>

<?php include __DIR__ . "/components/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>