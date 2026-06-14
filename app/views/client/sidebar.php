<div class="sidebar-wrapper">
    <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
        <span style="margin-left: 10px; font-weight: bold; font-size: 1.1rem;">Client Menu</span>
    </button>
    <div class="card sidebar" id="clientSidebar">
        <h3 class="sidebar-title">My Account</h3>
        <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 5px;">
                <a href="<?= URLROOT; ?>/client/index" class="sidebar-link <?= $current == 'index' ? 'active' : ''; ?>">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    Dashboard
                </a>
            </li>
            <li style="margin-bottom: 5px;">
                <a href="<?= URLROOT; ?>/client/services" class="sidebar-link <?= $current == 'services' ? 'active' : ''; ?>">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    My Services
                </a>
            </li>
            <li style="margin-bottom: 5px;">
                <a href="<?= URLROOT; ?>/client/buy" class="sidebar-link <?= $current == 'buy' ? 'active' : ''; ?>">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    Order New Service
                </a>
            </li>
            <li style="margin-bottom: 5px;">
                <a href="<?= URLROOT; ?>/client/invoices" class="sidebar-link <?= $current == 'invoices' ? 'active' : ''; ?>">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="M7 15h0M2 9.5h20"></path></svg>
                    Billing & Invoices
                </a>
            </li>
            <li style="margin-bottom: 5px;">
                <a href="<?= URLROOT; ?>/client/tickets" class="sidebar-link <?= $current == 'tickets' ? 'active' : ''; ?>">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    Support Center
                </a>
            </li>
        </ul>
    </div>
</div>
