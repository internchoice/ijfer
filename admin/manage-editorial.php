<?php
require_once "config/db.php";

// Set current page for sidebar
$currentPage = 'manage-editorial.php';

$uploadDir = "uploads/editorial/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Calculate statistics early
$stmt = $pdo->query("SELECT * FROM editorial_board ORDER BY role, position_order ASC");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
$activeCount = count(array_filter($members, function($m) { return $m['status'] == 'active'; }));
$editorsCount = count(array_filter($members, function($m) { return $m['role'] == 'Editor-in-Chief'; }));

/* ================= CREATE TABLE ================= */
$pdo->exec("
CREATE TABLE IF NOT EXISTS editorial_board (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    role ENUM('Editor-in-Chief','Associate Editor','Editorial Board Member') NOT NULL,
    designation VARCHAR(255) NOT NULL,
    expertise VARCHAR(255) NOT NULL,
    email VARCHAR(150) NOT NULL,
    photo VARCHAR(255),
    position_order INT DEFAULT 0,
    status VARCHAR(20) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
");

/* ================= DELETE ================= */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $stmt = $pdo->prepare("SELECT photo FROM editorial_board WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && !empty($row['photo']) && file_exists($uploadDir . $row['photo'])) {
        unlink($uploadDir . $row['photo']);
    }

    $pdo->prepare("DELETE FROM editorial_board WHERE id=?")->execute([$id]);
    header("Location: manage-editorial.php");
    exit;
}

/* ================= LOAD EDIT DATA ================= */
$editData = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $pdo->prepare("SELECT * FROM editorial_board WHERE id=?");
    $stmt->execute([$id]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}

/* ================= INSERT / UPDATE ================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = $_POST['id'] ?? '';
    $name = $_POST['name'];
    $role = $_POST['role'];
    $designation = $_POST['designation'];
    $expertise = $_POST['expertise'];
    $email = $_POST['email'];
    $position_order = $_POST['position_order'];
    $status = $_POST['status'];

    $photoName = $editData['photo'] ?? null;

    if (!empty($_FILES['photo']['name'])) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photoName = time() . "." . $ext;
        move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photoName);
    }

    if ($id) {
        $stmt = $pdo->prepare("UPDATE editorial_board 
            SET name=?, role=?, designation=?, expertise=?, email=?, photo=?, position_order=?, status=? 
            WHERE id=?");
        $stmt->execute([$name,$role,$designation,$expertise,$email,$photoName,$position_order,$status,$id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO editorial_board 
            (name, role, designation, expertise, email, photo, position_order, status)
            VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$name,$role,$designation,$expertise,$email,$photoName,$position_order,$status]);
    }

    header("Location: manage-editorial.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Editorial Board - IJFER Admin</title>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/favicon.png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Professional Admin CSS -->
    <link rel="stylesheet" href="assets/css/admin-professional.css">
</head>
<body>
    <?php include 'components/sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-users me-3"></i>
                Editorial Board Management
            </h1>
            <p class="page-subtitle">Add, edit, and manage editorial board members</p>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card editorial-form">
                    <div class="card-header">
                        <h4><i class="fas fa-user-plus me-2"></i><?php echo isset($editData) ? 'Edit Member' : 'Add New Member'; ?></h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" class="professional-form">
                            <input type="hidden" name="id" value="<?php echo $editData['id'] ?? ''; ?>">
                            
                            <div class="row g-4">
                                <!-- Name and Email Row -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Full Name <span class="required">*</span></label>
                                        <input type="text" name="name" class="form-control" 
                                               placeholder="Enter the member's full name"
                                               value="<?php echo $editData['name'] ?? ''; ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email Address <span class="required">*</span></label>
                                        <input type="email" name="email" class="form-control"
                                               placeholder="Enter Email"
                                               value="<?php echo $editData['email'] ?? ''; ?>" required>
                                    </div>
                                </div>
                                
                                <!-- Role and Designation Row -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Role <span class="required">*</span></label>
                                        <select name="role" class="form-control" required>
                                            <option value="">Select the member's role</option>
                                            <?php
                                            $roles = ['Editor-in-Chief','Associate Editor','Editorial Board Member'];
                                            foreach ($roles as $r) {
                                                $selected = (isset($editData['role']) && $editData['role']==$r) ? 'selected' : '';
                                                echo "<option value='$r' $selected>$r</option>";
                                            }
                                            ?>
                                        </select>
                                        <div class="form-help">Select the member's role</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Designation <span class="required">*</span></label>
                                        <input type="text" name="designation" class="form-control"
                                               placeholder="Academic or professional title"
                                               value="<?php echo $editData['designation'] ?? ''; ?>" required>
                                    </div>
                                </div>
                                
                                <!-- Full Width Expertise -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Area of Expertise <span class="required">*</span></label>
                                        <input type="text" name="expertise" class="form-control"
                                               value="<?php echo $editData['expertise'] ?? ''; ?>" required>
                                    </div>
                                </div>
                                
                                <!-- Order and Status Row -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Display Order</label>
                                        <input type="number" name="position_order" class="form-control"
                                               placeholder="Lower numbers appear first"
                                               value="<?php echo $editData['position_order'] ?? 0; ?>" min="0">
                                       
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="active" <?php echo (isset($editData['status']) && $editData['status']=='active')?'selected':''; ?>>Active</option>
                                            <option value="inactive" <?php echo (isset($editData['status']) && $editData['status']=='inactive')?'selected':''; ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Photo Upload -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Professional Photo</label>
                                        <input type="file" name="photo" class="form-control" accept="image/*">
                                        <?php if (!empty($editData['photo'])): ?>
                                            <div class="current-photo mt-3">
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo $uploadDir.$editData['photo']; ?>" width="80" class="rounded border me-3">
                                                    <div>
                                                        <strong>Current Photo</strong>
                                                        <p class="text-muted mb-0">Upload a new photo to replace this one</p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-actions mt-4 pt-3 border-top">
                                <div class="d-flex gap-3">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i><?php echo isset($editData) ? 'Update Member' : 'Add Member'; ?>
                                    </button>
                                    <?php if (isset($editData)): ?>
                                        <a href="manage-editorial.php" class="btn btn-outline-secondary btn-lg">
                                            <i class="fas fa-times me-2"></i>Cancel Edit
                                        </a>
                                    <?php endif; ?>
                                    <a href="manage-editorial.php" class="btn btn-outline-primary btn-lg ms-auto">
                                        <i class="fas fa-list me-2"></i>View All Members
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Members Table -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-list me-2"></i>All Editorial Board Members</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Designation</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($members) > 0): ?>
                                <?php foreach ($members as $m): ?>
                                    <tr>
                                        <td>#<?php echo $m['id']; ?></td>
                                        <td>
                                            <?php if (!empty($m['photo'])): ?>
                                                <img src="<?php echo $uploadDir.$m['photo']; ?>" width="50" class="rounded border">
                                            <?php else: ?>
                                                <div class="bg-light rounded border d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($m['name']); ?></td>
                                        <td>
                                            <span class="badge badge-secondary"><?php echo $m['role']; ?></span>
                                        </td>
                                        <td><?php echo htmlspecialchars($m['designation']); ?></td>
                                        <td><?php echo htmlspecialchars($m['email']); ?></td>
                                        <td>
                                            <?php if ($m['status'] == 'active'): ?>
                                                <span class="badge badge-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $m['position_order']; ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="?edit=<?php echo $m['id']; ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?delete=<?php echo $m['id']; ?>" class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Are you sure you want to delete this member?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No Members Found</h5>
                                        <p class="text-muted">Add your first editorial board member using the form above.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
