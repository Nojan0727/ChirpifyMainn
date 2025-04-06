
    document.addEventListener("DOMContentLoaded", function () {
    console.log("JavaScript Loaded!");

    // Attach event listeners to all like icons
    document.querySelectorAll('.like-icon').forEach(icon => {
    icon.addEventListener('click', function () {
    const index = this.getAttribute('data-index');
    likePost(index, this);
});
});

    // Attach event listeners to all repost icons
    document.querySelectorAll('.repost-icon').forEach(icon => {
    icon.addEventListener('click', function () {
    const index = this.getAttribute('data-index');
    repost(index, this);
});
});
});

    function likePost(index, icon) {
    fetch('update_post.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            index: index,
            action: 'like'
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the like count in the UI
                const countElement = document.getElementById(`like-count-${index}`);
                countElement.textContent = data.count;

                // Toggle the liked class for visual feedback
                icon.classList.toggle('liked');
            } else {
                console.error('Error liking post:', data.error);
                alert('Failed to like the post: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('An error occurred while liking the post.');
        });
}

    function repost(index, icon) {
    fetch('update_post.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            index: index,
            action: 'repost'
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the repost count in the UI
                const countElement = document.getElementById(`repost-count-${index}`);
                countElement.textContent = data.count;

                // Toggle the reposted class for visual feedback
                icon.classList.toggle('reposted');
            } else {
                console.error('Error reposting post:', data.error);
                alert('Failed to repost the post: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('An error occurred while reposting the post.');
        });
}
