<?php
require_once "../../admin/config/db.php";

// Fetch all active members
$stmt = $pdo->prepare("
    SELECT * FROM editorial_board
    WHERE status = 'active'
    ORDER BY 
        FIELD(role, 'Editor-in-Chief', 'Associate Editor', 'Editorial Board Member'),
        position_order ASC
");
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editorial Board - IJFER</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/favicon.png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<?php include __DIR__ . "../../../components/header.php"; ?>
<div id="navbar-placeholder"></div>

<main class="container-fluid my-4">
  <div class="main-content editorial-board-page">

    <h1>Editorial Board</h1>
    <p>
      Our Editorial Board comprises distinguished scholars and experts from various 
      engineering and scientific disciplines. The board includes the Editor-in-Chief, 
      Associate Editors, and Board Members who bring extensive academic qualifications, 
      professional affiliations, and expertise to the review process.
    </p>

    <div class="editorial-grid">

      <?php foreach ($members as $member): ?>

        <?php
          $photoPath = "../../admin/uploads/editorial/" . $member['photo'];
          if (empty($member['photo']) || !file_exists($photoPath)) {
              $photoPath = "../../assets/images/default-user.png";
          }
        ?>

        <div class="editorial-card">
          
          <div class="card-image">
            <img src="<?php echo $photoPath; ?>" 
                 alt="<?php echo htmlspecialchars($member['name']); ?>" 
                 class="member-photo">
          </div>

          <div class="card-content">
            <h3 class="member-name">
              <?php echo htmlspecialchars($member['name']); ?>
            </h3>

            <p class="member-designation">
              <?php echo htmlspecialchars($member['designation']); ?>
            </p>

            <span class="expertise-badge primary">
              <?php echo htmlspecialchars($member['expertise']); ?>
            </span>

            <div class="member-description">
              <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>" 
                 class="member-email">
                 <?php echo htmlspecialchars($member['email']); ?>
              </a>
            </div>
          </div>

        </div>

      <?php endforeach; ?>

    </div>

  </div>
</main>

<?php include __DIR__ . "../../../components/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/script.js"></script>

</body>
</html>
