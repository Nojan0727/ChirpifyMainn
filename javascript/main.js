window.onload = function() {
    let i;
    console.log("JavaScript Loaded!");

    // --- Likes ---
    let likeIcons = document.getElementsByClassName('like-icon');

    // loop for likes
    for (i = 0; i < likeIcons.length; i++) {
        likeIcons[i].onclick = function(event) {
            event.preventDefault();
            let index = this.getAttribute('data-index');
            likePost(index, this);
        };
    }

    // --- for later Reposts ---
    let repostIcons = document.getElementsByClassName('repost-icon');

    // Loop for repost
    for (i = 0; i < repostIcons.length; i++) {
        repostIcons[i].onclick = function(event) {
            event.preventDefault();
            let index = this.getAttribute('data-index');
            repost(index, this);
        };
    }

    // ---Comment Pop up ---
    let commentTriggers = document.getElementsByClassName('commentTrigger');
    let commentPopup = document.getElementById('commentPopup');
    let closePopup = document.getElementById('closePopup');
    let commentPostId = document.getElementById('commentPostId');

    // Loop for each comment
    for (i = 0; i < commentTriggers.length; i++) {
        commentTriggers[i].onclick = function() {
            let postId = this.getAttribute('data-post-id');
            commentPostId.value = postId;
        };
    }

    // pop up close button
    if (closePopup) {
        closePopup.onclick = function() {
            commentPopup.style.display = 'none'; // Hide the comment
        };
    }

    // if user click outside the box it will close the pop up
    if (commentPopup) {
        window.onclick = function(event) {
            if (event.target == commentPopup) {
                commentPopup.style.display = 'none';
            }
        };
    }

    // --- Message---
    let messageTriggers = document.getElementsByClassName('messageTrigger');
    let messagePopup = document.getElementById('messagePopup'); // The message popup element
    let closeMessagePopup = document.getElementById('closeMessagePopup'); // The close button for the message popup
    let messageRecipientId = document.getElementById('messageRecipientId'); // Hidden input to store the user ID

    for (i = 0; i < messageTriggers.length; i++) {
        messageTriggers[i].onclick = function() {
            let userId = this.getAttribute('data-user-id');
            messageRecipientId.value = userId;
            messagePopup.style.display = 'block';
            window.history.pushState({}, '', 'message.php?user_id=' + userId);
            location.reload();
        };
    }

    if (closeMessagePopup) {
        closeMessagePopup.onclick = function() {
            messagePopup.style.display = 'none';
        };
    }

    if (messagePopup) {
        window.onclick = function(event) {
            if (event.target == messagePopup) {
                messagePopup.style.display = 'none';
            }
        };
    }
};

// handle liking post
function likePost(index, icon) {
    // Send a request to the server to like the post
    fetch('update_post.php', {
        method: 'POST', // Use the POST method to send data
        headers: {
            'Content-Type': 'application/json' // Tell the server we're sending JSON data
        },
        body: JSON.stringify({
            index: index,
            action: 'like'
        })
    })
        .then(function(response) {
            return response.json(); // Convert the response to JSON
        })
        .then(function(data) {
            if (data.success) {
                // Update the like count on the page
                let countElement = document.getElementById('like-count-' + index);
                countElement.textContent = data.count;
                // Toggle the 'liked' class to change the icon's appearance
                if (icon.classList.contains('liked')) {
                    icon.classList.remove('liked');
                } else {
                    icon.classList.add('liked');
                }
            } else {
                console.log('Error liking post:', data.error);
                alert('Failed to like the post: ' + data.error);
            }
        })
        .catch(function(error) {
            console.log('Fetch error:', error);
            alert('An error occurred while liking the post.');
        });
}

// reposting a post for later
function repost(index, icon) {
    fetch('create_post.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json' // Tell the server we're sending JSON data
        },
        body: JSON.stringify({
            index: index,
            action: 'repost'
        })
    })
        .then(function(response) {
            return response.json(); // Convert the response to JSON
        })
        .then(function(data) {
            if (data.success) {
                // Update the repost count on the page
                let countElement = document.getElementById('repost-count-' + index);
                countElement.textContent = data.count;
                // Toggle the 'reposted' class to change the icon's appearance
                if (icon.classList.contains('reposted')) {
                    icon.classList.remove('reposted');
                } else {
                    icon.classList.add('reposted');
                }
            } else {
                console.log('Error reposting post:', data.error);
                alert('Failed to repost the post: ' + data.error);
            }
        })
        .catch(function(error) {
            console.log('Fetch error:', error);
            alert('An error occurred while reposting the post.');
        });
}