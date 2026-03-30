<?php

require_once '../config/config.php';
require_once '../classes/User.php';

// Check if user is logged in
if (!isset($_SESSION['session_login'])) {
    header("Location: " . ROOT_URL . "index.php");
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    $userObj = new User();
    $userObj->logout();
    header("Location: http://localhost/xampp/Online_Barangay_Portal-master/Online_Barangay_Portal-master");
    exit();
}

$userObj = new User();
$documents = $userObj->getAllDocuments();

$pageTitle = 'Documents Management';

?>
<?php include 'includes/header.php'; ?>

<style>
/* Documents Management Styles */
.documents-header {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
    padding: 2.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    text-align: center;
    box-shadow: 0 8px 25px rgba(44, 62, 80, 0.15);
}

.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.document-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.document-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.document-icon {
    font-size: 3rem;
    color: #3498db;
    margin-bottom: 1rem;
    text-align: center;
}

.document-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.document-description {
    color: #7f8c8d;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    line-height: 1.4;
}

.document-date {
    color: #bdc3c7;
    font-size: 0.8rem;
    margin-bottom: 1rem;
}

.document-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-download {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.8rem;
    transition: all 0.3s ease;
}

.btn-delete {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.8rem;
    transition: all 0.3s ease;
}

.btn-download:hover, .btn-delete:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.upload-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #7f8c8d;
}

.empty-state i {
    font-size: 4rem;
    color: #bdc3c7;
    margin-bottom: 1rem;
}
</style>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="documents-header">
                <h1><i class="fas fa-file-alt"></i> Documents Management</h1>
                <p class="lead">Manage and organize barangay documents</p>
            </div>
        </div>
    </div>
    
    <!-- Upload Section -->
    <div class="row">
        <div class="col-12">
            <div class="upload-section">
                <h5><i class="fas fa-upload"></i> Upload New Document</h5>
                <form action="../admin_Actions.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="description_field">Description</label>
                                <input type="text" name="description_field" id="description_field" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="document_date_time_field">Date</label>
                                <input type="datetime-local" name="document_date_time_field" id="document_date_time_field" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="myfile">Select File</label>
                        <input type="file" name="myfile" id="myfile" class="form-control" required>
                    </div>
                    <button type="submit" name="btn_upload" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload Document
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Documents Grid -->
    <div class="row">
        <div class="col-12">
            <?php if (!empty($documents)): ?>
                <div class="documents-grid">
                    <?php foreach ($documents as $doc): ?>
                        <div class="document-card">
                            <div class="document-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="document-title"><?php echo htmlspecialchars($doc['name']); ?></div>
                            <div class="document-description"><?php echo htmlspecialchars($doc['description']); ?></div>
                            <div class="document-date">
                                <i class="fas fa-calendar"></i> 
                                <?php echo date('M d, Y', strtotime($doc['upload_date_time'])); ?>
                            </div>
                            <div class="document-actions">
                                <a href="<?php echo htmlspecialchars($doc['directory'] . $doc['name']); ?>" 
                                   class="btn-download" download>
                                    <i class="fas fa-download"></i> Download
                                </a>
                                <form action="../admin_Actions.php" method="post" style="display: inline;">
                                    <input type="hidden" name="delete_document_id_field" value="<?php echo $doc['id']; ?>">
                                    <input type="hidden" name="delete_document_title_field" value="<?php echo $doc['name']; ?>">
                                    <button type="submit" name="btn_delete_document" class="btn-delete" 
                                            onclick="return confirm('Are you sure you want to delete this document?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <h3>No Documents Found</h3>
                    <p>No documents have been uploaded yet. Upload your first document to get started.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
