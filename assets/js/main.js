// ============================================================
// VKMPV Inventory Management — Main JS
// ============================================================

document.addEventListener('DOMContentLoaded', function () {

    // ── Navbar Toggle (mobile) ──────────────────────────────
    const navToggle = document.getElementById('navToggle');
    const navLinks = document.getElementById('navLinks');
    if (navToggle && navLinks) {
        navToggle.addEventListener('click', () => {
            navLinks.classList.toggle('open');
        });
    }

    // ── Sidebar Toggle (admin mobile) ───────────────────────
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        sidebar?.classList.add('open');
        sidebarOverlay?.classList.add('show');
    }
    function closeSidebar() {
        sidebar?.classList.remove('open');
        sidebarOverlay?.classList.remove('show');
    }

    sidebarToggle?.addEventListener('click', openSidebar);
    sidebarClose?.addEventListener('click', closeSidebar);
    sidebarOverlay?.addEventListener('click', closeSidebar);

    // ── Password Visibility Toggle ──────────────────────────
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.target);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });

    // ── Modal Handling ──────────────────────────────────────
    document.querySelectorAll('[data-modal]').forEach(btn => {
        btn.addEventListener('click', function () {
            const modalId = this.dataset.modal;
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('show');
                // Populate modal if data attrs present
                const itemId = this.dataset.id;
                const itemName = this.dataset.name;
                const action = this.dataset.action; // 'add' or 'reduce'

                if (itemId) modal.querySelector('[name="item_id"]').value = itemId;
                if (action) modal.querySelector('[name="action"]').value = action;
                if (itemName) {
                    const nameEl = modal.querySelector('.modal-item-name');
                    if (nameEl) nameEl.textContent = itemName;
                }
                const actionLabel = modal.querySelector('.modal-action-label');
                if (actionLabel) actionLabel.textContent = action === 'add' ? 'Add' : 'Reduce';
            }
        });
    });

    document.querySelectorAll('.modal-close, .modal-overlay').forEach(el => {
        el.addEventListener('click', function (e) {
            if (e.target === this) {
                this.closest('.modal-overlay')?.classList.remove('show');
                document.querySelectorAll('.modal-overlay').forEach(m => {
                    if (e.target === m) m.classList.remove('show');
                });
            }
        });
    });

    // Close modals on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function (e) {
            if (e.target === this) this.classList.remove('show');
        });
    });

    // ── Auto-dismiss alerts ─────────────────────────────────
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });

    // ── Language Filter ─────────────────────────────────────
    const langFilter = document.getElementById('langFilter');
    if (langFilter) {
        langFilter.addEventListener('change', function () {
            const rows = document.querySelectorAll('tbody tr[data-lang]');
            const selected = this.value;
            rows.forEach(row => {
                if (selected === 'all' || row.dataset.lang === selected) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // ── Confirm delete ──────────────────────────────────────
    document.querySelectorAll('.confirm-delete').forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
});
