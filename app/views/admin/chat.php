<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section bg-light" style="min-height: 80vh;">
    <div class="container">
        <div style="display: flex; gap: 30px;">
            <?php $current = 'chat'; include 'sidebar.php'; ?>

            <div style="flex-grow: 1;">
                <h2 style="margin-bottom: 20px;">Client Chat Center</h2>
                <div style="display: flex; gap: 20px; height: 500px;">

                    <!-- Client List -->
                    <div class="card" style="width: 250px; overflow-y: auto; padding: 10px;">
                        <h4 style="margin-bottom: 10px; border-bottom: 1px solid var(--border-color); padding-bottom: 5px;">Select Client</h4>
                        <?php if (empty($data['clients'])): ?>
                            <p style="color: var(--text-muted); font-size: 0.9rem;">No clients available.</p>
                        <?php else: ?>
                            <ul style="list-style: none;">
                                <?php foreach($data['clients'] as $c): ?>
                                    <li style="margin-bottom: 5px;">
                                        <button class="btn btn-outline client-select-btn" data-id="<?= $c->id; ?>" style="width: 100%; text-align: left; padding: 8px; border: none; background: var(--bg-dark); color: white;"><?= htmlspecialchars($c->name); ?></button>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>

                    <!-- Chat Window -->
                    <div class="card" style="flex-grow: 1; display: flex; flex-direction: column; padding: 0;">
                        <div id="activeChatHeader" style="padding: 15px; background: var(--bg-dark); border-bottom: 1px solid var(--border-color); font-weight: bold; border-radius: 8px 8px 0 0;">
                            Select a client to start chatting...
                        </div>
                        <div id="adminChatMessages" style="flex-grow: 1; overflow-y: auto; padding: 15px; display: flex; flex-direction: column; gap: 10px; background: var(--bg-card);">
                            <!-- Messages -->
                        </div>
                        <div style="padding: 15px; background: var(--bg-dark); border-top: 1px solid var(--border-color); border-radius: 0 0 8px 8px;">
                            <form id="adminChatForm" style="display: flex; gap: 10px;">
                                <input type="hidden" id="currentClientId" value="0">
                                <input type="text" id="adminChatInput" style="flex-grow: 1;" class="form-control" placeholder="Type message..." disabled required autocomplete="off">
                                <button type="submit" id="adminChatSubmit" class="btn btn-primary" disabled>Send</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('adminChatMessages');
    const chatForm = document.getElementById('adminChatForm');
    const chatInput = document.getElementById('adminChatInput');
    const chatSubmit = document.getElementById('adminChatSubmit');
    const currentClientIdInput = document.getElementById('currentClientId');
    const header = document.getElementById('activeChatHeader');
    const myId = <?= $_SESSION['user_id']; ?>;
    let fetchInterval = null;

    document.querySelectorAll('.client-select-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.client-select-btn').forEach(b => b.style.borderLeft = 'none');
            this.style.borderLeft = '3px solid var(--primary-color)';

            const clientId = this.getAttribute('data-id');
            const clientName = this.innerText;

            currentClientIdInput.value = clientId;
            header.innerText = "Chatting with: " + clientName;

            chatInput.disabled = false;
            chatSubmit.disabled = false;

            if (fetchInterval) clearInterval(fetchInterval);
            fetchAdminMessages();
            fetchInterval = setInterval(fetchAdminMessages, 3000);
        });
    });

    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const msg = chatInput.value.trim();
        const cid = currentClientIdInput.value;
        if (!msg || cid == 0) return;

        chatInput.value = '';
        const formData = new FormData();
        formData.append('message', msg);
        formData.append('client_id', cid);

        fetch('<?= URLROOT; ?>/index.php?url=chat/sendMessage', {
            method: 'POST',
            body: formData
        }).then(res => res.json()).then(data => {
            if(data.success) fetchAdminMessages();
        });
    });

    function fetchAdminMessages() {
        const cid = currentClientIdInput.value;
        if(cid == 0) return;

        fetch('<?= URLROOT; ?>/index.php?url=chat/getMessages&client_id=' + cid)
            .then(res => res.json())
            .then(data => {
                if(data.error) return;
                chatMessages.innerHTML = '';
                data.forEach(m => {
                    const isMe = m.sender_id == myId;
                    const align = isMe ? 'flex-end' : 'flex-start';
                    const bg = isMe ? 'var(--primary-color)' : '#334155';
                    chatMessages.innerHTML += `
                        <div style="align-self: ${align}; background: ${bg}; padding: 8px 12px; border-radius: 8px; max-width: 80%; word-wrap: break-word;">
                            ${m.message}
                        </div>
                    `;
                });
                chatMessages.scrollTop = chatMessages.scrollHeight;
            });
    }
});
</script>

<?php require_once APPROOT . '/app/views/partials/footer.php'; ?>
