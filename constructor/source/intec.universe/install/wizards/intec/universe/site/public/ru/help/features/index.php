<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php') ?>
<?php

$APPLICATION->SetTitle('Возможности');

$arRounds = [
    'none',
    '1',
    '2',
    '3',
    '4',
    '5',
    'half'
];

$arSizes = [
    '1',
    '2',
    '3',
    '4',
    '5'
];

$arSchemes = [
    '',
    'current',
    'blue',
    'blue-1',
    'red',
    'green',
    'green-1',
    'orange',
    'gray'
];

?>
<div class="intec-content">
    <div class="intec-content-wrapper">
        <h3 class="intec-ui-m-t-none">Типография</h3>
        <div>
            <p>Стандартизированная типография на всем сайте. Далее приведены все стандарты типографии шаблона.</p>
            <h1>Заголовок H1 (<code>h1, .intec-ui-markup-h1, [data-ui-markup="h1"]</code>)</h1>
            <p>
                <a href="#">Интернет-магазин</a> (англ. <a href="#">online shop</a> или <a href="#">e-shop</a>) — сайт, торгующий товарами посредством сети Интернет. Позволяет пользователям онлайн, в своём браузере или через мобильное приложение, сформировать заказ на покупку, выбрать способ оплаты и доставки заказа, оплатить заказ.
                <br />
                (<code>p, .intec-ui-markup-p, [data-ui-markup="p"]</code>)
            </p>
            <blockquote>
                При этом продажа товаров осуществляется дистанционным способом и она накладывает ограничения на продаваемые товары.
                <br />
                (<code>blockquote, .intec-ui-markup-blockquote, [data-ui-markup="blockquote"]</code>)
            </blockquote>
            <h2>Заголовок H2 (<code>h2, .intec-ui-markup-h2, [data-ui-markup="h2"]</code>)</h2>
            <p>
                <i>
                    Когда онлайн-магазин настроен на то, чтобы позволить компаниям покупать у других компаний, этот процесс называется онлайн-магазинами бизнес для бизнеса (B2B).
                    <br />
                    (<code>i, .intec-ui-markup-i, [data-ui-markup="i"]</code>)
                </i>
            </p>
            <p>
                <b>
                    Когда онлайн-магазин настроен на то, чтобы позволить компаниям покупать у других компаний, этот процесс называется онлайн-магазинами бизнес для бизнеса (B2B).
                    <br />
                    (<code>b, .intec-ui-markup-b, [data-ui-markup="b"]</code>)
                </b>
            </p>
            <h3>Заголовок H3 (<code>h3, .intec-ui-markup-h3, [data-ui-markup="h3"]</code>)</h3>
            <ul>
                <li>Пункт обычного списка</li>
                <li>Пункт обычного списка</li>
                <li>Пункт обычного списка</li>
                <li>Пункт обычного списка</li>
                <li>(<code>ul li, .intec-ui-markup-ul .intec-ui-markup-li, [data-ui-markup="ul"] [data-ui-markup="li"]</code>)</li>
            </ul>
            <h4>Заголовок H4 (<code>h4, .intec-ui-markup-h4, [data-ui-markup="h4"]</code>)</h4>
            <ol>
                <li>Пункт нумерованного списка</li>
                <li>Пункт нумерованного списка</li>
                <li>Пункт нумерованного списка</li>
                <li>Пункт нумерованного списка</li>
                <li>(<code>ol li, .intec-ui-markup-ol .intec-ui-markup-li, [data-ui-markup="ol"] [data-ui-markup="li"]</code>)</li>
            </ol>
            <h5>Заголовок H5 (<code>h5, .intec-ui-markup-h5, [data-ui-markup="h5"]</code>)</h5>
            <p>
                <a href="#">Ссылка 1</a>,
                <a href="#">Ссылка 2</a>,
                <a href="#">Ссылка 3</a>
                (<code>a, .intec-ui-markup-a, [data-ui-markup="a"]</code>)
            </p>
            <h6>Заголовок H6 (<code>h6, .intec-ui-markup-h6, [data-ui-markup="h6"]</code>)</h6>
            <p>
                Горизонтальная линия (<code>hr, .intec-ui-markup-hr, [data-ui-markup="hr"]</code>)
            </p>
            <hr />
            <p>
                Код (<code>code, .intec-ui-markup-code, [data-ui-markup="code"]</code>)
                <br />
                <code>
                    &lt;div class="intec-ui-markup-code"&gt;<br />Пример оформления кода<br />&lt;/div&gt;
                </code>
            </p>
            <div class="intec-ui-m-b-20">
                <table class="intec-ui-markup-table">
                    <thead>
                        <tr>
                            <th>Заголовок 1</th>
                            <th>Заголовок 2</th>
                            <th>Заголовок 3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Столбец 1</td>
                            <td>Столбец 2</td>
                            <td>Столбец 3</td>
                        </tr>
                        <tr>
                            <td>Столбец 1</td>
                            <td>Столбец 2</td>
                            <td>Столбец 3</td>
                        </tr>
                        <tr>
                            <td>Столбец 1</td>
                            <td>Столбец 2</td>
                            <td>Столбец 3</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="intec-ui-m-b-20">
                <table class="intec-ui-markup-table intec-ui-mod-block">
                    <thead>
                    <tr>
                        <th>Заголовок 1</th>
                        <th>Заголовок 2</th>
                        <th>Заголовок 3</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Столбец 1</td>
                        <td>Столбец 2</td>
                        <td>Столбец 3</td>
                    </tr>
                    <tr>
                        <td>Столбец 1</td>
                        <td>Столбец 2</td>
                        <td>Столбец 3</td>
                    </tr>
                    <tr>
                        <td>Столбец 1</td>
                        <td>Столбец 2</td>
                        <td>Столбец 3</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="intec-ui-m-b-20">
                <div class="intec-ui-markup-table-responsive">
                    <table class="intec-ui-markup-table">
                        <thead>
                        <tr>
                            <th>Заголовок 1</th>
                            <th>Заголовок 2</th>
                            <th>Заголовок 3</th>
                            <th>Заголовок 4</th>
                            <th>Заголовок 5</th>
                            <th>Заголовок 6</th>
                            <th>Заголовок 7</th>
                            <th>Заголовок 8</th>
                            <th>Заголовок 9</th>
                            <th>Заголовок 10</th>
                            <th>Заголовок 11</th>
                            <th>Заголовок 12</th>
                            <th>Заголовок 13</th>
                            <th>Заголовок 14</th>
                            <th>Заголовок 15</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Столбец 1</td>
                            <td>Столбец 2</td>
                            <td>Столбец 3</td>
                            <td>Столбец 4</td>
                            <td>Столбец 5</td>
                            <td>Столбец 6</td>
                            <td>Столбец 7</td>
                            <td>Столбец 8</td>
                            <td>Столбец 9</td>
                            <td>Столбец 10</td>
                            <td>Столбец 11</td>
                            <td>Столбец 12</td>
                            <td>Столбец 13</td>
                            <td>Столбец 14</td>
                            <td>Столбец 15</td>
                        </tr>
                        <tr>
                            <td>Столбец 1</td>
                            <td>Столбец 2</td>
                            <td>Столбец 3</td>
                            <td>Столбец 4</td>
                            <td>Столбец 5</td>
                            <td>Столбец 6</td>
                            <td>Столбец 7</td>
                            <td>Столбец 8</td>
                            <td>Столбец 9</td>
                            <td>Столбец 10</td>
                            <td>Столбец 11</td>
                            <td>Столбец 12</td>
                            <td>Столбец 13</td>
                            <td>Столбец 14</td>
                            <td>Столбец 15</td>
                        </tr>
                        <tr>
                            <td>Столбец 1</td>
                            <td>Столбец 2</td>
                            <td>Столбец 3</td>
                            <td>Столбец 4</td>
                            <td>Столбец 5</td>
                            <td>Столбец 6</td>
                            <td>Столбец 7</td>
                            <td>Столбец 8</td>
                            <td>Столбец 9</td>
                            <td>Столбец 10</td>
                            <td>Столбец 11</td>
                            <td>Столбец 12</td>
                            <td>Столбец 13</td>
                            <td>Столбец 14</td>
                            <td>Столбец 15</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <h3 class="intec-ui-m-t-none">Отступы</h3>
        <div>
            <p>
                Имеются классы для работы с margin и padding.
            </p>
            <ul>
                <li><code>.intec-ui-p</code> - класс для работы с padding;</li>
                <li><code>.intec-ui-m</code> - класс для работы с margin.</li>
            </ul>
            <p>
                Использование класса отступов
            </p>
            <ol>
                <li><code>.intec-ui-(m|p)-&lt;значение&gt;</code>;</li>
                <li><code>.intec-ui-(m|p)-(t|r|b|l|v|h)-&lt;значение&gt;</code>.</li>
            </ol>
            <p>
                <code>(m|p)</code> означает тип отступа (margin или padding соответственно).
                <code>(t|r|b|l|v|h)</code> направление отступа (top, right, bottom, left, vertical или horizontal соответственно), <code>v</code> означает отступ сверху и снизу, <code>h</code> - слева и справа.
                <code>&lt;значение&gt;</code> может иметь <code>none</code>, что означает необходимость убрать отступ,
                либо значение от <code>5</code> до <code>100</code> с шагом в <code>5</code>.
                В варианте 1, где не указано направление оступа, он будет применен ко всем сторонам.
            </p>
        </div>
        <h3 class="intec-ui-m-t-none">Элементы интерфейса</h3>
        <div>
            <p>
                Каждый элемент интерфейса имеет класс <code>.intec-ui</code>, а также собственный класс <code>.intec-ui-control-&lt;элемент&gt;</code>.
            </p>
            <p>
                Большинство элементов (кнопки, поля ввода, счетчики) имеют сетку в <code>4px</code> и стандартную высоту в <code>32px</code>.
                Размеры элементов можно менять с помощью класса <code>.intec-ui-size-&lt;размер&gt;</code>, где <code>размер</code> - это число от <code>1</code> до <code>5</code>.
                Каждый размер увеличивает высоту элемента на <code>4px</code>.
            </p>
            <p>
                Все элементы (кроме полей ввода) имеют цветовые схемы. Цветовые схемы задаются с помощью класса <code>.intec-ui-scheme-&lt;схема&gt;</code>
                <br />
                Доступны следующие цветовые схемы:
            </p>
            <ul>
                <li><code>current</code> - Текущая цветовая схема сайта</li>
                <li><code>blue</code> - Синяя цветовая схема</li>
                <li><code>blue-1</code> - Синяя цветовая схема 1</li>
                <li><code>red</code> - Красная цветовая схема</li>
                <li><code>green</code> - Зеленая цветовая схема</li>
                <li><code>green-1</code> - Зеленая цветовая схема 1</li>
                <li><code>orange</code> - Оранжевая цветовая схема</li>
                <li><code>gray</code> - Серая цветовая схема</li>
            </ul>
            <hr />
            <h4>Кнопка (<code>button</code>)</h4>
            <p>
                Класс для применения: <code>.intec-ui.intec-ui-control-button</code>.
            </p>
            <div class="intec-ui-m-b-20">
                <div class="intec-ui intec-ui-control-button intec-ui-scheme-current">Кнопка</div>
            </div>
            <h6>Блочная кнопка</h6>
            <p>
                Для того чтобы сделать кнопку блочным элементом, необходимо добавить класс <code>.intec-ui-mod-block</code>.
            </p>
            <div class="intec-ui-m-b-20">
                <div class="intec-ui intec-ui-control-button intec-ui-mod-block intec-ui-scheme-current">Кнопка</div>
            </div>
            <h6>Прозрачная кнопка</h6>
            <p>
                Для того чтобы сделать кнопку прозрачной, необходимо добавить класс <code>.intec-ui-mod-transparent</code>.
            </p>
            <div class="intec-ui-m-b-20">
                <div class="intec-ui intec-ui-control-button intec-ui-mod-transparent intec-ui-scheme-current">Кнопка</div>
            </div>
            <h6>Ссылочная кнопка</h6>
            <p>
                Для того чтобы сделать кнопку ссылочной, необходимо добавить класс <code>.intec-ui-mod-link</code>.
            </p>
            <div class="intec-ui-m-b-20">
                <div class="intec-ui intec-ui-control-button intec-ui-mod-link intec-ui-scheme-current">Кнопка</div>
            </div>
            <h6>Скругление углов</h6>
            <p>
                Для того чтобы скруглить углы кнопки, необходимо добавить класс <code>.intec-ui-mod-round-&lt;значение&gt;</code>, где <code>значение</code> - это число от <code>1</code> до <code>5</code>, которое эквивалентно скруглению в пикселях, <code>none</code> или <code>half</code>. Если <code>значение</code> <code>half</code>, то подразумевается скргление углов на половину высоты элемента.
            </p>
            <div class="intec-grid intec-grid-wrap intec-grid-i-5">
                <?php foreach ($arRounds as $sRound) { ?>
                    <div class="intec-grid-item-1">
                        <div class="intec-ui intec-ui-control-button intec-ui-mod-round-<?= $sRound ?> intec-ui-scheme-current">Кнопка (<?= $sRound ?>)</div>
                    </div>
                <?php } ?>
            </div>
            <h6>Интерактивная кнопка</h6>
            <p>
                Интерактивная кнопка обычно состоит из 2-х частей. Первая часть это контейнер с классом <code>.intec-ui-part-icon</code> (иконка), вторая - <code>.intec-ui-part-content</code> (содержимое кнопки). Кнопка может содержать любое количество частей. Каждая часть может быть расположена в любом месте. Отступ между частями интерактивной кнопки зависит от размеров текста.
            </p>
            <div class="intec-grid intec-grid-wrap intec-grid-i-5">
                <div class="intec-grid-item-auto">
                    <div class="intec-ui intec-ui-control-button intec-ui-scheme-current">
                        <div class="intec-ui-part-icon">
                            <i class="far fa-question-circle"></i>
                        </div>
                        <div class="intec-ui-part-content">Кнопка</div>
                    </div>
                </div>
                <div class="intec-grid-item-auto">
                    <div class="intec-ui intec-ui-control-button intec-ui-scheme-current">
                        <div class="intec-ui-part-content">Кнопка</div>
                        <div class="intec-ui-part-icon">
                            <i class="far fa-question-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            <h6>Цветовые схемы</h6>
            <div class="intec-grid intec-grid-wrap intec-grid-i-5">
                <?php foreach ($arSchemes as $sScheme) { ?>
                    <?php if (empty($sScheme)) { ?>
                        <div class="intec-grid-item-1">
                            <div class="intec-ui intec-ui-control-button">Кнопка</div>
                        </div>
                    <?php } else { ?>
                        <div class="intec-grid-item-1">
                            <div class="intec-ui intec-ui-control-button intec-ui-scheme-<?= $sScheme ?>">Кнопка (<?= $sScheme ?>)</div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <h6>Размеры</h6>
            <div class="intec-grid intec-grid-wrap intec-grid-i-5">
                <div class="intec-grid-item-1">
                    <div class="intec-ui intec-ui-control-button intec-ui-scheme-current">Кнопка</div>
                </div>
                <?php foreach ($arSizes as $sSize) { ?>
                    <div class="intec-grid-item-1">
                        <div class="intec-ui intec-ui-control-button intec-ui-scheme-current intec-ui-size-<?= $sSize ?>">Кнопка (<?= $sSize ?>)</div>
                    </div>
                <?php } ?>
            </div>
            <hr />
            <h4>Поле ввода (<code>input</code>)</h4>
            <p>
                Класс для применения: <code>.intec-ui.intec-ui-control-input</code>.
            </p>
            <div class="intec-ui-m-b-20">
                <div class="intec-ui-m-b-10">
                    <input class="intec-ui intec-ui-control-input" value="Поле ввода" />
                </div>
                <div class="intec-ui-m-b-10">
                    <select class="intec-ui intec-ui-control-input">
                        <option>Опция 1</option>
                        <option>Опция 2</option>
                        <option>Опция 3</option>
                    </select>
                </div>
                <div class="intec-ui-m-b-10">
                    <textarea class="intec-ui intec-ui-control-input" style="min-width: 100%; max-width: 100%;">Большое поле ввода</textarea>
                </div>
            </div>
            <h6>Блочное поле ввода</h6>
            <p>
                Для того чтобы сделать поле ввода блочным элементом, необходимо добавить класс <code>.intec-ui-mod-block</code>.
            </p>
            <div class="intec-ui-m-b-20">
                <input class="intec-ui intec-ui-control-input intec-ui-mod-block" value="Поле ввода" />
            </div>
            <h6>Варианты отображения</h6>
            <p>
                Имеет несколько вариантов отображения, необходимо добавить класс <code>.intec-ui-view-&lt;значение&gt;</code>, где &lt;значение&gt; - число.
            </p>
            <div class="intec-ui-m-b-20">
                <div class="intec-grid intec-grid-wrap intec-grid-i-5">
                    <div class="intec-grid-item-1">
                        <input class="intec-ui intec-ui-control-input" value="Поле ввода" />
                    </div>
                    <div class="intec-grid-item-1">
                        <input class="intec-ui intec-ui-control-input intec-ui-view-1" value="Поле ввода (1)" />
                    </div>
                    <div class="intec-grid-item-1">
                        <input class="intec-ui intec-ui-control-input intec-ui-view-2" value="Поле ввода (2)" />
                    </div>
                </div>
            </div>
            <h6>Скругление углов</h6>
            <p>
                Для того чтобы скруглить углы поля ввода, необходимо добавить класс <code>.intec-ui-mod-round-&lt;значение&gt;</code>, где <code>значение</code> - это число от <code>1</code> до <code>5</code>, которое эквивалентно скруглению в пикселях, <code>none</code> или <code>half</code>. Если <code>значение</code> <code>half</code>, то подразумевается скргление углов на половину высоты элемента.
            </p>
            <div class="intec-grid intec-grid-wrap intec-grid-i-5">
                <?php foreach ($arRounds as $sRound) { ?>
                    <div class="intec-grid-item-1">
                        <input class="intec-ui intec-ui-control-input intec-ui-mod-round-<?= $sRound ?>" value="Поле ввода (<?= $sRound ?>)" />
                    </div>
                <?php } ?>
            </div>
            <h6>Размеры</h6>
            <div class="intec-grid intec-grid-wrap intec-grid-i-5">
                <div class="intec-grid-item-1">
                    <input class="intec-ui intec-ui-control-input" value="Поле ввода" />
                </div>
                <?php foreach ($arSizes as $sSize) { ?>
                    <div class="intec-grid-item-1">
                        <input class="intec-ui intec-ui-control-input intec-ui-size-<?= $sSize ?>" value="Поле ввода (<?= $sSize ?>)" />
                    </div>
                <?php } ?>
            </div>
            <hr />
            <h4>Числовое поле ввода (<code>numeric</code>)</h4>
            <p>
                Класс для применения: <code>.intec-ui.intec-ui-control-numeric</code>.
            </p>
            <div class="intec-ui intec-ui-control-numeric intec-ui-scheme-current">
                <button class="intec-ui-part-decrement">-</button>
                <input type="text" class="intec-ui-part-input" value="1" />
                <button class="intec-ui-part-increment">+</button>
            </div>
            <h6>Варианты отображения</h6>
            <p>
                Имеет несколько вариантов отображения, необходимо добавить класс <code>.intec-ui-view-&lt;значение&gt;</code>, где &lt;значение&gt; - число.
            </p>
            <div class="intec-ui-m-b-20">
                <div class="intec-grid intec-grid-wrap intec-grid-i-5">
                    <div class="intec-grid-item-1">
                        <div class="intec-ui intec-ui-control-numeric intec-ui-scheme-current">
                            <button class="intec-ui-part-decrement">-</button>
                            <input type="text" class="intec-ui-part-input" value="1" />
                            <button class="intec-ui-part-increment">+</button>
                        </div>
                        <span>Вариант по умолчанию</span>
                    </div>
                    <div class="intec-grid-item-1">
                        <div class="intec-ui intec-ui-control-numeric intec-ui-scheme-current intec-ui-view-1">
                            <button class="intec-ui-part-decrement">-</button>
                            <input type="text" class="intec-ui-part-input" value="1" />
                            <button class="intec-ui-part-increment">+</button>
                        </div>
                        <span>Вариант 1</span>
                    </div>
                    <div class="intec-grid-item-1">
                        <div class="intec-ui intec-ui-control-numeric intec-ui-scheme-current intec-ui-view-2">
                            <button class="intec-ui-part-decrement">-</button>
                            <input type="text" class="intec-ui-part-input" value="1" />
                            <button class="intec-ui-part-increment">+</button>
                        </div>
                        <span>Вариант 2</span>
                    </div>
                    <div class="intec-grid-item-1">
                        <div class="intec-ui intec-ui-control-numeric intec-ui-scheme-current intec-ui-view-3">
                            <button class="intec-ui-part-decrement">-</button>
                            <input type="text" class="intec-ui-part-input" value="1" />
                            <button class="intec-ui-part-increment">+</button>
                        </div>
                        <span>Вариант 3</span>
                    </div>
                    <div class="intec-grid-item-1">
                        <div class="intec-ui intec-ui-control-numeric intec-ui-scheme-current intec-ui-view-4">
                            <button class="intec-ui-part-decrement">-</button>
                            <input type="text" class="intec-ui-part-input" value="1" />
                            <button class="intec-ui-part-increment">+</button>
                        </div>
                        <span>Вариант 4</span>
                    </div>
                </div>
            </div>
            <h6>Цветовые схемы</h6>
            <div class="intec-grid intec-grid-wrap intec-grid-i-5">
                <?php foreach ($arSchemes as $sScheme) { ?>
                    <?php if (empty($sScheme)) { ?>
                        <div class="intec-grid-item-1">
                            <div class="intec-ui intec-ui-control-numeric">
                                <button class="intec-ui-part-decrement">-</button>
                                <input type="text" class="intec-ui-part-input" value="1" />
                                <button class="intec-ui-part-increment">+</button>
                            </div>
                            <span>Схема по умолчанию</span>
                        </div>
                    <?php } else { ?>
                        <div class="intec-grid-item-1">
                            <div class="intec-ui intec-ui-control-numeric intec-ui-scheme-<?= $sScheme ?>">
                                <button class="intec-ui-part-decrement">-</button>
                                <input type="text" class="intec-ui-part-input" value="1" />
                                <button class="intec-ui-part-increment">+</button>
                            </div>
                            <span>Схема <?= $sScheme ?></span>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <h6>Размеры</h6>
            <div class="intec-grid intec-grid-wrap intec-grid-i-5">
                <div class="intec-grid-item-1">
                    <div class="intec-grid-item-1">
                        <div class="intec-ui intec-ui-control-numeric intec-ui-scheme-current">
                            <button class="intec-ui-part-decrement">-</button>
                            <input type="text" class="intec-ui-part-input" value="1" />
                            <button class="intec-ui-part-increment">+</button>
                        </div>
                    </div>
                </div>
                <?php foreach ($arSizes as $sSize) { ?>
                    <div class="intec-grid-item-1">
                        <div class="intec-ui intec-ui-control-numeric intec-ui-scheme-current intec-ui-size-<?= $sSize ?>">
                            <button class="intec-ui-part-decrement">-</button>
                            <input type="text" class="intec-ui-part-input" value="1" />
                            <button class="intec-ui-part-increment">+</button>
                        </div>
                        <span>Схема <?= $sSize ?></span>
                    </div>
                <?php } ?>
            </div>
            <hr />
            <h4>Сообщение или оповещение (<code>alert</code>)</h4>
            <p>
                Класс для применения: <code>.intec-ui.intec-ui-control-alert</code>.
            </p>
            <div class="intec-ui intec-ui-control-alert intec-ui-scheme-current intec-ui-m-b-20">
                Сообщение или оповещение.
            </div>
            <h6>Цветовые схемы</h6>
            <?php foreach ($arSchemes as $sScheme) { ?>
                <?php if (empty($sScheme)) { ?>
                    <div class="intec-ui intec-ui-control-alert intec-ui-m-b-20">
                        Сообщение или оповещение.
                    </div>
                <?php } else { ?>
                    <div class="intec-ui intec-ui-control-alert intec-ui-scheme-<?= $sScheme ?> intec-ui-m-b-20">
                        Сообщение или оповещение. (<?= $sScheme ?>)
                    </div>
                <?php } ?>
            <?php } ?>
            <hr />
            <h4>Checkbox (<code>checkbox</code>)</h4>
            <p>
                Класс для применения: <code>.intec-ui.intec-ui-control-checkbox</code>. Имеет нестандартную сетку.
            </p>
            <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-current">
                <input type="checkbox" checked="checked" />
                <span class="intec-ui-part-selector"></span>
                <span class="intec-ui-part-content">Пункт 1</span>
            </label>
            <br />
            <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-current">
                <input type="checkbox" />
                <span class="intec-ui-part-selector"></span>
                <span class="intec-ui-part-content">Пункт 2</span>
            </label>
            <br />
            <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-current">
                <input type="checkbox" />
                <span class="intec-ui-part-selector"></span>
                <span class="intec-ui-part-content">Пункт 3</span>
            </label>
            <h6>Размеры</h6>
            <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-current">
                <input type="checkbox" checked="checked" />
                <span class="intec-ui-part-selector"></span>
                <span class="intec-ui-part-content">Пункт</span>
            </label>
            <?php foreach ($arSizes as $sSize) { ?>
                <br />
                <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-current intec-ui-size-<?= $sSize ?>">
                    <input type="checkbox" checked="checked" />
                    <span class="intec-ui-part-selector"></span>
                    <span class="intec-ui-part-content">Пункт (<?= $sSize ?>)</span>
                </label>
            <?php } ?>
            <h6>Цветовые схемы</h6>
            <?php foreach ($arSchemes as $sScheme) { ?>
                <?php if (empty($sScheme)) { ?>
                    <label class="intec-ui intec-ui-control-checkbox">
                        <input type="checkbox" checked="checked" />
                        <span class="intec-ui-part-selector"></span>
                        <span class="intec-ui-part-content">Пункт</span>
                    </label>
                <?php } else { ?>
                    <br />
                    <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-<?= $sScheme ?>">
                        <input type="checkbox" checked="checked" />
                        <span class="intec-ui-part-selector"></span>
                        <span class="intec-ui-part-content">Пункт (<?= $sScheme ?>)</span>
                    </label>
                <?php } ?>
            <?php } ?>
            <hr />
            <h4>Radio (<code>radiobox</code>)</h4>
            <p>
                Класс для применения: <code>.intec-ui.intec-ui-control-radiobox</code>. Имеет нестандартную сетку.
            </p>
            <label class="intec-ui intec-ui-control-radiobox intec-ui-scheme-current">
                <input type="radio" checked="checked" name="radio" />
                <span class="intec-ui-part-selector"></span>
                <span class="intec-ui-part-content">Пункт 1</span>
            </label>
            <br />
            <label class="intec-ui intec-ui-control-radiobox intec-ui-scheme-current">
                <input type="radio" name="radio" />
                <span class="intec-ui-part-selector"></span>
                <span class="intec-ui-part-content">Пункт 2</span>
            </label>
            <br />
            <label class="intec-ui intec-ui-control-radiobox intec-ui-scheme-current">
                <input type="radio" name="radio" />
                <span class="intec-ui-part-selector"></span>
                <span class="intec-ui-part-content">Пункт 3</span>
            </label>
            <h6>Размеры</h6>
            <label class="intec-ui intec-ui-control-radiobox intec-ui-scheme-current">
                <input type="radio" checked="checked" name="radio-size" />
                <span class="intec-ui-part-selector"></span>
                <span class="intec-ui-part-content">Пункт</span>
            </label>
            <label class="intec-ui intec-ui-control-radiobox intec-ui-scheme-current">
                <input type="radio" name="radio-size" />
                <span class="intec-ui-part-selector"></span>
                <span class="intec-ui-part-content">Пункт</span>
            </label>
            <?php foreach ($arSizes as $sSize) { ?>
                <br />
                <label class="intec-ui intec-ui-control-radiobox intec-ui-scheme-current intec-ui-size-<?= $sSize ?>">
                    <input type="radio" checked="checked" name="radio-size-<?= $sSize ?>" />
                    <span class="intec-ui-part-selector"></span>
                    <span class="intec-ui-part-content">Пункт (<?= $sSize ?>)</span>
                </label>
                <label class="intec-ui intec-ui-control-radiobox intec-ui-scheme-current intec-ui-size-<?= $sSize ?>">
                    <input type="radio" name="radio-size-<?= $sSize ?>" />
                    <span class="intec-ui-part-selector"></span>
                    <span class="intec-ui-part-content">Пункт (<?= $sSize ?>)</span>
                </label>
            <?php } ?>
            <h6>Цветовые схемы</h6>
            <?php foreach ($arSchemes as $sScheme) { ?>
                <?php if (empty($sScheme)) { ?>
                    <label class="intec-ui intec-ui-control-radiobox">
                        <input type="radio" checked="checked" name="radio-scheme-simple" />
                        <span class="intec-ui-part-selector"></span>
                        <span class="intec-ui-part-content">Пункт</span>
                    </label>
                    <label class="intec-ui intec-ui-control-radiobox">
                        <input type="radio" name="radio-scheme-simple" />
                        <span class="intec-ui-part-selector"></span>
                        <span class="intec-ui-part-content">Пункт</span>
                    </label>
                <?php } else { ?>
                    <br />
                    <label class="intec-ui intec-ui-control-radiobox intec-ui-scheme-<?= $sScheme ?>">
                        <input type="radio" checked="checked" name="radio-scheme-<?= $sScheme ?>" />
                        <span class="intec-ui-part-selector"></span>
                        <span class="intec-ui-part-content">Пункт (<?= $sScheme ?>)</span>
                    </label>
                    <label class="intec-ui intec-ui-control-radiobox intec-ui-scheme-<?= $sScheme ?>">
                        <input type="radio" name="radio-scheme-<?= $sScheme ?>" />
                        <span class="intec-ui-part-selector"></span>
                        <span class="intec-ui-part-content">Пункт (<?= $sScheme ?>)</span>
                    </label>
                <?php } ?>
            <?php } ?>
            <hr />
            <h4>Переключатели (<code>switch</code>)</h4>
            <p>
                Класс для применения: <code>.intec-ui.intec-ui-control-switch</code>. Имеет нестандартную сетку.
            </p>
            <label class="intec-ui intec-ui-control-switch intec-ui-scheme-current">
                <input type="checkbox" />
                <span class="intec-ui-part-selector"></span>
                <span class="intec-ui-part-content">Пункт 1</span>
            </label>
            <br />
            <label class="intec-ui intec-ui-control-switch intec-ui-scheme-current">
                <input type="checkbox" />
                <span class="intec-ui-part-selector"></span>
                <span class="intec-ui-part-content">Пункт 2</span>
            </label>
            <br />
            <label class="intec-ui intec-ui-control-switch intec-ui-scheme-current">
                <input type="checkbox" />
                <span class="intec-ui-part-selector"></span>
                <span class="intec-ui-part-content">Пункт 3</span>
            </label>
            <h6>Размеры</h6>
            <label class="intec-ui intec-ui-control-switch intec-ui-scheme-current">
                <input type="checkbox" checked="checked" />
                <span class="intec-ui-part-selector"></span>
                <span class="intec-ui-part-content">Пункт</span>
            </label>
            <?php foreach ($arSizes as $sSize) { ?>
                <br />
                <label class="intec-ui intec-ui-control-switch intec-ui-scheme-current intec-ui-size-<?= $sSize ?>">
                    <input type="checkbox" checked="checked" />
                    <span class="intec-ui-part-selector"></span>
                    <span class="intec-ui-part-content">Пункт (<?= $sSize ?>)</span>
                </label>
            <?php } ?>
            <h6>Цветовые схемы</h6>
            <?php foreach ($arSchemes as $sScheme) { ?>
                <?php if (empty($sScheme)) { ?>
                    <label class="intec-ui intec-ui-control-switch">
                        <input type="checkbox" checked="checked" />
                        <span class="intec-ui-part-selector"></span>
                        <span class="intec-ui-part-content">Пункт</span>
                    </label>
                <?php } else { ?>
                    <br />
                    <label class="intec-ui intec-ui-control-switch intec-ui-scheme-<?= $sScheme ?>">
                        <input type="checkbox" checked="checked" />
                        <span class="intec-ui-part-selector"></span>
                        <span class="intec-ui-part-content">Пункт (<?= $sScheme ?>)</span>
                    </label>
                <?php } ?>
            <?php } ?>
            <hr />
            <h4>Вкладки (<code>tabs</code>)</h4>
            <p>
                Класс для применения: <code>.intec-ui.intec-ui-control-tabs</code>. Имеет нестандартную сетку.
            </p>
            <div class="intec-ui-m-b-20">
                <ul class="intec-ui intec-ui-control-tabs intec-ui-scheme-current">
                    <li class="intec-ui-part-tab active">
                        <a href="#tab-1" role="tab" data-toggle="tab">Вкладка 1</a>
                    </li>
                    <li class="intec-ui-part-tab">
                        <a href="#tab-2" role="tab" data-toggle="tab">Вкладка 2</a>
                    </li>
                    <li class="intec-ui-part-tab">
                        <a href="#tab-3" role="tab" data-toggle="tab">Вкладка 3</a>
                    </li>
                </ul>
                <div class="intec-ui intec-ui-control-tabs-content">
                    <div id="tab-1" class="intec-ui-part-tab active" role="tabpanel">
                        <div class="intec-ui-m-t-20">Контент первой вкладки.</div>
                    </div>
                    <div id="tab-2" class="intec-ui-part-tab" role="tabpanel">
                        <div class="intec-ui-m-t-20">Контент второй вкладки.</div>
                    </div>
                    <div id="tab-3" class="intec-ui-part-tab" role="tabpanel">
                        <div class="intec-ui-m-t-20">Контент третьей вкладки.</div>
                    </div>
                </div>
            </div>
            <p>
                Для того чтобы сделать вкладки блочным элементом, необходимо добавить класс <code>.intec-ui-mod-block</code>.
            </p>
            <div class="intec-ui-m-b-20">
                <ul class="intec-ui intec-ui-control-tabs intec-ui-mod-block intec-ui-scheme-current">
                    <li class="intec-ui-part-tab active">
                        <a href="#block-tab-1" role="tab" data-toggle="tab">Вкладка 1</a>
                    </li>
                    <li class="intec-ui-part-tab">
                        <a href="#block-tab-2" role="tab" data-toggle="tab">Вкладка 2</a>
                    </li>
                    <li class="intec-ui-part-tab">
                        <a href="#block-tab-3" role="tab" data-toggle="tab">Вкладка 3</a>
                    </li>
                </ul>
                <div class="intec-ui intec-ui-control-tabs-content">
                    <div id="block-tab-1" class="intec-ui-part-tab active" role="tabpanel">
                        <div class="intec-ui-m-t-20">Контент первой вкладки.</div>
                    </div>
                    <div id="block-tab-2" class="intec-ui-part-tab" role="tabpanel">
                        <div class="intec-ui-m-t-20">Контент второй вкладки.</div>
                    </div>
                    <div id="block-tab-3" class="intec-ui-part-tab" role="tabpanel">
                        <div class="intec-ui-m-t-20">Контент третьей вкладки.</div>
                    </div>
                </div>
            </div>
            <h6>Варианты отображения</h6>
            <p>
                Имеет несколько вариантов отображения, необходимо добавить класс <code>.intec-ui-view-&lt;значение&gt;</code>, где &lt;значение&gt; - число.
            </p>
            <div class="intec-ui-m-b-20">
                <div class="intec-grid intec-grid-wrap intec-grid-i-10">
                    <div class="intec-grid-item-1">
                        <div class="intec-ui-m-b-10">
                            <b>Вариант по умолчанию</b>
                        </div>
                        <ul class="intec-ui intec-ui-control-tabs intec-ui-scheme-current">
                            <li class="intec-ui-part-tab active">
                                <a href="#block-view-0-tab-1" role="tab" data-toggle="tab">Вкладка 1</a>
                            </li>
                            <li class="intec-ui-part-tab">
                                <a href="#block-view-0-tab-2" role="tab" data-toggle="tab">Вкладка 2</a>
                            </li>
                            <li class="intec-ui-part-tab">
                                <a href="#block-view-0-tab-3" role="tab" data-toggle="tab">Вкладка 3</a>
                            </li>
                        </ul>
                        <div class="intec-ui intec-ui-control-tabs-content">
                            <div id="block-view-0-tab-1" class="intec-ui-part-tab active" role="tabpanel">
                                <div class="intec-ui-m-t-20">Контент первой вкладки.</div>
                            </div>
                            <div id="block-view-0-tab-2" class="intec-ui-part-tab" role="tabpanel">
                                <div class="intec-ui-m-t-20">Контент второй вкладки.</div>
                            </div>
                            <div id="block-view-0-tab-3" class="intec-ui-part-tab" role="tabpanel">
                                <div class="intec-ui-m-t-20">Контент третьей вкладки.</div>
                            </div>
                        </div>
                    </div>
                    <div class="intec-grid-item-1">
                        <div class="intec-ui-m-b-10">
                            <b>Вариант 1</b>
                        </div>
                        <ul class="intec-ui intec-ui-control-tabs intec-ui-scheme-current intec-ui-view-1">
                            <li class="intec-ui-part-tab active">
                                <a href="#block-view-1-tab-1" role="tab" data-toggle="tab">Вкладка 1</a>
                            </li>
                            <li class="intec-ui-part-tab">
                                <a href="#block-view-1-tab-2" role="tab" data-toggle="tab">Вкладка 2</a>
                            </li>
                            <li class="intec-ui-part-tab">
                                <a href="#block-view-1-tab-3" role="tab" data-toggle="tab">Вкладка 3</a>
                            </li>
                        </ul>
                        <div class="intec-ui intec-ui-control-tabs-content">
                            <div id="block-view-1-tab-1" class="intec-ui-part-tab active" role="tabpanel">
                                <div class="intec-ui-m-t-20">Контент первой вкладки.</div>
                            </div>
                            <div id="block-view-1-tab-2" class="intec-ui-part-tab" role="tabpanel">
                                <div class="intec-ui-m-t-20">Контент второй вкладки.</div>
                            </div>
                            <div id="block-view-1-tab-3" class="intec-ui-part-tab" role="tabpanel">
                                <div class="intec-ui-m-t-20">Контент третьей вкладки.</div>
                            </div>
                        </div>
                    </div>
                    <div class="intec-grid-item-1">
                        <div class="intec-ui-m-b-10">
                            <b>Вариант 2</b>
                        </div>
                        <ul class="intec-ui intec-ui-control-tabs intec-ui-scheme-current intec-ui-view-2">
                            <li class="intec-ui-part-tab active">
                                <a href="#block-view-2-tab-1" role="tab" data-toggle="tab">Вкладка 1</a>
                            </li>
                            <li class="intec-ui-part-tab">
                                <a href="#block-view-2-tab-2" role="tab" data-toggle="tab">Вкладка 2</a>
                            </li>
                            <li class="intec-ui-part-tab">
                                <a href="#block-view-2-tab-3" role="tab" data-toggle="tab">Вкладка 3</a>
                            </li>
                        </ul>
                        <div class="intec-ui intec-ui-control-tabs-content">
                            <div id="block-view-2-tab-1" class="intec-ui-part-tab active" role="tabpanel">
                                <div class="intec-ui-m-t-20">Контент первой вкладки.</div>
                            </div>
                            <div id="block-view-2-tab-2" class="intec-ui-part-tab" role="tabpanel">
                                <div class="intec-ui-m-t-20">Контент второй вкладки.</div>
                            </div>
                            <div id="block-view-2-tab-3" class="intec-ui-part-tab" role="tabpanel">
                                <div class="intec-ui-m-t-20">Контент третьей вкладки.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h6>Цветовые схемы</h6>
            <div class="intec-grid intec-grid-wrap intec-grid-i-10">
                <?php foreach ($arSchemes as $sScheme) { ?>
                    <?php if (empty($sScheme)) { ?>
                        <div class="intec-grid-item-1">
                            <div class="intec-ui-m-b-10">
                                <b>Схема по умолчанию</b>
                            </div>
                            <ul class="intec-ui intec-ui-control-tabs">
                                <li class="intec-ui-part-tab active">
                                    <a href="#block-scheme-default-tab-1" role="tab" data-toggle="tab">Вкладка 1</a>
                                </li>
                                <li class="intec-ui-part-tab">
                                    <a href="#block-scheme-default-tab-2" role="tab" data-toggle="tab">Вкладка 2</a>
                                </li>
                                <li class="intec-ui-part-tab">
                                    <a href="#block-scheme-default-tab-3" role="tab" data-toggle="tab">Вкладка 3</a>
                                </li>
                            </ul>
                            <div class="intec-ui intec-ui-control-tabs-content">
                                <div id="block-scheme-default-tab-1" class="intec-ui-part-tab active" role="tabpanel">
                                    <div class="intec-ui-m-t-20">Контент первой вкладки.</div>
                                </div>
                                <div id="block-scheme-default-tab-2" class="intec-ui-part-tab" role="tabpanel">
                                    <div class="intec-ui-m-t-20">Контент второй вкладки.</div>
                                </div>
                                <div id="block-scheme-default-tab-3" class="intec-ui-part-tab" role="tabpanel">
                                    <div class="intec-ui-m-t-20">Контент третьей вкладки.</div>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="intec-grid-item-1">
                            <div class="intec-ui-m-b-10">
                                <b>Схема <?= $sScheme ?></b>
                            </div>
                            <ul class="intec-ui intec-ui-control-tabs intec-ui-scheme-<?= $sScheme ?>">
                                <li class="intec-ui-part-tab active">
                                    <a href="#block-scheme-<?= $sScheme ?>-tab-1" role="tab" data-toggle="tab">Вкладка 1</a>
                                </li>
                                <li class="intec-ui-part-tab">
                                    <a href="#block-scheme-<?= $sScheme ?>-tab-2" role="tab" data-toggle="tab">Вкладка 2</a>
                                </li>
                                <li class="intec-ui-part-tab">
                                    <a href="#block-scheme-<?= $sScheme ?>-tab-3" role="tab" data-toggle="tab">Вкладка 3</a>
                                </li>
                            </ul>
                            <div class="intec-ui intec-ui-control-tabs-content">
                                <div id="block-scheme-<?= $sScheme ?>-tab-1" class="intec-ui-part-tab active" role="tabpanel">
                                    <div class="intec-ui-m-t-20">Контент первой вкладки.</div>
                                </div>
                                <div id="block-scheme-<?= $sScheme ?>-tab-2" class="intec-ui-part-tab" role="tabpanel">
                                    <div class="intec-ui-m-t-20">Контент второй вкладки.</div>
                                </div>
                                <div id="block-scheme-<?= $sScheme ?>-tab-3" class="intec-ui-part-tab" role="tabpanel">
                                    <div class="intec-ui-m-t-20">Контент третьей вкладки.</div>
                                </div>
                            </div>
                            <div class="intec-ui intec-ui-control-numeric intec-ui-scheme-<?= $sScheme ?>">
                                <button class="intec-ui-part-decrement">-</button>
                                <input type="text" class="intec-ui-part-input" value="1" />
                                <button class="intec-ui-part-increment">+</button>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <h6>Выравнивание</h6>
            <p>
                Имеет выравнивание вкладок слева, справа и по центру. Необходимо добавить класс <code>.intec-ui-mod-position-&lt;значение&gt;</code>, где &lt;значение&gt; - <code>left</code>, <code>right</code> или <code>center</code>. Позиционирование работает только при блочном варианте отображения.
            </p>
        </div>
    </div>
</div>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php') ?>