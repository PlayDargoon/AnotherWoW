document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(e) {
        if (e.target.closest('.hide-notify-btn')) {
            e.preventDefault();
            var btn = e.target.closest('.hide-notify-btn');
            var notifyId = btn.getAttribute('data-id');
            fetch('/notify-hide.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'id=' + encodeURIComponent(notifyId)
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    var block = document.getElementById('notify-' + notifyId);
                    if (block) block.remove();
                }
            });
        }
    });
});
