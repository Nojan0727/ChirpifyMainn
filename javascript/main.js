document.addEventListener('DOMContentLoaded', () => {
    console.log("JavaScript Loaded!");

    // Likes
    document.querySelectorAll('.like-icon').forEach(icon => {
        icon.addEventListener('click', function (e) {
            e.preventDefault();
            const index = this.getAttribute('data-index');
            const form = this.closest('form');
            if (form) {
                form.submit();
            }
        });
    });

    // Reposts
    document.querySelectorAll('.repost-icon').forEach(icon => {
        icon.addEventListener('click', function (e) {
            e.preventDefault();
            console.log('Repost clicked, no functionality implemented');
        });
    });

    // Comment popup
    const commentTriggers = document.querySelectorAll('.commentTrigger');
    const commentPopup = document.getElementById('commentPopup');
    const closePopup = document.querySelector('.closePopup');
    const commentPostId = document.getElementById('commentPostId');
    const commentForm = document.querySelector('.commentForm');

    commentTriggers.forEach(trigger => {
        trigger.addEventListener('click', function (e) {
            e.preventDefault();
            const postId = this.getAttribute('data-post-id');
            if (commentPostId) {
                commentPostId.value = postId;
            }
            if (commentPopup) {
                commentPopup.style.display = 'flex';
            }
        });
    });

    if (closePopup) {
        closePopup.addEventListener('click', () => {
            if (commentPopup) {
                commentPopup.style.display = 'none';
            }
        });
    }

    // Message popup
    const messageTriggers = document.querySelectorAll('.messageTrigger');
    const messagePopup = document.getElementById('messagePopup');
    const closeMessagePopup = document.getElementById('closeMessagePopup');
    const messageRecipientId = document.getElementById('messageRecipientId');

    messageTriggers.forEach(trigger => {
        trigger.addEventListener('click', function () {
            const userId = this.getAttribute('data-user-id');
            if (messageRecipientId) {
                messageRecipientId.value = userId;
            }
            if (messagePopup) {
                messagePopup.style.display = 'block';
                window.history.pushState({}, '', 'message.php?user_id=' + userId);
            }
        });
    });

    if (closeMessagePopup) {
        closeMessagePopup.addEventListener('click', () => {
            if (messagePopup) {
                messagePopup.style.display = 'none';
            }
        });
    }

    // Click outside to close popups
    window.addEventListener('click', function (event) {
        if (commentPopup && event.target === commentPopup) {
            commentPopup.style.display = 'none';
        }
        if (messagePopup && event.target === messagePopup) {
            messagePopup.style.display = 'none';
        }
    });

    // Close comment popup on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && commentPopup) {
            commentPopup.style.display = 'none';
        }
    });
});