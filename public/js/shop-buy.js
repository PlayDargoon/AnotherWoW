document.addEventListener('DOMContentLoaded', function () {
  function makeModal(chars, onConfirm, onCancel) {
    var overlay = document.createElement('div');
    overlay.style.position = 'fixed';
    overlay.style.left = '0';
    overlay.style.top = '0';
    overlay.style.right = '0';
    overlay.style.bottom = '0';
    overlay.style.background = 'rgba(0,0,0,0.8)';
    overlay.style.zIndex = '9999';
    overlay.style.display = 'flex';
    overlay.style.alignItems = 'center';
    overlay.style.justifyContent = 'center';

    var modal = document.createElement('div');
    modal.style.background = '#333333';
    modal.style.border = '1px solid #999999';
    modal.style.padding = '20px';
    modal.style.width = '400px';
    modal.style.maxWidth = '90%';
    modal.style.boxShadow = '0 4px 6px rgba(0,0,0,0.3)';

    var title = document.createElement('h2');
    title.textContent = 'Выберите персонажа';
    title.style.color = '#ff6600';
    title.style.marginBottom = '12px';
    title.style.fontSize = 'medium';
    modal.appendChild(title);

    var description = document.createElement('p');
    description.textContent = 'Кому предоставить услугу?';
    description.style.color = '#999999';
    description.style.fontSize = 'small';
    description.style.marginBottom = '12px';
    modal.appendChild(description);

    // Контейнер для сообщения об ошибке
    var errorMsg = document.createElement('div');
    errorMsg.style.display = 'none';
    errorMsg.style.padding = '10px';
    errorMsg.style.marginBottom = '12px';
    errorMsg.style.backgroundColor = '#191948';
    errorMsg.style.border = '1px solid #ff0000';
    errorMsg.style.color = '#ff0000';
    errorMsg.style.fontSize = 'small';
    modal.appendChild(errorMsg);

    var select = document.createElement('select');
    select.style.width = '100%';
    select.style.padding = '8px';
    select.style.marginBottom = '16px';
    select.style.backgroundColor = '#191948';
    select.style.color = '#FFFFFF';
    select.style.border = '1px solid #999999';
    select.style.fontSize = 'medium';
    chars.forEach(function (c) {
      var opt = document.createElement('option');
      opt.value = c.guid;
      opt.textContent = c.name + ' (уровень ' + c.level + ')';
      select.appendChild(opt);
    });
    modal.appendChild(select);

    var actions = document.createElement('div');
    actions.style.display = 'flex';
    actions.style.gap = '10px';
    actions.style.justifyContent = 'flex-end';

    var cancelBtn = document.createElement('a');
    cancelBtn.href = '#';
    cancelBtn.textContent = 'Отмена';
    cancelBtn.style.color = '#ffff33';
    cancelBtn.style.textDecoration = 'underline';
    cancelBtn.style.padding = '8px 16px';
    cancelBtn.style.cursor = 'pointer';

    var okBtn = document.createElement('a');
    okBtn.href = '#';
    okBtn.textContent = 'Подтвердить';
    okBtn.style.color = '#ffff33';
    okBtn.style.textDecoration = 'underline';
    okBtn.style.padding = '8px 16px';
    okBtn.style.cursor = 'pointer';
    okBtn.style.fontWeight = 'bold';

    actions.appendChild(cancelBtn);
    actions.appendChild(okBtn);
    modal.appendChild(actions);

    overlay.appendChild(modal);
    document.body.appendChild(overlay);

    // Функция для показа ошибки в модальном окне
    overlay.showError = function(message, topupUrl) {
      errorMsg.style.display = 'block';
      errorMsg.innerHTML = message;
      if (topupUrl) {
        var link = document.createElement('a');
        link.href = topupUrl;
        link.textContent = ' Пополнить баланс';
        link.style.color = '#ffff33';
        link.style.textDecoration = 'underline';
        link.style.marginLeft = '5px';
        errorMsg.appendChild(link);
      }
    };

    overlay.hideError = function() {
      errorMsg.style.display = 'none';
      errorMsg.innerHTML = '';
    };

    okBtn.addEventListener('click', function (e) {
      e.preventDefault();
      overlay.hideError();
      onConfirm && onConfirm(parseInt(select.value, 10), overlay);
    });
    cancelBtn.addEventListener('click', function (e) {
      e.preventDefault();
      onCancel && onCancel(overlay);
      if (overlay.parentNode) overlay.parentNode.removeChild(overlay);
    });

    return overlay;
  }

  function closeOverlay(overlay) {
    if (overlay && overlay.parentNode) overlay.parentNode.removeChild(overlay);
  }

  function getChars() {
    return fetch('/cabinet/characters.json', { credentials: 'same-origin' })
      .then(function (r) {
        if (!r.ok) throw new Error('auth_or_network');
        return r.json();
      })
      .then(function (j) {
        if (!j || !Array.isArray(j.characters)) throw new Error('bad_json');
        return j.characters;
      });
  }

  function buy(itemId, price, guid) {
    var fd = new FormData();
    fd.append('item_id', itemId);
    fd.append('price', price);
    fd.append('char_guid', guid);
    return fetch('/shop/buy', { method: 'POST', body: fd, credentials: 'same-origin' })
      .then(function (r) { return r.json(); });
  }

  document.querySelectorAll('.shop-buy-btn').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      var itemId = btn.getAttribute('data-item-id');
      var price = parseInt(btn.getAttribute('data-price'), 10);

      getChars().then(function (chars) {
        if (!chars.length) { alert('У вас нет персонажей.'); return; }
        var overlay = makeModal(chars, function (guid, ov) {
          buy(itemId, price, guid).then(function (res) {
            if (res && res.ok) {
              alert(res.message || 'Покупка успешно совершена.');
              closeOverlay(ov);
            } else if (res && res.need_topup) {
              // Показываем ошибку прямо в модальном окне с ссылкой на пополнение
              ov.showError(
                res.error || 'Недостаточно бонусов для покупки.',
                res.topup_url || '/payment/create'
              );
            } else {
              // Показываем ошибку в модальном окне без ссылки
              ov.showError((res && res.error) ? res.error : 'Ошибка при покупке.');
            }
          }).catch(function () {
            ov.showError('Ошибка сети при покупке. Попробуйте позже.');
          });
        }, function () { /* cancel */ });
      }).catch(function (err) {
        if (String(err) === 'Error: auth_or_network') {
          alert('Необходимо войти в аккаунт.');
        } else {
          alert('Не удалось загрузить список персонажей');
        }
      });
    });
  });
});
