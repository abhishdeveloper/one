<!-- Floating Chat UI -->
<?php if(isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'client'): ?>
<div id="chatWidget" style="position: fixed; bottom: 20px; right: 20px; width: 300px; z-index: 9999;">
    <!-- Chat Button -->
    <div id="chatHeader" style="background: var(--primary-color); color: white; padding: 15px; border-radius: 8px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <span style="font-weight: bold;">💬 Live Support</span>
        <span id="unreadBadge" style="background: #ef4444; color: white; border-radius: 50%; padding: 2px 6px; font-size: 0.7rem; display: none;">0</span>
    </div>

    <!-- Chat Body -->
    <div id="chatBody" style="display: none; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 0 0 8px 8px; height: 350px; flex-direction: column;">
        <div id="chatMessages" style="flex-grow: 1; overflow-y: auto; padding: 15px; display: flex; flex-direction: column; gap: 10px; font-size: 0.9rem;">
            <!-- Messages go here -->
        </div>
        <div style="padding: 10px; border-top: 1px solid var(--border-color); background: var(--bg-dark);">
            <form id="chatForm" style="display: flex; gap: 5px;">
                <input type="text" id="chatInput" style="flex-grow: 1; padding: 8px; border: 1px solid var(--border-color); border-radius: 4px; background: var(--bg-card); color: white;" placeholder="Type a message..." required autocomplete="off">
                <button type="submit" class="btn btn-primary" style="padding: 8px 15px;">Send</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatHeader = document.getElementById('chatHeader');
    const chatBody = document.getElementById('chatBody');
    const chatMessages = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const myId = <?= $_SESSION['user_id']; ?>;

    let chatOpen = false;
    let fetchInterval = null;

    chatHeader.addEventListener('click', () => {
        chatOpen = !chatOpen;
        chatBody.style.display = chatOpen ? 'flex' : 'none';
        chatHeader.style.borderRadius = chatOpen ? '8px 8px 0 0' : '8px';
        if (chatOpen) {
            fetchMessages();
            fetchInterval = setInterval(fetchMessages, 3000); // Polling every 3s
            document.getElementById('unreadBadge').style.display = 'none';
        } else {
            clearInterval(fetchInterval);
        }
    });

    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const msg = chatInput.value.trim();
        if (!msg) return;

        chatInput.value = '';
        const formData = new FormData();
        formData.append('message', msg);

        fetch('<?= URLROOT; ?>/index.php?url=chat/sendMessage', {
            method: 'POST',
            body: formData
        }).then(res => res.json()).then(data => {
            if(data.success) fetchMessages();
        });
    });

    function fetchMessages() {
        fetch('<?= URLROOT; ?>/index.php?url=chat/getMessages')
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
<?php endif; ?>
