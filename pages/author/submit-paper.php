<?php 
require_once("../../admin/config/db.php");

$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $volume = "Volume 6 Issue 6, June 2024";
    $research_area = $_POST['research_area'] ?? '';
    $paper_title   = $_POST['paper_title'] ?? '';
    $abstract      = $_POST['abstract'] ?? '';
    $country       = $_POST['country'] ?? '';

    // ===== Authors =====
    $authors = [];
    if (isset($_POST['author_name'])) {
        for ($i = 0; $i < count($_POST['author_name']); $i++) {
            $authors[] = [
                "name" => $_POST['author_name'][$i],
                "email" => $_POST['author_email'][$i],
                "phone" => $_POST['author_phone'][$i],
                "institution" => $_POST['author_institution'][$i]
            ];
        }
    }
    $authors_json = json_encode($authors);

    // ===== File Upload (Word Only) =====
    if (isset($_FILES['paper_file']) && $_FILES['paper_file']['error'] == 0) {

        $fileExt = strtolower(pathinfo($_FILES['paper_file']['name'], PATHINFO_EXTENSION));
        $allowed = ['doc','docx'];

        if (!in_array($fileExt, $allowed)) {
            die("Only Microsoft Word (.doc, .docx) files are allowed.");
        }

        $allowedMimeTypes = [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        if (!in_array($_FILES['paper_file']['type'], $allowedMimeTypes)) {
            die("Invalid file type detected.");
        }

        $month = strtoupper(date("F"));
        $year  = date("Y");

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM papers 
                               WHERE MONTH(submitted_at)=MONTH(CURRENT_DATE()) 
                               AND YEAR(submitted_at)=YEAR(CURRENT_DATE())");
        $stmt->execute();
        $count = $stmt->fetchColumn();

        $serial = str_pad($count + 1, 3, "0", STR_PAD_LEFT);
        $paper_id = "IJFER_{$month}_{$year}_{$serial}";

        $uploadDir = "../../admin/uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);

        $newFileName = $paper_id . "." . $fileExt;
        $fullPath = $uploadDir . $newFileName;

        if (!move_uploaded_file($_FILES['paper_file']['tmp_name'], $fullPath)) {
            die("File upload failed.");
        }

        $filePathForDB = "admin/uploads/" . $newFileName;

        $stmt = $pdo->prepare("INSERT INTO papers 
            (paper_id, volume, research_area, paper_title, abstract, country, authors, file_name, file_path) 
            VALUES 
            (:paper_id,:volume,:research_area,:paper_title,:abstract,:country,:authors,:file_name,:file_path)");

        $stmt->execute([
            ':paper_id'     => $paper_id,
            ':volume'       => $volume,
            ':research_area'=> $research_area,
            ':paper_title'  => $paper_title,
            ':abstract'     => $abstract,
            ':country'      => $country,
            ':authors'      => $authors_json,
            ':file_name'    => $newFileName,
            ':file_path'    => $filePathForDB
        ]);

        $successMessage = "Paper Submitted Successfully! Your Paper ID: " . $paper_id;

        if (!empty($authors)) {
            $firstAuthor = $authors[0];
            $to = $firstAuthor['email'];
            $subject = "Paper Submission Successful";
            $message = "Dear ".$firstAuthor['name'].",\n\nYour paper titled '".$paper_title."' has been successfully submitted. Your Paper ID is ".$paper_id.".\n\nThank you,\nIJFER Team";
            $headers = "From: no-reply@ijfer.com";
            mail($to, $subject, $message, $headers);

            $firstAuthorPhone = preg_replace('/\D/', '', $firstAuthor['phone']);
            $whatsappMsg = urlencode("Hello ".$firstAuthor['name'].", your paper titled '".$paper_title."' has been successfully submitted. Paper ID: ".$paper_id);
            $whatsappUrl = "https://wa.me/".$firstAuthorPhone."?text=".$whatsappMsg;
            echo "<script>window.open('".$whatsappUrl."', '_blank');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Submit Paper - IJFER</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
  <?php include '../../components/header.php'; ?>
  <div id="navbar-placeholder"></div>

<main class="container my-4">
<div class="main-content">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
          <li class="breadcrumb-item"><a href="../../author.php">Author</a></li>
          <li class="breadcrumb-item active">Submit Paper Online</li>
        </ol>
      </nav>

      <div class="submit-paper-intro">
        <h1>Submit Paper Online</h1>
        <p>Upload your manuscript directly through our secure online portal. Supported formats: DOC, DOCX.</p>
      </div>

<?php if($successMessage): ?>
<div class="alert alert-success">
    <?php echo $successMessage; ?>
</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<!-- Volume + Research -->
<div class="form-grid">
    <div>
        <label>Volume</label>
        <p class="volume-text">Volume 6 Issue 6, June 2024</p>
    </div>

    <div>
        <label>Research Area</label>
        <select name="research_area" required>
            <option value="">Select One</option>
            <option>Computer Science</option>
            <option>Mechanical Engineering</option>
            <option>Electronics</option>
            <option>Civil Engineering</option>
            <option>Electrical Engineering</option>
            <option>Chemical Engineering</option>
            <option>Bio-Technology</option>
            <option>Mathematics</option>
            <option>Physics</option>
            <option>Chemistry</option>
            <option>Management</option>
            <option>Science</option>
            <option>Technology</option>
            <option>Environmental Science</option>
            <option>Medical Science</option>
        </select>
    </div>

    <div class="full-width">
        <label>Paper Title *</label>
        <input type="text" name="paper_title" required>
    </div>

    <div class="full-width">
        <label>Abstract *</label>
        <textarea name="abstract" required></textarea>
    </div>

    <div>
        <label>Country *</label>
        <input type="text" name="country" required>
    </div>
</div>

<!-- Authors -->
<h2 class="mt-4 mb-3">Add Author</h2>

<div id="authorsContainer">
    <div class="author-row d-flex align-items-center gap-2 mb-2 p-2 border rounded">
        <span class="author-sn badge bg-primary">Author 1</span>
        <input type="text" name="author_name[]" placeholder="Author Full Name" required class="form-control">
        <input type="email" name="author_email[]" placeholder="Author Email ID" required class="form-control">
        <input type="tel" name="author_phone[]" placeholder="Contact Number" required class="form-control">
        <input type="text" name="author_institution[]" placeholder="College/Institute Name" required class="form-control">
        <button type="button" class="deleteAuthorBtn btn btn-danger btn-sm ms-auto">Delete</button>
    </div>
</div>

<button type="button" id="addAuthorBtn" class="btn btn-outline-secondary mt-2">
    + Add New Author
</button>

<!-- Upload -->
<h2 class="mt-4 mb-3">Upload Paper</h2>
<input type="file" name="paper_file" accept=".doc,.docx" required>
<small class="text-muted">Upload Only Microsoft Word Files (.doc / .docx)</small>

<div class="checkbox mt-3">
    <input type="checkbox" required> I agree to terms of service and privacy policy
</div>

<div class="form-actions mt-4">
    <button type="submit" class="btn btn-primary me-2">Submit</button>
    <button type="reset" class="btn btn-outline-secondary">Clear</button>
</div>

</form>
</div>
</main>

<script>
function updateAuthorSerials() {
    document.querySelectorAll("#authorsContainer .author-row").forEach((row,index)=>{
        row.querySelector(".author-sn").textContent = "Author " + (index+1);
    });
}

function addAuthorRow() {
    const container = document.getElementById("authorsContainer");
    const newRow = document.createElement("div");
    newRow.classList.add("author-row","d-flex","align-items-center","gap-2","mb-2","p-2","border","rounded");
    newRow.innerHTML = `
        <span class="author-sn badge bg-primary"></span>
        <input type="text" name="author_name[]" placeholder="Author Full Name" required class="form-control">
        <input type="email" name="author_email[]" placeholder="Author Email ID" required class="form-control">
        <input type="tel" name="author_phone[]" placeholder="Contact Number" required class="form-control">
        <input type="text" name="author_institution[]" placeholder="College/Institute Name" required class="form-control">
        <button type="button" class="deleteAuthorBtn btn btn-danger btn-sm ms-auto">Delete</button>
    `;
    container.appendChild(newRow);
    updateAuthorSerials();

    newRow.querySelector(".deleteAuthorBtn").addEventListener("click", function(){
        if(container.children.length>1){ newRow.remove(); updateAuthorSerials(); }
        else alert("At least one author is required.");
    });
}

document.getElementById("addAuthorBtn").addEventListener("click", addAuthorRow);

document.querySelectorAll(".deleteAuthorBtn").forEach(btn=>{
    btn.addEventListener("click",function(){
        const container = document.getElementById("authorsContainer");
        if(container.children.length>1){ btn.parentElement.remove(); updateAuthorSerials(); }
        else alert("At least one author is required.");
    });
});

updateAuthorSerials();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/script.js"></script>

<?php include('../../components/footer.php'); ?>
</body>
</html>