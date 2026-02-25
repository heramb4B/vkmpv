<?php
$pageTitle = 'Form Submissions';
require_once 'config/auth.php';
require_once 'config/db.php';
requireAdmin();

$conn = getDBConnection();
$success = '';
$error   = '';

// ── Handle AJAX status update ───────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_action']) && $_POST['ajax_action'] === 'update_status') {
    header('Content-Type: application/json');
    $id     = (int)($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';
    $allowed = ['New', 'Contacted', 'Interested', 'Not Interested'];
    if ($id > 0 && in_array($status, $allowed)) {
        $stmt = $conn->prepare("UPDATE contact_submissions SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $id);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    }
    $conn->close();
    exit();
}

// ── Handle delete ───────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM contact_submissions WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        $success = 'Submission deleted successfully.';
    }
}

// ── Fetch all submissions ───────────────────────────────────────────────
$submissions = $conn->query(
    "SELECT * FROM contact_submissions ORDER BY submitted_at DESC"
)->fetch_all(MYSQLI_ASSOC);

// Count new
$newCount = 0;
foreach ($submissions as $s) {
    if ($s['status'] === 'New') $newCount++;
}

$conn->close();

require_once 'includes/header.php';
?>

<div class="app-layout">
    <?php require_once 'includes/sidebar.php'; ?>
    <main class="main-content">
        <button class="btn btn-outline" id="sidebarToggle" style="display:none; margin-bottom:16px;">
            <i class="fas fa-bars"></i> Menu
        </button>

        <div class="page-header">
            <h1>
                <i class="fas fa-inbox" style="color:var(--saffron); margin-right:10px;"></i>
                Form Submissions
                <?php if ($newCount > 0): ?>
                    <span class="sidebar-badge" style="font-size:13px; padding:2px 10px; border-radius:12px; vertical-align:middle;">
                        <?= $newCount ?> New
                    </span>
                <?php endif; ?>
            </h1>
            <p>All contact form submissions from the website. Update status or delete records below.</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Summary Strip -->
        <div style="display:flex; gap:14px; flex-wrap:wrap; margin-bottom:24px;">
            <?php
            $counts = ['New'=>0,'Contacted'=>0,'Interested'=>0,'Not Interested'=>0];
            foreach ($submissions as $s) {
                if (isset($counts[$s['status']])) $counts[$s['status']]++;
            }
            $icons = ['New'=>'fa-envelope','Contacted'=>'fa-phone-volume','Interested'=>'fa-thumbs-up','Not Interested'=>'fa-thumbs-down'];
            $colors= ['New'=>'#1A5B9B','Contacted'=>'#8B6A0A','Interested'=>'#1A6B3A','Not Interested'=>'#C0392B'];
            foreach ($counts as $label => $cnt):
            ?>
            <div style="display:flex; align-items:center; gap:10px; background:var(--white); border:1px solid var(--border); border-radius:var(--radius); padding:12px 18px; box-shadow:var(--shadow-sm);">
                <i class="fas <?= $icons[$label] ?>" style="color:<?= $colors[$label] ?>; font-size:16px;"></i>
                <span style="font-size:13px; font-weight:700; color:var(--text-dark);"><?= $cnt ?></span>
                <span style="font-size:12px; color:var(--text-light);"><?= $label ?></span>
            </div>
            <?php endforeach; ?>
            <div style="display:flex; align-items:center; gap:10px; background:var(--maroon-deep); border-radius:var(--radius); padding:12px 18px;">
                <i class="fas fa-layer-group" style="color:var(--gold-light); font-size:16px;"></i>
                <span style="font-size:13px; font-weight:700; color:var(--white);"><?= count($submissions) ?></span>
                <span style="font-size:12px; color:rgba(255,255,255,0.65);">Total</span>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-table"></i> All Submissions</h3>
                <span style="font-size:13px; color:var(--text-light);">
                    Status changes are saved automatically.
                </span>
            </div>
            <div class="card-body" style="padding:0;">
                <div class="table-wrapper">
                    <table class="data-table" id="submissionsTable">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Date Submitted</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($submissions)): ?>
                            <tr>
                                <td colspan="10" style="text-align:center; padding:60px 20px; color:var(--text-light);">
                                    <i class="fas fa-inbox" style="font-size:48px; opacity:0.25; display:block; margin-bottom:12px;"></i>
                                    No contact form submissions yet.
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($submissions as $i => $row): ?>
                            <tr id="row-<?= $row['id'] ?>">
                                <td><?= $i + 1 ?></td>
                                <td><code style="font-size:12px; color:var(--maroon);">#<?= $row['id'] ?></code></td>
                                <td>
                                    <strong><?= htmlspecialchars($row['name']) ?></strong>
                                </td>
                                <td>
                                    <a href="mailto:<?= htmlspecialchars($row['email']) ?>"
                                       style="color:var(--saffron); font-size:13px;">
                                        <?= htmlspecialchars($row['email']) ?>
                                    </a>
                                </td>
                                <td style="font-size:13px; color:var(--text-mid);">
                                    <?= $row['phone'] ? htmlspecialchars($row['phone']) : '<span style="color:var(--text-light);">—</span>' ?>
                                </td>
                                <td><strong style="font-size:13px;"><?= htmlspecialchars($row['subject']) ?></strong></td>
                                <td>
                                    <div class="message-preview" title="<?= htmlspecialchars($row['message']) ?>">
                                        <?= htmlspecialchars($row['message']) ?>
                                    </div>
                                    <button class="btn btn-outline" style="font-size:11px; padding:3px 10px; margin-top:5px;"
                                            data-modal="msgModal"
                                            data-name="<?= htmlspecialchars($row['name']) ?>"
                                            data-msg="<?= htmlspecialchars($row['message']) ?>">
                                        <i class="fas fa-expand-alt"></i> View
                                    </button>
                                </td>
                                <td style="font-size:12px; color:var(--text-light); white-space:nowrap;">
                                    <?= date('d M Y', strtotime($row['submitted_at'])) ?><br>
                                    <span style="font-size:11px;"><?= date('h:i A', strtotime($row['submitted_at'])) ?></span>
                                </td>
                                <td>
                                    <select class="status-select status-<?= strtolower(str_replace(' ','-',$row['status'])) ?>"
                                            data-id="<?= $row['id'] ?>"
                                            onchange="updateStatus(this)">
                                        <option value="New" <?= $row['status']==='New'?'selected':'' ?>>New</option>
                                        <option value="Contacted" <?= $row['status']==='Contacted'?'selected':'' ?>>Contacted</option>
                                        <option value="Interested" <?= $row['status']==='Interested'?'selected':'' ?>>Interested</option>
                                        <option value="Not Interested" <?= $row['status']==='Not Interested'?'selected':'' ?>>Not Interested</option>
                                    </select>
                                </td>
                                <td>
                                    <form method="POST" onsubmit="return confirm('Permanently delete this submission?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="btn btn-danger" style="padding:6px 14px; font-size:12px;">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Message Preview Modal -->
