document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(e) {
        if (e.target.closest('.hide-notify-btn')) {
            e.preventDefault();
            var btn = e.target.closest('.hide-notify-btn');
            var notifyId = btn.getAttribute('data-id');
            var block = document.getElementById('notify-' + notifyId);
            
            console.log('Hiding notification:', notifyId);
            // Оптимистичное удаление из DOM сразу, чтобы пользователь видел результат
            if (block) {
                block.parentNode && block.parentNode.removeChild(block);
            }
            
            fetch('/notify-hide.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                // Важно: отправляем cookie с запросом, чтобы сервер увидел сессию
                credentials: 'same-origin',
                body: 'id=' + encodeURIComponent(notifyId)
            })
            .then(function(response) { 
                console.log('Response status:', response.status);
                var ct = response.headers.get('content-type') || '';
                if (ct.indexOf('application/json') !== -1) {
                    return response.json();
                }
                return response.text().then(function(t){
                    try { return JSON.parse(t); } catch(e) { return { success: false, raw: t }; }
                });
            })
            .then(function(data) {
                console.log('Response data:', data);
                if (data && data.success) {
                    console.log('Notification removed successfully');
                } else {
                    console.log('Failed to hide notification:', data);
                    // В крайнем случае сообщим пользователю
                    // alert('Не удалось скрыть уведомление. Обновите страницу.');
                }
            })
            .catch(function(error) {
                console.error('Error hiding notification:', error);
            });
        }
    });
});
