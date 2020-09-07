<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
$serverName = $_SERVER["SERVER_NAME"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<head>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<table width="700" align="center" cellpadding="0" cellspacing="0" border="0" style="margin:auto; padding:0"
       bgcolor="#ffffff">
    <tr>
        <td>
            <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff">
                <tr>
                    <td align="left" rowspan="2" width="15">
                        <img src="/img/mail.jpg" alt="" border="0" width="14" height="14"
                             style="padding-top: 2px; padding-right: 5px; border:0; outline:none; text-decoration:none; display:block;">
                    </td>
                    <td align="left" rowspan="2">
                        <a href="mailto:sale@sotbit.ru" value="sale@sotbit.ru"
                           style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 15px; font-weight: bold; text-decoration: none;">sale@sotbit.ru</a>
                    </td>
                    <td rowspan="2" align="center">
                        <a href=""><img src="/img/logo.png" alt="" border="0" width="165" height="45"
                                        style="display:block;text-decoration:none;outline:none;"></a>
                    </td>
                    <td align="right"><img src="/img/phone.jpg" alt="" border="0" width="14" height="14"
                                           style="display:block;text-decoration:none;outline:none;"></td>
                    <td align="right" width="135">
                        <a href="tel:+7 (812) 670-07-40" value="+7 (812) 670-07-40"
                           style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 15px; font-weight: bold; text-decoration: none;">+7
                            (812) 670-07-40</a>
                    </td>
                </tr>
                <tr>
                    <td align="right"><img src="/img/phone.jpg" alt="" border="0" width="14" height="14"
                                           style="border:0; outline:none; text-decoration:none; display:block;"></td>
                    <td align="right">
                        <a href="tel:+7 (499) 647-89-31" value="+7 (499) 647-89-31"
                           style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 15px; font-weight: bold; text-decoration: none;">+7
                            (499) 647-89-31</a>
                    </td>
                </tr>
            </table>
            <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff"
                   style="border-bottom: 1px solid #f2f2f2; border-top: 1px solid #f2f2f2; line-height: 40px; margin-top: 5px;">
                <tr align="center">
                    <?
                    EventMessageThemeCompiler::includeComponent(
                        "bitrix:menu",
                        "top",
                        array("ALLOW_MULTI_SELECT" => "N",
                            "DELAY" => "N",
                            "MAX_LEVEL" => "1",
                            "MENU_CACHE_GET_VARS" => array(""),
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_TYPE" => "N",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "ROOT_MENU_TYPE" => "top",
                            "USE_EXT" => "Y",
                        ),
                        false
                    );
                    ?>
                </tr>
            </table>
            <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff"
                   style="padding-top: 40px;">
                <tr>
                    <td>
                        <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff">
                            <tr>
                                <td>
                                    <h1 style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 22px; font-weight: bold; padding-bottom: 30px;">
                                        Вы кажется что-то забыли?</h1>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 14px; padding-bottom: 25px;">
                                        Приветствуем Вас, Иван Иванов. Мыхотим вам напомнить, что вы забыли в корзине
                                        на сайте нашего магазина. Хотим убедиться, что все в порядке, и это всего-лишь
                                        случайное
                                        недоразумение.</p>
                                    <p style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 14px; padding-bottom: 25px;">
                                        Если вам необходимо помощь при оформление заказа, Вы всегда можете нам позвонить
                                        или написать на почту, и мы окажем Вам необходимую поддержку в тот час, как мы
                                        получим ваше сообщение</p>
                                    <p style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 14px; padding-bottom: 25px;">
                                        Мы дарим Вам купон на 5% скидки, который будет действовать 2 дня, успейте купить
                                        :</p>
                                    <p style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: bold; padding-bottom: 25px;">
                                        Номер купона:</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 14px;">
                                        Колличество позиций: <span style="color: #000000; font-weight: bold;">1</span>
                                    </p>
                                    <p style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 14px;">
                                        Общая стоимость позиций: <span style="color: #000000; font-weight: bold;">24 990 руб.</span>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 40px; padding-bottom: 40px;">
                                    <div style="width: 150px; background-color: #fb0040; line-height: 36px; text-align: center;">
                                        <a href=""
                                           style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 13px; font-weight: bold; text-decoration: none; display: block;">Оформить
                                            заказ</a></div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="border-bottom: 1px solid #f2f2f2;"></div>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff"
                               style="padding-top: 40px;">
                            <tr>
                                <td bgcolor="#f7f7f7" height="40" colspan="4">
                                    <p style="color: #252525; font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: bold; padding-left: 25px;">
                                        4 <span style="color: #8b8b8b; font-weight: normal;">товара на сумму </span>62
                                        790 &#8381;</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 20px; padding-left: 20px; padding-bottom: 20px; border-bottom: 1px solid #f2f2f2; border-left: 1px solid #f2f2f2;">
                                    <div style="border: 1px solid #ececec; width: 105px; height: 105px; display: table;">
                                        <a href=""
                                           style="line-height: 105px; display: table-cell; vertical-align: middle;"><img
                                                    src="/img/watch-product.jpg" alt="" border="0" width="80" height=""
                                                    style="display:block;text-decoration:none;outline:none; margin: auto; vertical-align: middle;"></a>
                                    </div>
                                </td>
                                <td style="border-bottom: 1px solid #f2f2f2;">
                                    <p style="color: #060606; font-family: 'Open Sans', sans-serif; font-size: 15px; font-weight: bold;">
                                        Умные часы Smart Watch X6 Black </p>
                                    <p>
                                        <span style="color: #060606; font-family: 'Open Sans', sans-serif; font-size: 14px; padding-right: 40px;">Цвет: Черный </span>
                                    </p>
                                </td>
                                <td align="center" style="border-bottom: 1px solid #f2f2f2;">
                                    <p style="color: #313131; font-family: 'Open Sans', sans-serif; font-size: 13px; padding-right: 40px;">
                                        1 шт.</p>
                                </td>
                                <td align="center"
                                    style="border-bottom: 1px solid #f2f2f2; border-right: 1px solid #f2f2f2;">
                                    <p style="color: #313131; font-family: 'Open Sans', sans-serif; font-size: 18px; font-weight: bold;">
                                        14 000 &#8381;</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 20px; padding-left: 20px; padding-bottom: 20px; border-bottom: 1px solid #f2f2f2; border-left: 1px solid #f2f2f2;">
                                    <div style="border: 1px solid #ececec; width: 105px; height: 105px; display: table;">
                                        <a href=""
                                           style="line-height: 105px; display: table-cell; vertical-align: middle;"><img
                                                    src="/img/cross-product.jpg" alt="" border="0" width="80" height=""
                                                    style="display:block;text-decoration:none;outline:none; margin: auto; vertical-align: middle;"></a>
                                    </div>
                                </td>
                                <td style="border-bottom: 1px solid #f2f2f2;">
                                    <p style="color: #060606; font-family: 'Open Sans', sans-serif; font-size: 15px; font-weight: bold;">
                                        Nike Air Max 270 Flyknit</p>
                                    <p>
                                        <span style="color: #060606; font-family: 'Open Sans', sans-serif; font-size: 14px; padding-right: 40px;">Цвет: Серый</span><span
                                                style="color: #060606; font-family: 'Open Sans', sans-serif; font-size: 14px;">Размер: 41</span>
                                    </p>
                                </td>
                                <td align="center" style="border-bottom: 1px solid #f2f2f2;">
                                    <p style="color: #313131; font-family: 'Open Sans', sans-serif; font-size: 13px; padding-right: 40px;">
                                        1 шт.</p>
                                </td>
                                <td align="center"
                                    style="border-bottom: 1px solid #f2f2f2; border-right: 1px solid #f2f2f2;">
                                    <p style="color: #313131; font-family: 'Open Sans', sans-serif; font-size: 18px; font-weight: bold;">
                                        14 000 &#8381;</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 35px; padding-bottom: 60px;">
                                    <div style="width: 150px; background-color: #fb0040; line-height: 36px; text-align: center;">
                                        <a href=""
                                           style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 13px; font-weight: bold; text-decoration: none; display: block;">Оформить
                                            заказ</a></div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table width="100%" height="255px" align="center" cellpadding="0" cellspacing="0" border="0"
                   bgcolor="#000000" style="padding-top: 40px; padding-right: 40px; padding-left: 40px;">
                <tr>
                    <td valign="top">
                        <div style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 15px; font-weight: bold; padding-bottom: 25px;">
                            Мы в соц. сетях
                        </div>
                        <table align="left" cellpadding="0" cellspacing="0" border="0" bgcolor="#000000">
                            <tr>
                                <td width="40">
                                    <a href=""><img src="/img/vk.png" alt="" border="0" width="30" height="30"
                                                    style="display:block;text-decoration:none;outline:none;"></a>
                                </td>
                                <td width="40">
                                    <a href=""><img src="/img/tw.png" alt="" border="0" width="30" height="30"
                                                    style="display:block;text-decoration:none;outline:none;"></a>
                                </td>
                                <td width="40">
                                    <a href=""><img src="/img/ok.png" alt="" border="0" width="30" height="30"
                                                    style="display:block;text-decoration:none;outline:none;"></a>
                                </td>
                                <td>
                                    <a href=""><img src="/img/inst.png" alt="" border="0" width="30" height="30"
                                                    style="display:block;text-decoration:none;outline:none;"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td valign="top">
                        <div style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 15px; font-weight: bold; padding-bottom: 25px;">
                            О компании
                        </div>
                        <table align="left" cellpadding="0" cellspacing="0" border="0" bgcolor="#000000">
                            <tr>
                                <td style="padding-bottom: 20px;">
                                    <a href=""
                                       style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 13px; text-decoration: none;">Каталог
                                        товаров</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-bottom: 20px;">
                                    <a href=""
                                       style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 13px; text-decoration: none;">Смотреть
                                        акции</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-bottom: 20px;">
                                    <a href=""
                                       style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 13px; text-decoration: none;">Наши
                                        услуги</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href=""
                                       style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 13px; text-decoration: none;">Новости
                                        компании</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td valign="top" align="right">
                        <div style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 15px; font-weight: bold; padding-bottom: 25px;">
                            Контактная информация
                        </div>
                        <table align="right" cellpadding="0" cellspacing="0" border="0" bgcolor="#000000">
                            <tr align="right">
                                <td style="padding-bottom: 20px;">
                                    <p style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 13px; text-decoration: none;">
                                        Косой переулок, дом 147</p>
                                </td>
                            </tr>
                            <tr align="right">
                                <td style="padding-bottom: 20px;">
                                    <a href="tel:+375297402777"
                                       style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 13px; text-decoration: none;">+375
                                        29 740 27 77</a>
                                </td>
                            </tr>
                            <tr align="right">
                                <td style="padding-bottom: 20px;">
                                    <a href="mailto:sale@sotbit.ru"
                                       style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 13px; text-decoration: none;">sale@sotbit.ru</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>


