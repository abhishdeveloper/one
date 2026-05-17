<div class="card" style="width: 250px; flex-shrink: 0; align-self: flex-start;">
    <h3 style="margin-bottom: 20px;">Client Menu</h3>
    <ul style="list-style: none; padding: 0;">
        <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/index.php?url=client/index" style="display: block; padding: 10px; background: <?= $current == 'index' ? 'var(--primary-color)' : 'var(--bg-dark)'; ?>; color: <?= $current == 'index' ? 'white' : 'inherit'; ?>; border-radius: 4px;">Dashboard Overview</a></li>
        <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/index.php?url=client/services" style="display: block; padding: 10px; background: <?= $current == 'services' ? 'var(--primary-color)' : 'var(--bg-dark)'; ?>; color: <?= $current == 'services' ? 'white' : 'inherit'; ?>; border-radius: 4px;">My Services</a></li>
        <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/index.php?url=client/buy" style="display: block; padding: 10px; background: <?= $current == 'buy' ? 'var(--primary-color)' : 'var(--bg-dark)'; ?>; color: <?= $current == 'buy' ? 'white' : 'inherit'; ?>; border-radius: 4px;">Buy New Service</a></li>
        <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/index.php?url=client/invoices" style="display: block; padding: 10px; background: <?= $current == 'invoices' ? 'var(--primary-color)' : 'var(--bg-dark)'; ?>; color: <?= $current == 'invoices' ? 'white' : 'inherit'; ?>; border-radius: 4px;">Invoices & Billing</a></li>
        <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/index.php?url=client/tickets" style="display: block; padding: 10px; background: <?= $current == 'tickets' ? 'var(--primary-color)' : 'var(--bg-dark)'; ?>; color: <?= $current == 'tickets' ? 'white' : 'inherit'; ?>; border-radius: 4px;">Support Tickets</a></li>
    </ul>
</div>
