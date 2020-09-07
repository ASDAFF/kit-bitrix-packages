<?
namespace Kit\Crosssell\Helper;

class CrosssellNotice {
    public static function getError($error) {
        return ' 
            <div class="adm-info-message-wrap adm-info-message-red">
                <div class="adm-info-message" style="margin: 2px 0;">
                    <div class="adm-info-message-title">' . $error . '</div>
                    <div class="adm-info-message-icon"></div>
                </div>
            </div>
            ';
    }
    public static function getSuccess($mess) {
        return ' 
            <div class="adm-info-message-wrap adm-info-message-green">
                <div class="adm-info-message" style="margin: 2px 0;">
                    <div class="adm-info-message-title">' . $mess . '</div>
                    <div class="adm-info-message-icon"></div>
                </div>
            </div>
            ';
    }
}


?>