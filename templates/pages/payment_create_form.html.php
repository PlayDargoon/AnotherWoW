<!-- templates/pages/payment_create_form.html.php -->
<div class="body">
    <h2>Пополнение баланса</h2>
    <div class="section-sep"></div>
    
    <div class="pt" style="text-align:center; margin-bottom: 20px;">
        <div class="block-border">
            <img src="/images/kollekzioner_310_blue.jpg" width="310" height="109" alt="Пополнение баланса">
        </div>
    </div>

    <div class="pt">
        <div class="small">
            <img src="/images/icons/money.png" alt="*" width="16" height="16" style="float:left;margin-right:8px;">
            Пополните баланс бонусов для покупок в игровом магазине.<br/>
            После оплаты бонусы автоматически зачислятся на ваш аккаунт.
        </div>
        <br>

        <div class="pt">
            <form method="post" action="/payment/create">
                <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                    <tr>
                        <td style="padding: 8px; width: 30%; vertical-align: top;">
                            <img src="/images/icons/money.png" width="12" height="12" alt="*"> 
                            <span class="minor">Сумма пополнения:</span>
                        </td>
                        <td style="padding: 8px;">
                            <input 
                                type="number" 
                                step="0.01" 
                                min="1" 
                                name="amount" 
                                value="100.00"
                                style="width: 200px; padding: 8px; background-color: #191948; color: #FFFFFF; border: 1px solid #999999; font-size: medium;"
                            > <span class="minor">RUB</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; vertical-align: top;">
                            <img src="/images/icons/service.png" width="12" height="12" alt="*"> 
                            <span class="minor">Способ оплаты:</span>
                        </td>
                        <td style="padding: 8px;">
                            <span>ЮКасса (банковские карты, электронные кошельки)</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; vertical-align: top;">
                            <img src="/images/icons/info.png" width="12" height="12" alt="*"> 
                            <span class="minor">Курс обмена:</span>
                        </td>
                        <td style="padding: 8px;">
                            <span style="color: #51cf66;">1 RUB = 1 бонус</span>
                        </td>
                    </tr>
                </table>

                <div class="section-sep"></div>
                
                <div class="pt" style="text-align: center;">
                    <button 
                        type="submit" 
                        style="padding: 12px 40px; background-color: #191948; color: #ffff33; border: 1px solid #999999; font-size: medium; cursor: pointer; font-weight: bold;"
                        onmouseover="this.style.backgroundColor='#252560';"
                        onmouseout="this.style.backgroundColor='#191948';"
                    >
                        <img src="/images/icons/money.png" width="16" height="16" alt="*" style="vertical-align: middle; margin-right: 5px;">
                        Перейти к оплате
                    </button>
                </div>
            </form>

            <div class="section-sep"></div>
            
            <div class="pt">
                <p class="minor" style="font-size: small; text-align: center;">
                    <img src="/images/icons/question_blue.png" width="12" height="12" alt="*"> 
                    После успешной оплаты бонусы поступят на ваш счет автоматически в течение нескольких минут.
                </p>
            </div>
        </div>
    </div>
    <div class="section-sep"></div>
</div>

<div class="footer nav block-border-top">
    <ol>
        <li>
            <img class="i12img" src="/images/icons/home.png" alt="." width="12px" height="12px">
            <a href="/">На главную</a>
        </li>
        <li>
            <img src="/images/icons/menialo.png" alt="*" width="12" height="12">
            <a href="/cabinet">В кабинет</a>
        </li>
        <li>
            <img src="/images/icons/shop.png" alt="*" width="12" height="12">
            <a href="/shop">В магазин</a>
        </li>
    </ol>
</div>