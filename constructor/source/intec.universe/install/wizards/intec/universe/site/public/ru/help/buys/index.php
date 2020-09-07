<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Покупки");

?>
<div class="intec-content">
    <div class="intec-content-wrapper">
        <div style="line-height:1.6;">
            <img class="help-buys-img" src="#SITE_DIR#images/buys.png" style="max-width: 100%;">
            <p>
                <span style="font-size: 18pt;">УВАЖАЕМЫЕ ПОКУПАТЕЛИ!</span>
            </p>
            <p>
                <span style="font-size: 13px;">
                    Для совершения покупки в интернет-магазине вам необходимо:
                </span>
            </p>
            <p>
                <span style="font-size: 13px;">
                    Зарегистрироваться в интернет-магазине.
                    Если вы не хотите регистрироваться, то данный пункт вы можете пропустить.
                </span><br>
                <span style="font-size: 13px;">
                    Поместить интересующий вас товар в «корзину» и оформить заказ.
                </span>
            </p>
            <p>
                <span style="font-size: 18pt;">Выбрать тип доставки:</span>
            </p>
            <div style="padding: 20px; background-color: #e9f6ff; text-align: left;">
                <ul>
                    <li><span style="font-size: 13px;"> самовывоз из магазина;</span></li>
                    <li>
                        <span style="font-size: 13px;"> доставка по г. Челябинск (стоимость доставки см. ниже);</span>
                    </li>
                    <li><span style="font-size: 13px;"> доставка транспортной компанией в другие города.</span></li>
                </ul>
            </div>
            <p>
                <span style="font-size: 18pt;">Выбрать вид оплаты:</span>
            </p>
            <div style="padding: 20px; background-color: #e9f6ff; text-align: left;">
                <ul>
                    <li><span style="font-size: 13px;"> наличные (оплата в магазине при получении товара или оплата курьеру по г. Челябинск);</span>
                    </li>
                    <li><span style="font-size: 13px;">оплата банковской картой (принимаются карты Visa, MasterCard);</span>
                    </li>
                    <li><span style="font-size: 13px;">счет для оплаты </span></li>
                </ul>
            </div>
            <p>
                <span style="font-size: 13px;">
                    Ваш заказ оформлен. Ждите ответа оператора по электронной почте.
                </span>
                При оплате товара банковской картой Вы автоматически перенаправляетесь на сайт системы обработки
                платежей. Далее нажимаете кнопку "Перейти" и заполняете приводимую ниже форму, после чего нажимаете
                "Оплатить". Предварительно, уточните наличие товара на складе у оператора. При получении товара
                методом самовывоза - необходимо личное присутствие держателя карты. При себе иметь документ,
                удостоверяющий личность.&nbsp;
            </p><br>
            <p>
                Если Вы заказываете товар с доставкой, то сумму доставки надо уточнить у оператора. При выборе
                платежной системы "Счет на оплату" с Вами связывается оператор и высылает на указанный электронный
                адрес счет на оплату с учетом доставки товара до вашего города. Товар резервируется за Вами сроком
                до 3-х дней с момента выставления счета. После поступления оплаты на наш р/с, Вам отсылается товар.
                Во избежание недоразумений подтверждайте свою оплату: либо по телефону, либо по электронке
            </p>
        </div>
        <br>
    </div>
</div>
<style>
    @media all and (max-width: 500px) {
        .help-buys-img {
            height: 100px;
        }
    }
    @media all and (max-width: 350px) {
        .help-buys-img {
            height: 90px;
        }
    }
</style>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>