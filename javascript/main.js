window.onload = function () {
    console.log("JavaScript Loaded!");

    // Likes
    document.querySelectorAll('.like-icon').forEach(icon => {
        icon.onclick = function (e) {
            e.preventDefault();
            const index = this.getAttribute('data-index');
            likePost(index, this);
        };
    });

    // Reposts
    document.querySelectorAll('.repost-icon').forEach(icon => {
        icon.onclick = function (e) {
            e.preventDefault();
            const index = this.getAttribute('data-index');
            repost(index, this);
        };
    });

    // Comment popup
    const commentTriggers = document.getElementsByClassName('commentTrigger');
    const commentPopup = document.getElementById('commentPopup');
    const closePopup = document.querySelector('.closePopup');
    const commentPostId = document.getElementById('commentPostId');

    Array.from(commentTriggers).forEach(trigger => {
        trigger.onclick = function () {
            const postId = this.getAttribute('data-post-id');
            commentPostId.value = postId;
            commentPopup.style.display = 'flex';
        };
    });

    if (closePopup) {
        closePopup.onclick = () => commentPopup.style.display = 'none';
    }

    // Message popup
    const messageTriggers = document.getElementsByClassName('messageTrigger');
    const messagePopup = document.getElementById('messagePopup');
    const closeMessagePopup = document.getElementById('closeMessagePopup');
    const messageRecipientId = document.getElementById('messageRecipientId');

    Array.from(messageTriggers).forEach(trigger => {
        trigger.onclick = function () {
            const userId = this.getAttribute('data-user-id');
            messageRecipientId.value = userId;
            messagePopup.style.display = 'block';
            window.history.pushState({}, '', 'message.php?user_id=' + userId);
        };
    });

    if (closeMessagePopup) {
        closeMessagePopup.onclick = () => messagePopup.style.display = 'none';
    }

    // click outside
    window.onclick = function (event) {
        if (commentPopup && event.target === commentPopup) {
            commentPopup.style.display = 'none';
        }
        if (messagePopup && event.target === messagePopup) {
            messagePopup.style.display = 'none';
        }
    };

    // closes comment popup
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            commentPopup.style.display = 'none';
        }
    });
};