<div class="modal-overlay" id="msgModal">
    <div class="modal-box" style="max-width:520px;">
        <h3 class="modal-title" id="msgModalTitle">Message from </h3>
        <p id="msgModalBody" style="font-size:14px; line-height:1.8; color:var(--text-mid); white-space:pre-wrap; background:var(--cream); border-radius:var(--radius); padding:16px; margin-bottom:20px;"></p>
        <div style="text-align:right;">
            <button class="btn btn-outline" onclick="document.getElementById('msgModal').classList.remove('show')">Close</button>
        </div>
    </div>
</div>

<!-- Status saved toast -->
<div id="statusToast" style="
    display:none;
    position:fixed;
    bottom:24px;
    right:24px;
    background:var(--success);
    color:#fff;
    padding:12px 20px;
    border-radius:var(--radius);
    font-size:14px;
    font-weight:700;
    box-shadow:var(--shadow-md);
    z-index:9999;
    align-items:center;
    gap:8px;
">
    <i class="fas fa-check-circle"></i> Status updated
</div>

<?php
$extraJS = <<<'JS'
<script>
// Sidebar mobile toggle
const sidebarToggle = document.getElementById('sidebarToggle');
if (sidebarToggle) {
    function checkWidth() {
        sidebarToggle.style.display = window.innerWidth <= 768 ? 'inline-flex' : 'none';
    }
    checkWidth();
    window.addEventListener('resize', checkWidth);
}

// Status update via AJAX
function updateStatus(select) {
    const id     = select.dataset.id;
    const status = select.value;

    // Update select color class
    select.className = 'status-select status-' + status.toLowerCase().replace(/ /g, '-');

    const fd = new FormData();
    fd.append('ajax_action', 'update_status');
    fd.append('id', id);
    fd.append('status', status);

    fetch('contact-submissions.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const toast = document.getElementById('statusToast');
                toast.style.display = 'flex';
                setTimeout(() => { toast.style.display = 'none'; }, 2500);
            }
        });
}

// Message modal
document.querySelectorAll('[data-modal="msgModal"]').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('msgModalTitle').textContent = 'Message from ' + this.dataset.name;
        document.getElementById('msgModalBody').textContent = this.dataset.msg;
        document.getElementById('msgModal').classList.add('show');
    });
});

document.getElementById('msgModal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('show');
});
</script>
JS;
?>

<?php require_once 'includes/footer.php'; ?>
