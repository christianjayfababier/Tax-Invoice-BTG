<?php
session_start();
require_once '../config.php';

// Ensure the user is logged in and is a staff member
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit;
}

// Initialize variables
$error = '';
$success = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form inputs
    $tax_invoice_number = $_POST['tax_invoice_number'] ?? '';
    $invoice_type = $_POST['invoice_type'] ?? '';
    $priority = $_POST['priority'] ?? 'Medium';
    $notes = $_POST['notes'] ?? '';

    // Validate required fields
    if (empty($tax_invoice_number) || empty($invoice_type)) {
        $error = "Tax Invoice Number and Invoice Type are required.";
    } else {
        // File upload logic
        $pdf_invoice_path = null;

        if (isset($_FILES['pdf_invoice']) && $_FILES['pdf_invoice']['error'] == UPLOAD_ERR_OK) {
            $upload_dir = '../dist/invoices/';
            $file_name = time() . '_' . basename($_FILES['pdf_invoice']['name']); // Add timestamp to avoid duplicates
            $target_path = $upload_dir . $file_name;
        
            if (move_uploaded_file($_FILES['pdf_invoice']['tmp_name'], $target_path)) {
                $pdf_invoice_path = $file_name; // Save the file name for the database
            } else {
                $error = "Failed to upload the PDF. Please try again.";
            }
        }
        

        // If no errors, insert into the database
        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO invoice_list (tax_invoice_number, invoice_type, priority, notes, pdf_invoice_path, date_requested) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssss", $tax_invoice_number, $invoice_type, $priority, $notes, $pdf_invoice_path);

            if ($stmt->execute()) {
                $success = "Invoice submitted successfully.";
            } else {
                $error = "Failed to submit the invoice. Please try again.";
            }
        }
    }
}
?>

<?php include '../includes/head.php'; ?>
<?php include '../includes/header.php'; ?>

<div class="content">
    <div class="container mt-4">
        <h1>Upload Invoice</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="taxInvoiceNumber" class="form-label">Tax Invoice Number</label>
                <input type="text" name="tax_invoice_number" class="form-control" id="taxInvoiceNumber" required>
            </div>

            <div class="mb-3">
                <label for="invoiceType" class="form-label">Invoice Type</label>
                <input type="text" name="invoice_type" class="form-control" id="invoiceType" required>
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label">Priority</label>
                <select name="priority" id="priority" class="form-select">
                    <option value="Low">Low</option>
                    <option value="Medium" selected>Medium</option>
                    <option value="High">High</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" id="notes" class="form-control" rows="5"></textarea>
            </div>

            <div class="mb-3">
                <label for="pdfInvoice" class="form-label">Upload PDF Invoice</label>
                <input type="file" name="pdf_invoice" class="form-control" id="pdfInvoice" accept="application/pdf">
            </div>

            <button type="submit" class="btn btn-primary">Submit Invoice</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
