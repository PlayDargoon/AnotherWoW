document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(e) {
        if (e.target.closest('.hide-notify-btn')) {
            e.preventDefault();
            var btn = e.target.closest('.hide-notify-btn');
            var notifyId = btn.getAttribute('data-id');
            
            console.log('Hiding notification:', notifyId);
            
            fetch('/notify-hide.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'id=' + encodeURIComponent(notifyId)
            })
            .then(function(response) { 
                console.log('Response status:', response.status);
                return response.json(); 
            })
            .then(function(data) {
                console.log('Response data:', data);
                if (data.success) {
                    var block = document.getElementById('notify-' + notifyId);
                    if (block) {
                        block.remove();
                        console.log('Notification removed successfully');
                    } else {
                        console.log('Block not found:', 'notify-' + notifyId);
                    }
                } else {
                    console.log('Failed to hide notification:', data);
                }
            })
            .catch(function(error) {
                console.error('Error hiding notification:', error);
            });
        }
    });
});
