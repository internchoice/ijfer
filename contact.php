<?php
require_once "admin/config/db.php";

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $inquiry_type = trim($_POST['inquiry_type']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (!$full_name || !$email || !$phone || !$inquiry_type || !$subject || !$message) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages 
                (full_name, email, phone, inquiry_type, subject, message)
                VALUES (:full_name, :email, :phone, :inquiry_type, :subject, :message)");

            $stmt->execute([
                ':full_name' => $full_name,
                ':email' => $email,
                ':phone' => $phone,
                ':inquiry_type' => $inquiry_type,
                ':subject' => $subject,
                ':message' => $message
            ]);

            $success = "Your inquiry has been submitted successfully.";
        } catch (PDOException $e) {
            die("SQL ERROR: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - IJFER</title>
  <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include __DIR__ . "/components/header.php"; ?>
<div id="navbar-placeholder"></div>

<main class="container my-4">
  <div class="row">
    <div class="col-lg-8">
      <div class="main-content">
        <h1>Contact Us</h1>

        <?php if($success): ?>
          <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <?php if($error): ?>
          <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <div class="contact-info mb-4">
          <div class="address-info bg-light p-4 rounded mb-4">
            <h4 class="mb-3">
              <i class="fas fa-map-marker-alt text-dark-navy me-2"></i>Our Address
            </h4>
            <p class="mb-2"><strong>International Research Journal of Modernization in Engineering Technology & Science</strong></p>
            <p class="mb-2">Near Gangapur Dam, Mumbai-Nashik Highway,</p>
            <p class="mb-3">Nashik (Maharashtra)</p>
            
            <div class="contact-methods">
              <p class="mb-2">
                <i class="fas fa-envelope text-dark-navy me-2"></i>
                <strong>Email:</strong> editor@IJFER.com
              </p>
              <p class="mb-0">
                <i class="fas fa-phone text-dark-navy me-2"></i>
                <strong>Phone:</strong> +91 786-969-67-94, +91 786-986-27-10
              </p>
              <p class="mb-0">
                <small class="text-muted">(WhatsApp or SMS on these numbers)</small>
              </p>
            </div>
          </div>
        </div>

        <div class="contact-form-section">
          <h3 class="mb-4">Suggestions for IJFER Editorial Team</h3>

          <form id="contactForm" method="POST">

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Full Name *</label>
                <input type="text" name="full_name" class="form-control"
                       placeholder="Enter your full name"
                       value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>"
                       required>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-control"
                       placeholder="your.email@example.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       required>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Phone Number *</label>
                <input type="tel" name="phone" class="form-control"
                       placeholder="+91 98765 43210"
                       value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                       required>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Inquiry Type *</label>
                <select name="inquiry_type" class="form-select" required>
                  <option value="">Please select inquiry type</option>
                  <?php
                  $types = [
                    "Author Submission","Reviewer Application","Paper Status Inquiry",
                    "Editorial Board","Publication Ethics","Technical Support",
                    "Payment & Fees","Partnership & Collaboration","General Inquiry"
                  ];
                  foreach($types as $type){
                      $selected = (($_POST['inquiry_type'] ?? '') == $type) ? "selected" : "";
                      echo "<option $selected>$type</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Subject *</label>
              <input type="text" name="subject" class="form-control"
                     placeholder="Brief subject of your inquiry"
                     value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>"
                     required>
            </div>
            
            <div class="mb-4">
              <label class="form-label">Detailed Message *</label>
              <textarea name="message" class="form-control" rows="6"
                        placeholder="Please provide detailed information about your inquiry. Include relevant paper IDs, submission details, or specific questions you have."
                        required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg px-5">
              <i class="fas fa-paper-plane me-2"></i>Submit Inquiry
            </button>

          </form>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4">
      <div class="sidebar-panel mt-4 mt-lg-0">
        <h5><i class="fas fa-info-circle text-dark-navy me-2"></i>Quick Information</h5>
        <ul class="list-unstyled ps-3">
          <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>ISSN: 2582-5208</li>
          <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>24/7 Support Available</li>
          <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Response within 6-24 hours</li>
          <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Multi-language Support</li>
        </ul>
      </div>
      
      <div class="sidebar-panel mt-4">
        <h5><i class="fas fa-clock text-dark-navy me-2"></i>Response Time</h5>
        <p class="mb-2"><strong>Paper-related queries:</strong> Within 6 hours</p>
        <p class="mb-0"><strong>General inquiries:</strong> Within 24 hours</p>
      </div>
      
      <div class="sidebar-panel mt-4">
        <h5><i class="fas fa-headset text-dark-navy me-2"></i>Support Channels</h5>
        <div class="support-channels">
          <p class="mb-2"><a href="https://wa.me/917869696794" target="_blank" class="text-decoration-none text-dark"><i class="fab fa-whatsapp text-success me-2"></i>WhatsApp Support</a></p>
          <p class="mb-2"><a href="sms:+917869696794" class="text-decoration-none text-dark"><i class="fas fa-sms text-info me-2"></i>SMS Support</a></p>
          <p class="mb-2"><a href="#" class="text-decoration-none text-dark chatbot-link"><i class="fas fa-comments text-primary me-2"></i>Chatbot Support</a></p>
          <p class="mb-0"><a href="mailto:editor@IJFER.com" class="text-decoration-none text-dark"><i class="fas fa-envelope text-warning me-2"></i>Email Support</a></p>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include __DIR__ . "/components/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const chatbotLink = document.querySelector('.chatbot-link');
  if (chatbotLink) {
    chatbotLink.addEventListener('click', function(e) {
      e.preventDefault();
      alert('Chatbot support will be available soon! For immediate assistance, please use WhatsApp, SMS, or email support.');
    });
  }
});
</script>

</body>
</html>