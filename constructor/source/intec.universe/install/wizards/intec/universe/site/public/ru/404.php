<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

?>
<div class="intec-content">
    <div class="intec-content-wrapper intec-404">
        <div class="row">
            <div class="col-md-6 xs-12">
                <div class="image-404">
                    <img src="<?= SITE_DIR ?>images/404.png">
                </div>
            </div>
            <div class="col-md-6 xs-12">
                <div class="text-404">
                    <div class="header-text">
                        Ошибка 404
                    </div>
                    <div class="header2-text">
                        Страница не найдена
                    </div>
                    <div class="text">
                        Неправильно набран адрес или такой страницы не существует
                    </div>
                    <div>
                        <a href="<?=SITE_DIR?>" class="intec-button intec-button-cl-common intec-button-md ">
                            Перейти на главную
                        </a>
                        <a href="<?=SITE_DIR?>catalog/" class="intec-button intec-button-transparent intec-button-cl-default intec-button-md ">
                            Вернуться в каталог
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>