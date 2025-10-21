document.addEventListener('DOMContentLoaded', function () {
  // Иконки классов (используем существующие из images/small)
  var classIcons = {
    1: '/images/small/1.gif',   // Воин
    2: '/images/small/2.gif',   // Паладин
    3: '/images/small/3.gif',   // Охотник
    4: '/images/small/4.gif',   // Разбойник
    5: '/images/small/5.gif',   // Жрец
    6: '/images/small/6.gif',   // Рыцарь Смерти
    7: '/images/small/7.gif',   // Шаман
    8: '/images/small/8.gif',   // Маг
    9: '/images/small/9.gif',   // Чернокнижник
    11: '/images/small/11.gif'  // Друид
  };
  
  // Маппинг рас на фракции (Alliance/Horde)
  var raceFactions = {
    1: 'alliance', 2: 'horde', 3: 'alliance', 4: 'alliance',
    5: 'horde', 6: 'horde', 7: 'alliance', 8: 'horde',
    10: 'horde', 11: 'alliance'
  };
  
  var factionIcons = {
    'alliance': '/images/small/alliance.png',
    'horde': '/images/small/orda.png'
  };
  
  // Fallback иконки
  var fallbackClassIcon = '/images/icons/sword_red.png';
  var fallbackFactionIcon = '/images/icons/home.png';
  
  // Функция получения иконки расы (race-gender.gif)
  function getRaceIcon(raceId, gender) {
    // gender: 0 = мужской, 1 = женский
    // Используем мужской по умолчанию (0)
    var g = gender || 0;
    return '/images/small/' + raceId + '-' + g + '.gif';
  }

  function makeModal(chars, onConfirm, onCancel) {
    var overlay = document.createElement('div');
    overlay.style.position = 'fixed';
    overlay.style.left = '0';
    overlay.style.top = '0';
    overlay.style.right = '0';
    overlay.style.bottom = '0';
    overlay.style.background = 'rgba(0,0,0,0.85)';
    overlay.style.backdropFilter = 'blur(4px)';
    overlay.style.zIndex = '9999';
    overlay.style.display = 'flex';
    overlay.style.alignItems = 'center';
    overlay.style.justifyContent = 'center';
    overlay.style.animation = 'fadeIn 0.3s ease';

    var modal = document.createElement('div');
    modal.style.background = 'linear-gradient(135deg, rgba(25, 25, 72, 0.98) 0%, rgba(0, 0, 51, 0.98) 100%)';
    modal.style.border = '2px solid #4a4a70';
    modal.style.borderRadius = '12px';
    modal.style.padding = '25px';
    modal.style.width = '450px';
    modal.style.maxWidth = '90%';
    modal.style.boxShadow = '0 8px 32px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.1)';
    modal.style.position = 'relative';
    modal.style.animation = 'slideIn 0.3s ease';

    var title = document.createElement('h2');
    title.textContent = '✨ Выберите персонажа';
    title.style.color = '#ffff99';
    title.style.marginBottom = '15px';
    title.style.fontSize = '20px';
    title.style.fontWeight = 'bold';
    title.style.textAlign = 'center';
    title.style.textShadow = '0 2px 4px rgba(0, 0, 0, 0.5)';
    title.style.letterSpacing = '0.5px';
    modal.appendChild(title);

    var description = document.createElement('p');
    description.textContent = 'Кому предоставить услугу?';
    description.style.color = '#b0b0d0';
    description.style.fontSize = '14px';
    description.style.marginBottom = '20px';
    description.style.textAlign = 'center';
    modal.appendChild(description);

    // Контейнер для сообщения об ошибке
    var errorMsg = document.createElement('div');
    errorMsg.style.display = 'none';
    errorMsg.style.padding = '12px';
    errorMsg.style.marginBottom = '15px';
    errorMsg.style.background = 'rgba(139, 0, 0, 0.2)';
    errorMsg.style.border = '2px solid #ff3333';
    errorMsg.style.borderRadius = '8px';
    errorMsg.style.color = '#ff6666';
    errorMsg.style.fontSize = '14px';
    errorMsg.style.fontWeight = 'bold';
    errorMsg.style.textAlign = 'center';
    errorMsg.style.boxShadow = '0 0 15px rgba(255, 51, 51, 0.3)';
    modal.appendChild(errorMsg);

    // Создаем кастомный список персонажей вместо select
    var charListContainer = document.createElement('div');
    charListContainer.style.marginBottom = '20px';
    charListContainer.style.maxHeight = '300px';
    charListContainer.style.overflowY = 'auto';
    charListContainer.style.border = '2px solid #5a5a80';
    charListContainer.style.borderRadius = '8px';
    charListContainer.style.background = 'linear-gradient(135deg, rgba(0, 0, 0, 0.4) 0%, rgba(51, 51, 102, 0.3) 100%)';
    
    var selectedGuid = chars.length > 0 ? chars[0].guid : null;
    
    chars.forEach(function (c, index) {
      var charCard = document.createElement('div');
      charCard.className = 'char-select-card';
      charCard.dataset.guid = c.guid;
      charCard.style.display = 'flex';
      charCard.style.alignItems = 'center';
      charCard.style.padding = '12px';
      charCard.style.margin = '2px';
      charCard.style.cursor = 'pointer';
      charCard.style.borderRadius = '6px';
      charCard.style.transition = 'all 0.2s ease';
      charCard.style.gap = '12px';
      
      if (index === 0) {
        charCard.style.background = 'rgba(90, 138, 170, 0.3)';
        charCard.style.border = '2px solid #5a8aaa';
      } else {
        charCard.style.background = 'transparent';
        charCard.style.border = '2px solid transparent';
      }
      
      // Иконка фракции
      var faction = raceFactions[c.race] || 'alliance';
      var factionIcon = document.createElement('img');
      factionIcon.src = factionIcons[faction] || fallbackFactionIcon;
      factionIcon.alt = faction;
      factionIcon.style.width = '24px';
      factionIcon.style.height = '24px';
      factionIcon.style.filter = 'drop-shadow(0 2px 4px rgba(0,0,0,0.5))';
      factionIcon.onerror = function() { this.src = fallbackFactionIcon; };
      charCard.appendChild(factionIcon);
      
      // Иконка расы
      var raceIcon = document.createElement('img');
      raceIcon.src = getRaceIcon(c.race, c.gender);
      raceIcon.alt = 'Race';
      raceIcon.style.width = '32px';
      raceIcon.style.height = '32px';
      raceIcon.style.borderRadius = '4px';
      raceIcon.style.background = 'rgba(0,0,0,0.3)';
      raceIcon.style.padding = '2px';
      raceIcon.style.border = '1px solid #4a4a70';
      raceIcon.onerror = function() { 
        // Если не найден файл с гендером, пробуем без гендера
        this.src = '/images/small/' + c.race + '.gif';
      };
      charCard.appendChild(raceIcon);
      
      // Иконка класса
      var classIcon = document.createElement('img');
      classIcon.src = classIcons[c.class] || fallbackClassIcon;
      classIcon.alt = 'Class';
      classIcon.style.width = '32px';
      classIcon.style.height = '32px';
      classIcon.style.borderRadius = '4px';
      classIcon.style.background = 'rgba(0,0,0,0.3)';
      classIcon.style.padding = '2px';
      classIcon.style.border = '1px solid #4a4a70';
      classIcon.onerror = function() { this.src = fallbackClassIcon; };
      charCard.appendChild(classIcon);
      
      // Текстовая информация
      var charInfo = document.createElement('div');
      charInfo.style.flex = '1';
      
      var charName = document.createElement('div');
      charName.textContent = c.name;
      charName.style.color = '#ffff99';
      charName.style.fontWeight = 'bold';
      charName.style.fontSize = '15px';
      charName.style.textShadow = '0 1px 3px rgba(0,0,0,0.7)';
      charInfo.appendChild(charName);
      
      var charLevel = document.createElement('div');
      charLevel.textContent = 'Уровень ' + c.level;
      charLevel.style.color = '#b0b0d0';
      charLevel.style.fontSize = '12px';
      charInfo.appendChild(charLevel);
      
      charCard.appendChild(charInfo);
      
      // Обработчик выбора
      charCard.addEventListener('click', function() {
        selectedGuid = c.guid;
        document.querySelectorAll('.char-select-card').forEach(function(card) {
          card.style.background = 'transparent';
          card.style.border = '2px solid transparent';
        });
        charCard.style.background = 'rgba(90, 138, 170, 0.3)';
        charCard.style.border = '2px solid #5a8aaa';
        charCard.style.boxShadow = '0 0 10px rgba(90, 138, 170, 0.4)';
      });
      
      charCard.addEventListener('mouseenter', function() {
        if (parseInt(charCard.dataset.guid) !== selectedGuid) {
          charCard.style.background = 'rgba(70, 70, 100, 0.3)';
        }
      });
      
      charCard.addEventListener('mouseleave', function() {
        if (parseInt(charCard.dataset.guid) !== selectedGuid) {
          charCard.style.background = 'transparent';
        }
      });
      
      charListContainer.appendChild(charCard);
    });
    
    modal.appendChild(charListContainer);

    var actions = document.createElement('div');
    actions.style.display = 'flex';
    actions.style.gap = '12px';
    actions.style.justifyContent = 'center';

    var cancelBtn = document.createElement('button');
    cancelBtn.textContent = 'Отмена';
    cancelBtn.style.padding = '10px 24px';
    cancelBtn.style.background = 'linear-gradient(135deg, #4a4a4a 0%, #2a2a2a 100%)';
    cancelBtn.style.border = '2px solid #666666';
    cancelBtn.style.borderRadius = '6px';
    cancelBtn.style.color = '#cccccc';
    cancelBtn.style.fontSize = '14px';
    cancelBtn.style.fontWeight = 'bold';
    cancelBtn.style.cursor = 'pointer';
    cancelBtn.style.textTransform = 'uppercase';
    cancelBtn.style.letterSpacing = '0.5px';
    cancelBtn.style.boxShadow = '0 3px 10px rgba(0, 0, 0, 0.4)';
    cancelBtn.style.transition = 'all 0.3s ease';
    cancelBtn.onmouseover = function() {
      this.style.background = 'linear-gradient(135deg, #5a5a5a 0%, #3a3a3a 100%)';
      this.style.borderColor = '#888888';
      this.style.transform = 'translateY(-2px)';
      this.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.5)';
    };
    cancelBtn.onmouseout = function() {
      this.style.background = 'linear-gradient(135deg, #4a4a4a 0%, #2a2a2a 100%)';
      this.style.borderColor = '#666666';
      this.style.transform = 'translateY(0)';
      this.style.boxShadow = '0 3px 10px rgba(0, 0, 0, 0.4)';
    };

    var okBtn = document.createElement('button');
    okBtn.textContent = '✓ Подтвердить';
    okBtn.style.padding = '10px 24px';
    okBtn.style.background = 'linear-gradient(135deg, #2a5a7a 0%, #1a3a5a 100%)';
    okBtn.style.border = '2px solid #4a7a9a';
    okBtn.style.borderRadius = '6px';
    okBtn.style.color = '#ffff99';
    okBtn.style.fontSize = '14px';
    okBtn.style.fontWeight = 'bold';
    okBtn.style.cursor = 'pointer';
    okBtn.style.textTransform = 'uppercase';
    okBtn.style.letterSpacing = '0.5px';
    okBtn.style.boxShadow = '0 3px 10px rgba(0, 0, 0, 0.4)';
    okBtn.style.transition = 'all 0.3s ease';
    okBtn.onmouseover = function() {
      this.style.background = 'linear-gradient(135deg, #3a6a8a 0%, #2a4a6a 100%)';
      this.style.borderColor = '#5a8aaa';
      this.style.transform = 'translateY(-2px)';
      this.style.boxShadow = '0 5px 15px rgba(90, 138, 170, 0.5)';
    };
    okBtn.onmouseout = function() {
      this.style.background = 'linear-gradient(135deg, #2a5a7a 0%, #1a3a5a 100%)';
      this.style.borderColor = '#4a7a9a';
      this.style.transform = 'translateY(0)';
      this.style.boxShadow = '0 3px 10px rgba(0, 0, 0, 0.4)';
    };

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

    // Функция для показа успешного сообщения
    overlay.showSuccess = function(message) {
      // Скрываем все элементы выбора
      title.style.display = 'none';
      description.style.display = 'none';
      errorMsg.style.display = 'none';
      charListContainer.style.display = 'none';
      actions.style.display = 'none';
      
      // Создаем контейнер для успешного сообщения
      var successContainer = document.createElement('div');
      successContainer.style.textAlign = 'center';
      successContainer.style.padding = '20px 0';
      
      // Иконка успеха
      var successIcon = document.createElement('div');
      successIcon.textContent = '✓';
      successIcon.style.fontSize = '60px';
      successIcon.style.color = '#33cc33';
      successIcon.style.marginBottom = '20px';
      successIcon.style.textShadow = '0 0 20px rgba(51, 204, 51, 0.6)';
      successIcon.style.animation = 'pulse 1s ease';
      successContainer.appendChild(successIcon);
      
      // Заголовок успеха
      var successTitle = document.createElement('h2');
      successTitle.textContent = 'Успешно!';
      successTitle.style.color = '#33cc33';
      successTitle.style.fontSize = '24px';
      successTitle.style.fontWeight = 'bold';
      successTitle.style.marginBottom = '15px';
      successTitle.style.textShadow = '0 2px 4px rgba(0, 0, 0, 0.5)';
      successContainer.appendChild(successTitle);
      
      // Текст сообщения
      var successText = document.createElement('p');
      successText.textContent = message;
      successText.style.color = '#b0b0d0';
      successText.style.fontSize = '16px';
      successText.style.marginBottom = '30px';
      successText.style.lineHeight = '1.5';
      successContainer.appendChild(successText);
      
      // Контейнер для кнопок
      var successActions = document.createElement('div');
      successActions.style.display = 'flex';
      successActions.style.gap = '12px';
      successActions.style.justifyContent = 'center';
      
      // Кнопка "Продолжить покупки"
      var continueBtn = document.createElement('button');
      continueBtn.textContent = '🛒 Продолжить покупки';
      continueBtn.style.padding = '10px 24px';
      continueBtn.style.background = 'linear-gradient(135deg, #2a5a7a 0%, #1a3a5a 100%)';
      continueBtn.style.border = '2px solid #4a7a9a';
      continueBtn.style.borderRadius = '6px';
      continueBtn.style.color = '#ffff99';
      continueBtn.style.fontSize = '14px';
      continueBtn.style.fontWeight = 'bold';
      continueBtn.style.cursor = 'pointer';
      continueBtn.style.textTransform = 'uppercase';
      continueBtn.style.letterSpacing = '0.5px';
      continueBtn.style.boxShadow = '0 3px 10px rgba(0, 0, 0, 0.4)';
      continueBtn.style.transition = 'all 0.3s ease';
      continueBtn.onmouseover = function() {
        this.style.background = 'linear-gradient(135deg, #3a6a8a 0%, #2a4a6a 100%)';
        this.style.borderColor = '#5a8aaa';
        this.style.transform = 'translateY(-2px)';
        this.style.boxShadow = '0 5px 15px rgba(90, 138, 170, 0.5)';
      };
      continueBtn.onmouseout = function() {
        this.style.background = 'linear-gradient(135deg, #2a5a7a 0%, #1a3a5a 100%)';
        this.style.borderColor = '#4a7a9a';
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = '0 3px 10px rgba(0, 0, 0, 0.4)';
      };
      continueBtn.onclick = function() {
        closeOverlay(overlay);
      };
      
      // Кнопка "Закрыть"
      var closeBtn = document.createElement('button');
      closeBtn.textContent = '✕ Закрыть';
      closeBtn.style.padding = '10px 24px';
      closeBtn.style.background = 'linear-gradient(135deg, #4a4a4a 0%, #2a2a2a 100%)';
      closeBtn.style.border = '2px solid #666666';
      closeBtn.style.borderRadius = '6px';
      closeBtn.style.color = '#cccccc';
      closeBtn.style.fontSize = '14px';
      closeBtn.style.fontWeight = 'bold';
      closeBtn.style.cursor = 'pointer';
      closeBtn.style.textTransform = 'uppercase';
      closeBtn.style.letterSpacing = '0.5px';
      closeBtn.style.boxShadow = '0 3px 10px rgba(0, 0, 0, 0.4)';
      closeBtn.style.transition = 'all 0.3s ease';
      closeBtn.onmouseover = function() {
        this.style.background = 'linear-gradient(135deg, #5a5a5a 0%, #3a3a3a 100%)';
        this.style.borderColor = '#888888';
        this.style.transform = 'translateY(-2px)';
        this.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.5)';
      };
      closeBtn.onmouseout = function() {
        this.style.background = 'linear-gradient(135deg, #4a4a4a 0%, #2a2a2a 100%)';
        this.style.borderColor = '#666666';
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = '0 3px 10px rgba(0, 0, 0, 0.4)';
      };
      closeBtn.onclick = function() {
        closeOverlay(overlay);
      };
      
      successActions.appendChild(continueBtn);
      successActions.appendChild(closeBtn);
      successContainer.appendChild(successActions);
      
      // Добавляем контейнер в модальное окно
      modal.appendChild(successContainer);
    };

    okBtn.addEventListener('click', function (e) {
      e.preventDefault();
      overlay.hideError();
      onConfirm && onConfirm(selectedGuid, overlay);
    });
    cancelBtn.addEventListener('click', function (e) {
      e.preventDefault();
      onCancel && onCancel(overlay);
      if (overlay.parentNode) {
        modal.style.animation = 'slideOut 0.3s ease';
        overlay.style.animation = 'fadeOut 0.3s ease';
        setTimeout(function() {
          if (overlay.parentNode) overlay.parentNode.removeChild(overlay);
        }, 300);
      }
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
              // Показываем успешное сообщение в модальном окне
              ov.showSuccess(res.message || 'Покупка успешно совершена!');
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
