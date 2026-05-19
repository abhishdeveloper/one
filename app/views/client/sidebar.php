<div class="card" style="width: 250px; flex-shrink: 0; align-self: flex-start;">
    <h3 style="margin-bottom: 20px;">Client Menu</h3>
    <ul style="list-style: none; padding: 0;">
        <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/client/index" style="display: block; padding: 10px; background: <?= $current == 'index' ? 'var(--accent-primary)' : 'var(--bg-main)'; ?>; color: <?= $current == 'index' ? 'white' : 'inherit'; ?>; border-radius: 4px;">Dashboard Overview</a></li>
        <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/client/services" style="display: block; padding: 10px; background: <?= $current == 'services' ? 'var(--accent-primary)' : 'var(--bg-main)'; ?>; color: <?= $current == 'services' ? 'white' : 'inherit'; ?>; border-radius: 4px;">My Services</a></li>
        <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/client/buy" style="display: block; padding: 10px; background: <?= $current == 'buy' ? 'var(--accent-primary)' : 'var(--bg-main)'; ?>; color: <?= $current == 'buy' ? 'white' : 'inherit'; ?>; border-radius: 4px;">Buy New Service</a></li>
        <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/client/invoices" style="display: block; padding: 10px; background: <?= $current == 'invoices' ? 'var(--accent-primary)' : 'var(--bg-main)'; ?>; color: <?= $current == 'invoices' ? 'white' : 'inherit'; ?>; border-radius: 4px;">Invoices & Billing</a></li>
        <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/client/tickets" style="display: block; padding: 10px; background: <?= $current == 'tickets' ? 'var(--accent-primary)' : 'var(--bg-main)'; ?>; color: <?= $current == 'tickets' ? 'white' : 'inherit'; ?>; border-radius: 4px;">Support Tickets</a></li>
    </ul>
</div>
