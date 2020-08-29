<?
use Sotbit\Origami\Helper\Config;
if(Config::get("BTN_TOP") == "Y"):
?>
<div class ="btn_go-top" id="btn_go-top">
    <svg class="btn_go-top__icon" height="70" width="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle class="btn-go-top__border" cx="35" cy="35" r="35" />
        <circle class="btn-go-top__background" cx="35" cy="35" r="28" />
        <path class="btn-go-top__content" d="M34.2318 27.9219C34.6316 27.4421 35.3684 27.4421 35.7682 27.9219L43.6332 37.3598C44.176 38.0111 43.7128 39 42.865 39H27.135C26.2872 39 25.824 38.0111 26.3668 37.3598L34.2318 27.9219Z" fill="white"/>
    </svg>
    <script>
        (function() {
            btnTop();
        })();
    </script>
</div>
<?endif;?>