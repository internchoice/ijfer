<?php
session_start();

// Protect the page
if (!isset($_SESSION['just_submitted']) || empty($_SESSION['just_submitted']['paper_id'])) {
    header("Location: submit-paper.php");
    exit;
}

$data = $_SESSION['just_submitted'];
unset($_SESSION['just_submitted']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Paper Submitted – IJFER</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">

  <style>
    :root {
      /* Your variables already loaded via style.css / header */
    }

    body {
      background: var(--bg-secondary);
      color: var(--text-dark);
    }

    .success-wrapper {
      min-height: 80vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 3rem 1rem 6rem;
    }

    .success-card {
      width: 100%;
      max-width: 860px;
      background: var(--bg-primary);
      border: none;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: var(--shadow-dark);
    }

    .success-header {
      background: var(--gradient-primary);
      color: var(--text-white);
      padding: 3.5rem 2.5rem 2.5rem;
      text-align: center;
      position: relative;
    }

    .success-header::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 15%;
      right: 15%;
      height: 5px;
      background: var(--secondary);
      border-radius: 5px;
    }

    .success-icon {
      font-size: 5.5rem;
      color: var(--secondary);
      margin-bottom: 1.2rem;
      filter: drop-shadow(0 6px 12px rgba(198,167,94,0.35));
    }

    .headline {
      font-size: 2.6rem;
      font-weight: 800;
      margin-bottom: 0.6rem;
      letter-spacing: -0.5px;
    }

    .subheadline {
      font-size: 1.25rem;
      opacity: 0.9;
      max-width: 620px;
      margin: 0 auto;
    }

    .info-section {
      padding: 2.8rem 2.5rem 1.8rem;
      background: var(--bg-accent);
    }

    .info-label {
      font-size: 0.95rem;
      font-weight: 700;
      color: var(--text-muted);
      margin-bottom: 0.4rem;
      letter-spacing: 0.6px;
      text-transform: uppercase;
    }

    .paper-id-display {
      font-size: 3.1rem;
      font-weight: 900;
      color: var(--accent);
      background: rgba(47, 93, 138, 0.07);
      padding: 0.8rem 1.2rem;
      border-radius: 10px;
      display: inline-block;
      margin: 0.6rem 0 1.8rem;
      box-shadow: inset 0 2px 6px rgba(47, 93, 138, 0.12);
    }

    .title-display {
      font-size: 1.65rem;
      font-weight: 700;
      color: var(--primary);
      line-height: 1.35;
    }

    .author-display {
      font-size: 1.4rem;
      font-weight: 600;
      color: var(--primary-dark);
    }

    .next-steps-section {
      padding: 0 2.5rem 3rem;
    }

    .alert-blue-message {
      background: rgba(47, 93, 138, 0.08);
      border: 1px solid var(--accent-light);
      border-left: 5px solid var(--accent);
      padding: 1.8rem 2rem;
      border-radius: 10px;
      margin: 2rem 0;
      color: var(--text-dark);
    }

    .alert-blue-message strong {
      color: var(--accent-dark);
    }

    .btn-track {
      background: var(--accent);
      border: none;
      color: white;
      padding: 1rem 2.8rem;
      font-size: 1.2rem;
      font-weight: 600;
      transition: all 0.3s;
      box-shadow: 0 4px 12px rgba(47, 93, 138, 0.25);
    }

    .btn-track:hover {
      background: var(--accent-dark);
      transform: translateY(-3px);
      box-shadow: 0 10px 24px rgba(47, 93, 138, 0.35);
    }

    @media (max-width: 576px) {
      .headline { font-size: 2.1rem; }
      .paper-id-display { font-size: 2.4rem; }
      .success-header { padding: 2.8rem 1.5rem 2rem; }
      .info-section, .next-steps-section { padding-left: 1.6rem; padding-right: 1.6rem; }
      .btn-track { padding: 0.9rem 2.2rem; font-size: 1.1rem; }
    }
  </style>
</head>
<body>

  <?php include '../../components/header.php'; ?>
  <div id="navbar-placeholder"></div>

  <div class="success-wrapper">
    <div class="success-card">

      <div class="success-header">
        <i class="fas fa-check-circle success-icon"></i>
        <h1 class="headline">Submission Successful!</h1>
        <p class="subheadline">Your manuscript has been successfully received by IJFER Editorial Team.</p>
      </div>

      <div class="info-section">
        <div class="mb-5">
          <div class="info-label">Paper ID</div>
          <div class="paper-id-display"><?php echo htmlspecialchars($data['paper_id']); ?></div>
        </div>

        <div class="mb-5">
          <div class="info-label">Paper Title</div>
          <div class="title-display"><?php echo htmlspecialchars($data['paper_title']); ?></div>
        </div>

        <div>
          <div class="info-label">Corresponding Author</div>
          <div class="author-display mt-2"><?php echo htmlspecialchars($data['first_author']); ?></div>
        </div>
      </div>

      <div class="next-steps-section">
        <div class="alert-blue-message">
          <strong>Next Steps – Timeline</strong>
          <ul class="mt-3 mb-0 ps-4" style="font-size: 1.08rem; line-height: 1.8;">
            <li>You will <strong>shortly receive a confirmation email</strong> containing your Paper ID and submission summary.</li>
            <li>Initial editorial screening / desk review will be completed within the next <strong>24–48 hours</strong>.</li>
            <li>You will receive a follow-up email with one of the following outcomes:
              <strong>Acceptance</strong>, <strong>Minor/Major Revision</strong>, or <strong>Rejection</strong>.
            </li>
            <li class="mt-3 fw-bold">Please preserve your Paper ID — it is essential for all future correspondence, status tracking, revision uploads, and certificate requests.</li>
          </ul>
        </div>

        <div class="text-center mt-5 pt-4">
          <a href="track-paper.php?paper_id=<?php echo urlencode($data['paper_id']); ?>" 
             class="btn btn-track btn-lg">
            <i class="fas fa-search me-2"></i> Track Your Paper Status
          </a>
        </div>

        <p class="text-center text-muted mt-5 small">
          Thank you for choosing IJFER.<br>
          We value your contribution to global research and scholarship.
        </p>
      </div>

    </div>
  </div>

  <?php include('../../components/footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/js/script.js"></script>
</body>
</html>