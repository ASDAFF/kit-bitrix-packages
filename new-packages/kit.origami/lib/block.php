<?

namespace Sotbit\Origami;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SiteTable;
use Sotbit\Origami\Front\User;
use Bitrix\Main\Type\DateTime;
use Sotbit\Origami\Internals\BlockTable;
use Bitrix\Conversion\Internals\MobileDetect;
use Bitrix\Main\Page\Asset;

class Block
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $code;
    /**
     * @var int
     */
    private $sort;
    /**
     * @var string
     */
    private $part;
    /**
     * @var boolean
     */
    private $active;
    /**
     * @var string
     */
    private $content;
    /**
     * @var array
     */
    private $settings;
    /**
     * @var string
     */
    private $dir;
    /**
     * @var string
     */
    private $class;
    /**
     * @var boolean
     */
    private $deleted = false;

    /**
     * @var string
     */
    private $page = '/';

    /**
     *
     */
    private $copyOf = 0;

    /**
     * Block constructor.
     *
     * @param array   $block
     * @param string $page
     */
    public function __construct($block = [], $page = SITE_DIR)
    {
        $this->setId($block['ID']);
        $this->setActive($block['ACTIVE']);
        $this->setCode($block['CODE']);
        $this->setPart($block['PART']);
        $this->setSort($block['SORT']);
        $this->setClass();
		$this->setPage($page);

        $dir = '';
        if ($this->getId() && strpos($this->getId(), 'tmp') === false) {
            $dir = $page.'blocks/'.$this->getCode().'_'.$this->getId();
        }

        $this->setDir($dir);

        $this->setContent();
        $this->setSettings();
    }

    public function includeAssets()
    {
        if ($this->getSettings()['ext']['css'])
        {
            foreach ($this->getSettings()['ext']['css'] as $css)
            {
                if (file_exists($css))
                {
                    echo '<style>'.file_get_contents($css).'</style>';
                }
            }
        }
        if ($this->getSettings()['ext']['js'])
        {
            foreach ($this->getSettings()['ext']['js'] as $js)
            {
                if (file_exists($js))
                {
                    echo '<script src="'.str_replace($_SERVER['DOCUMENT_ROOT'],'',$js).'"></script>';
                }
            }
        }
    }

    public function includeHeadAssets()
    {
        if($this->getSettings()['ext']['include_head'])
        {
            if ($this->getSettings()['ext']['css'])
            {
                foreach ($this->getSettings()['ext']['css'] as $css)
                {
                    $css = str_replace($_SERVER['DOCUMENT_ROOT'], '', $css);

                    //if (file_exists($css))
                    {
                        Asset::getInstance()->addCss($css);
                    }
                }
            }
            if ($this->getSettings()['ext']['js'])
            {
                foreach ($this->getSettings()['ext']['js'] as $js)
                {
                    //if (file_exists($js))
                    $js = str_replace($_SERVER['DOCUMENT_ROOT'], '', $js);
                    {
                        Asset::getInstance()->addJs($js);
                    }
                }
            }
        }
    }

    public function designShow()
    {
        $lgnone = '';
        if ($this->getSettings()['style']['l-d-lg-none']['value']) {
            $lgnone = 'landing-ui-active';
        }
        $mdnone = '';
        if ($this->getSettings()['style']['l-d-md-none']['value']) {
            $mdnone = 'landing-ui-active';
        }
        $xsnone = '';
        if ($this->getSettings()['style']['l-d-xs-none']['value']) {
            $xsnone = 'landing-ui-active';
        }
        echo '
		<div  class="landing-ui-panel landing-ui-panel-content landing-ui-panel-style landing-ui-hide landing-ui-hidden landing-ui-design"
		data-is-shown="true" data-id="'.$this->getId().'">
			<div class="landing-ui-panel-content-element landing-ui-panel-content-header">
				<div class="landing-ui-panel-content-title">'
            .Loc::getMessage(\SotbitOrigami::moduleId.'_STYLE').'</div>
			</div>
			<div class="landing-ui-panel-content-element landing-ui-panel-content-body">
				<div class="landing-ui-panel-content-body-sidebar"></div>
				<div class="landing-ui-panel-content-body-content">
					<div class="landing-ui-form landing-ui-form-style">
						<div class="landing-ui-form-header">'
            .Loc::getMessage(\SotbitOrigami::moduleId.'_SETTINGS').'</div>
					<div class="landing-ui-form-body">
						<div class="landing-ui-field landing-ui-display-button-group landing-ui-field-button-group" data-selector="#block110 > :first-child">
							<div class="landing-ui-field-header">'
            .Loc::getMessage(\SotbitOrigami::moduleId.'_VIEW').'</div>
							<div class="landing-ui-field-input" data-placeholder="">
								<button type="button" class="'.$lgnone.' landing-ui-button" data-id="l-d-lg-none"
								value="l-d-lg-none">
									<span class="landing-ui-button-text">
										<span class="landing-ui-button-desktop"></span>
									</span>
								</button>
								<button type="button" class="'.$mdnone.' landing-ui-button" data-id="l-d-md-none" value="l-d-md-none">
									<span class="landing-ui-button-text">
										<span class="landing-ui-button-tablet"></span>
									</span>
								</button>
								<button type="button" class="'.$xsnone.' landing-ui-button" data-id="l-d-xs-none" value="l-d-xs-none">
									<span class="landing-ui-button-text">
										<span class="landing-ui-button-mobile"></span>
									</span>
								</button>
							</div>
                        </div>

			';
        $paddings = [
            'padding-top',
            'padding-bottom',
            'padding-left',
            'padding-right',
        ];

        foreach ($paddings as $padding) {
            $val = 0;
            if ($this->getSettings()['style'][$padding]['value'] > 0) {
                $val = $this->getSettings()['style'][$padding]['value'];
            }
            echo '<label for="amount'.$padding.$this->getId().'">'
                .Loc::getMessage(\SotbitOrigami::moduleId.'_'.$padding).':</label>
			  <input type="text" id="amount'.$padding.$this->getId().'" readonly style="border:0; color:#f6931f; font-weight:bold;">
			<div id="'.$padding.$this->getId().'"></div>

			<script>
			$( function() {
				$( "#'.$padding.$this->getId().'" ).slider({
				  range: "max",
				  min: 0,
				  max: 150,
				  animate: "slow",
				  value: '.$val.',
				  slide: function( event, ui ) {
					$( "#amount'.$padding.$this->getId().'" ).val( ui.value );

					$.ajax({
								type: "POST",
								url: "/local/components/kit/block.include/ajax.php",
								async:false,
								data: {
									"id": "'.$this->getId().'",
									"code": "'.$this->getCode().'",
									"action":"'.$padding.'",
									"value":ui.value,
									"part":"'.$this->getPart().'",
									"site":"'.SITE_ID.'",
									"siteTemplate":"'.SITE_TEMPLATE_ID.'",
									"page":"'.$this->getPage().'"
								},
								success: function(data) {
								if($(".block-wrapper[data-id=\''.$this->getId().'\']").length)
								{
									$(".block-wrapper[data-id=\''.$this->getId().'\']").after(data);
									$(".block-wrapper[data-id=\''.$this->getId().'\']:first").remove();
								}
								else
								{
									if($(".block-wrapper[data-copyof=\''.$this->getId().'\']").length)
									{
										$(".block-wrapper[data-copyof=\''.$this->getId().'\']").after(data);
										$(".block-wrapper[data-copyof=\''.$this->getId().'\']:first").remove();
									}
								}
								},
							});
				  }
				});
				$( "#amount'.$padding.$this->getId().'" ).val( $( "#'.$padding
                .$this->getId().'" ).slider( "value" ) );
			  } );
			</script>';
        }

        echo '</div></div>';

        echo '<div><div style="margin-top:30px" class="landing-ui-field-header">'.Loc::getMessage(\SotbitOrigami::moduleId.'_BACKGROUND').'</div>';

        $background = [
            'background-color',
            'background-image',
            'background-position',
            'background-repeat',
            'background-size',
            'background-attachment',
            'background-clip',
            'background-origin'
        ];

        global $APPLICATION;

        foreach ($background as $back)
        {
            $val = "";
            if (isset($this->getSettings()['style'][$back]['value']) && !empty($this->getSettings()['style'][$back]['value'])) {
                $val = $this->getSettings()['style'][$back]['value'];
            }

            switch ($back)
            {
                case 'background-color':
                    echo '<script>
                            function OnSelectBGColor'.$this->getId().'(color, objColorPicker)
                            {
                               //alert(color);
                               $("#'.$back.$this->getId().'").val(color);

                               $.ajax({
                                    type: "POST",
                                    url: "/local/components/kit/block.include/ajax.php",
                                    async:false,
                                    data: {
                                        "id": "'.$this->getId().'",
                                        "code": "'.$this->getCode().'",
                                        "action":"'.$back.'",
                                        "value":color,
                                        "part":"'.$this->getPart().'",
                                        "site":"'.SITE_ID.'",
                                        "siteTemplate":"'.SITE_TEMPLATE_ID.'",
                                        "page":"'.$this->getPage().'"
                                    },
                                    success: function(data) {
                                        if($(".block-wrapper[data-id=\''.$this->getId().'\']").length)
                                        {
                                            $(".block-wrapper[data-id=\''.$this->getId().'\']").after(data);
                                            $(".block-wrapper[data-id=\''.$this->getId().'\']:first").remove();
                                        }
                                        else
                                        {
                                            if($(".block-wrapper[data-copyof=\''.$this->getId().'\']").length)
                                            {
                                                $(".block-wrapper[data-copyof=\''.$this->getId().'\']").after(data);
                                                $(".block-wrapper[data-copyof=\''.$this->getId().'\']:first").remove();
                                            }
                                        }
                                    },
                                });
                            }

                        </script>';
                    echo '<div class="landing-ui-field"><label for="'.$back.$this->getId().'">'
                        .Loc::getMessage(\SotbitOrigami::moduleId.'_'.$back).':</label>';
                    $APPLICATION->IncludeComponent(
                        "bitrix:main.colorpicker",
                        "origami.colorpicker",
                        Array(
                            "SHOW_BUTTON" => "Y",
                            "ID" => "color_picker_".$this->getId(),
                            "NAME" => Loc::getMessage(\SotbitOrigami::moduleId.'_'.$back.'_select'),
                            "ONSELECT" => "OnSelectBGColor".$this->getId()
                        ),
                        $component, array("HIDE_ICONS" => "Y")
                    );
                    echo '<input type="text" class="options-block-custom" id="'.$back.$this->getId().'" minlength="4" maxlength="7" placeholder="'.Loc::getMessage(\SotbitOrigami::moduleId.'_'.$back.'_descr').'" value="'.$val.'"></div>';


                    break;

                case 'background-image':
                    echo '<div class="landing-ui-field">'.Loc::getMessage(\SotbitOrigami::moduleId.'_'.$back);
                    $APPLICATION->IncludeComponent("bitrix:main.file.input", "origami_block",
                        array(
                            "INPUT_NAME"=>"BACKGROUND_IMAGE_".$this->getId(),
                            "MULTIPLE"=>"N",
                            "MODULE_ID"=>"kit.origami",
                            "MAX_FILE_SIZE"=>"2097152",
                            "ALLOW_UPLOAD"=>"I",
                            "ALLOW_UPLOAD_EXT"=>"",
                            "INPUT_CAPTION" => Loc::getMessage(\SotbitOrigami::moduleId.'_'.$back.'_add'),
                            "INPUT_VALUE" => $val,
                            "id" => $this->getId(),
                            "code" =>  $this->getCode(),
                            "action" => $back,
                            "part" => $this->getPart(),
                            "site" => SITE_ID,
                            "siteTemplate" => SITE_TEMPLATE_ID,
                            "page" => $this->getPage()
                        ),
                        false, array("HIDE_ICONS" => "Y")
                    );
                    echo '<input type="hidden" id="file_id_'.$val.'"value="'.$this->getId().'" /></div>';
                    echo '<script>
                        BX.addCustomEvent("onUploadDone", BX.delegate(function (params) {
                            let v = params.file_id;
                            let vID = "BACKGROUND_IMAGE_'.$this->getId().'";
                            let vName = $("input[type=hidden][value="+v+"]").attr("name");
                            if(vName != vID) return true;

                            $("input[type=hidden][value='.$this->getId().']").attr("id", "file_id_"+v);

	                        $.ajax({
								type: "POST",
								url: "/local/components/kit/block.include/ajax.php",
								async:false,
								data: {
									"id": "'.$this->getId().'",
									"code": "'.$this->getCode().'",
									"action":"'.$back.'",
									"value":v,
									"part":"'.$this->getPart().'",
									"site":"'.SITE_ID.'",
									"siteTemplate":"'.SITE_TEMPLATE_ID.'",
									"page":"'.$this->getPage().'"
								},
								success: function(data) {
								    if($(".block-wrapper[data-id=\''.$this->getId().'\']").length)
                                    {
                                        $(".block-wrapper[data-id=\''.$this->getId().'\']").after(data);
                                        $(".block-wrapper[data-id=\''.$this->getId().'\']:first").remove();
                                    }
                                    else
                                    {
                                        if($(".block-wrapper[data-copyof=\''.$this->getId().'\']").length)
                                        {
                                            $(".block-wrapper[data-copyof=\''.$this->getId().'\']").after(data);
                                            $(".block-wrapper[data-copyof=\''.$this->getId().'\']:first").remove();
                                        }
                                    }
								},
							});
                        }));
                        </script>';
                    break;

                case 'background-position':
                    echo '<div class="landing-ui-field"><label for="'.$back.$this->getId().'">'
                        .Loc::getMessage(\SotbitOrigami::moduleId.'_'.$back).':</label>
			            <input type="text" class="options-block-custom" id="'.$back.$this->getId().'" minlength="4" maxlength="7" placeholder="'.Loc::getMessage(\SotbitOrigami::moduleId.'_'.$back.'_descr').'" value="'.$val.'">
			            <button class="landing-ui-design__btn_apply" id="but_'.$back.$this->getId().'" type="button">'.Loc::getMessage(\SotbitOrigami::moduleId.'_APPLY').'</button></div>';
                    break;

                case 'background-repeat':
                    $options = array(
                        "no-repeat", "repeat", "repeat-x", "repeat-y", ""
                    );
                    echo '<div class="landing-ui-field">'.Loc::getMessage(\SotbitOrigami::moduleId.'_'.$back).'
                                        <select id="'.$back.$this->getId().'" class="landing-ui-field-select">';

                    foreach($options as $v)
                    {
                        $selected = '';
                        if($v == $val) $selected = 'selected';
                        echo '<option value="'.$v.'" '.$selected.'>'.$v.'</option>';
                    }
                    echo '</select></div>';
                    break;

                case 'background-size':
                    $options = array(
                        "cover", "contain", "auto"
                    );
                    echo '<div class="landing-ui-field">'.Loc::getMessage(\SotbitOrigami::moduleId.'_'.$back).'
                                        <select id="'.$back.$this->getId().'" class="landing-ui-field-select">';

                    foreach($options as $v)
                    {
                        $selected = '';
                        if($v == $val) $selected = 'selected';
                        echo '<option value="'.$v.'"'.$selected.'>'.$v.'</option>';
                    }
                    echo '</select>';

                    echo '<label for="'.$back.$this->getId().'_abs">'
                        .Loc::getMessage(\SotbitOrigami::moduleId.'_'.$back.'_abs').':</label>
			            <input type="text" class="options-block-custom" id="'.$back.$this->getId().'_abs" minlength="4" maxlength="7" placeholder="'.Loc::getMessage(\SotbitOrigami::moduleId.'_'.$back.'_abs_descr').'" name="" value="">
			            <button class="landing-ui-design__btn_apply" id="but_'.$back.$this->getId().'" type="button">'.Loc::getMessage(\SotbitOrigami::moduleId.'_APPLY').'</button></div>';
                    break;

                case 'background-attachment':
                    $options = array(
                        "fixed", "scroll", "local", ""
                    );
                    echo '<div class="landing-ui-field">'.Loc::getMessage(\SotbitOrigami::moduleId.'_'.$back).'
                                        <select id="'.$back.$this->getId().'" class="landing-ui-field-select">';

                    foreach($options as $v)
                    {
                        $selected = '';
                        if($v == $val) $selected = 'selected';
                        echo '<option value="'.$v.'"'.$selected.'>'.$v.'</option>';
                    }
                    echo '</select></div>';
                    break;

                case 'background-clip':
                case 'background-origin':
                    $options = array(
                        "padding-box", "border-box", "content-box", ""
                    );
                    echo '<div class="landing-ui-field">'.Loc::getMessage(\SotbitOrigami::moduleId.'_'.$back).'
                                        <select id="'.$back.$this->getId().'" class="landing-ui-field-select">';

                    foreach($options as $v)
                    {
                        $selected = '';
                        if($v == $val) $selected = 'selected';
                        echo '<option value="'.$v.'"'.$selected.'>'.$v.'</option>';
                    }
                    echo '</select></div>';
                    break;
            }

            echo '<script>
                    $( function() {
                        $( "#'.$back.$this->getId().'").change(function(){

                            $.ajax({
								type: "POST",
								url: "/local/components/kit/block.include/ajax.php",
								async:false,
								data: {
									"id": "'.$this->getId().'",
									"code": "'.$this->getCode().'",
									"action":"'.$back.'",
									"value":$(this).val(),
									"part":"'.$this->getPart().'",
									"site":"'.SITE_ID.'",
									"siteTemplate":"'.SITE_TEMPLATE_ID.'",
									"page":"'.$this->getPage().'"
								},
								success: function(data) {
								    if($(".block-wrapper[data-id=\''.$this->getId().'\']").length)
                                    {
                                        $(".block-wrapper[data-id=\''.$this->getId().'\']").after(data);
                                        $(".block-wrapper[data-id=\''.$this->getId().'\']:first").remove();
                                    }
                                    else
                                    {
                                        if($(".block-wrapper[data-copyof=\''.$this->getId().'\']").length)
                                        {
                                            $(".block-wrapper[data-copyof=\''.$this->getId().'\']").after(data);
                                            $(".block-wrapper[data-copyof=\''.$this->getId().'\']:first").remove();
                                        }
                                    }
								},
							});
                        });

                        $( "#but_'.$back.$this->getId().'").click(function(){
                            $.ajax({
								type: "POST",
								url: "/local/components/kit/block.include/ajax.php",
								async:false,
								data: {
									"id": "'.$this->getId().'",
									"code": "'.$this->getCode().'",
									"action":"'.$back.'",
									"value":$(this).prev().val(),
									"part":"'.$this->getPart().'",
									"site":"'.SITE_ID.'",
									"siteTemplate":"'.SITE_TEMPLATE_ID.'",
									"page":"'.$this->getPage().'"
								},
								success: function(data) {
								    if($(".block-wrapper[data-id=\''.$this->getId().'\']").length)
                                    {
                                        $(".block-wrapper[data-id=\''.$this->getId().'\']").after(data);
                                        $(".block-wrapper[data-id=\''.$this->getId().'\']:first").remove();
                                    }
                                    else
                                    {
                                        if($(".block-wrapper[data-copyof=\''.$this->getId().'\']").length)
                                        {
                                            $(".block-wrapper[data-copyof=\''.$this->getId().'\']").after(data);
                                            $(".block-wrapper[data-copyof=\''.$this->getId().'\']:first").remove();
                                        }
                                    }
								},
							});
                        });

                    })
                </script>';

        }

        echo '</div></div>
			<button type="button" class="landing-ui-button landing-ui-panel-content-close" data-id="close" title="'
            .Loc::getMessage(\SotbitOrigami::moduleId.'_CLOSE')
            .'"><span class="landing-ui-button-text"></span></button></div></div>';



    }

    public function show($canChange = true, $hideAssets = false)
    {
        global $APPLICATION;
        global $USER;
        global $settings;

        self::includeClass();
        if(!$hideAssets)
            self::includeAssets();

        $settings = $this->getSettings();

        $style = '';
        $padding = '';
        if ($settings['style']['padding-top']['value']) {
            $padding .= $settings['style']['padding-top']['value'].'px ';
        } else {
            $padding .= '0px ';
        }
        if ($settings['style']['padding-right']['value']) {
            $padding .= $settings['style']['padding-right']['value'].'px ';
        } else {
            $padding .= '0px ';
        }
        if ($settings['style']['padding-bottom']['value']) {
            $padding .= $settings['style']['padding-bottom']['value'].'px ';
        } else {
            $padding .= '0px ';
        }
        if ($settings['style']['padding-left']['value']) {
            $padding .= $settings['style']['padding-left']['value'].'px ';
        } else {
            $padding .= '0px ';
        }
        if ($padding) {
            $style .= 'padding: '.$padding.'; ';
        }

        $background = "";

        if ($settings['style']['background-color']['value']) {
            $background .= 'background-color: '.$settings['style']['background-color']['value'].';';
        }
        if ($settings['style']['background-image']['value'] > 0) {
            $imgPath = \CFile::GetPath($settings['style']['background-image']['value']);
            $background .= 'background-image: url('.$imgPath.');';
        }
        if ($settings['style']['background-position']['value']) {
            $background .= 'background-position: '.$settings['style']['background-position']['value'].';';
        }
        if ($settings['style']['background-repeat']['value']) {
            $background .= 'background-repeat: '.$settings['style']['background-repeat']['value'].';';
        }
        if ($settings['style']['background-size']['value']) {
            $background .= 'background-size: '.$settings['style']['background-size']['value'].';';
        }
        if ($settings['style']['background-attachment']['value']) {
            $background .= 'background-attachment: '.$settings['style']['background-attachment']['value'].';';
        }
        if ($settings['style']['background-clip']['value']) {
            $background .= 'background-clip: '.$settings['style']['background-clip']['value'].';';
        }
        if ($settings['style']['background-origin']['value']) {
            $background .= 'background-origin: '.$settings['style']['background-origin']['value'].';';
        }

        if ($background) {
            $style .= $background;
        }

        $lgnone = '';
        if ($this->getSettings()['style']['l-d-lg-none']['value']) {
            $lgnone = 'l-d-lg-none';
        }
        $mdnone = '';
        if ($this->getSettings()['style']['l-d-md-none']['value']) {
            $mdnone = 'l-d-md-none';
        }
        $xsnone = '';
        if ($this->getSettings()['style']['l-d-xs-none']['value']) {
            $xsnone = 'l-d-xs-none';
        }

        $active = '';
        if (!$this->isActive()) {
            $active = 'landing-block-disabled';
        }

        if(!$canChange)
        {
            $detect = new MobileDetect;
            if ($this->getSettings()['style']['l-d-xs-none']['value'] && $detect->isMobile())
                return false;

            if ($this->getSettings()['style']['l-d-md-none']['value'] && $detect->isTablet())
                return false;

            if ($this->getSettings()['style']['l-d-lg-none']['value'] && !$detect->isTablet() && !$detect->isTablet())
                return false;
        }

        echo '<div class="block-wrapper ', ($canChange)
            ? 'block-wrapper-changed'
            : '', '" data-id="', $this->getId(), '" data-copyof="'
	        .$this->getCopyOf()
	        .'" >';
        echo '<div class="block-wrapper-inner ', ($canChange)
            ? 'block-wrapper-inner-changed' : '', $lgnone, ' '.
            $mdnone, ' ', $xsnone.' ', $active
        , '" style="', $style.'">';

        $content = $this->getDir().'/content.php';

        if (strpos($_SERVER['DOCUMENT_ROOT'], $content) === false) {
            $content = $_SERVER['DOCUMENT_ROOT'].$content;
        }
        if (file_exists($content)) {
            include $_SERVER['DOCUMENT_ROOT'].$this->getDir().'/content.php';
        }

        if ($canChange)
        {
            echo '
			<div data-id="content_actions" class="landing-ui-panel landing-ui-panel-content-action landing-ui-show">
				<button type="button" class="landing-ui-button landing-ui-button-action" data-id="collapse" title="'
                .Loc::getMessage(\SotbitOrigami::moduleId.'_COLLAPSE').'">
					<span class="landing-ui-button-text">
						<span class="fa fa-caret-right"></span>
					</span>
				</button>';
            if ($this->getSettings()['groups']) {
                echo '
				<button type="button" class="landing-ui-button landing-ui-button-action" data-id="content" title="'
                    .Loc::getMessage(\SotbitOrigami::moduleId
                        .'_EDIT_TITLE').'">
					<span class="landing-ui-button-text">'
                    .Loc::getMessage(\SotbitOrigami::moduleId
                        .'_EDIT_CONTENT').'</span>
				</button>';
            }
            echo '
				<button type="button" class="landing-ui-button landing-ui-button-action" data-id="style" title="'
                .Loc::getMessage(\SotbitOrigami::moduleId.'_EDIT_STYLE_TITLE')
                .'">
					<span class="landing-ui-button-text">'
                .Loc::getMessage(\SotbitOrigami::moduleId.'_EDIT_STYLE').'</span>
				</button>
				<button type="button" class="landing-ui-button landing-ui-button-action" data-id="block_display_info">
					<span class="landing-ui-button-text">&nbsp;</span>
				</button>
			</div>


<form id="'.$this->getId().'" method="POST" action="javascript:void(null);">
<div data-id="content_edit"
class="landing-ui-panel landing-ui-panel-content landing-ui-panel-content-with-subtitle landing-ui-panel-content-edit landing-ui-hide landing-ui-hidden" data-is-shown="true">
<div class="landing-ui-panel-content-element landing-ui-panel-content-header">
<div class="landing-ui-panel-content-title">'
                .Loc::getMessage(\SotbitOrigami::moduleId.'_EDIT').'</div>
<div class="landing-ui-panel-content-subtitle">'
                .$this->getSettings()['block']['name'].'</div></div>
<div class="landing-ui-panel-content-element landing-ui-panel-content-body"><div class="landing-ui-panel-content-body-sidebar">';

            if ($this->getSettings()['groups']) {
                $i = 0;
                foreach ($this->getSettings()['groups'] as $gCode => $group) {
                    echo '<button type="button" class="landing-ui-button landing-ui-button-sidebar ', ($i
                        == 0) ? 'landing-ui-active' : '', '"
					data-id="settingsTab" data-code="'.$gCode
                        .'"><span class="landing-ui-button-text">'
                        .$group['name'].
                        '</span></button>';
                    ++$i;
                }
            }

            echo '</div>
<div class="landing-ui-panel-content-body-content"><div class="landing-ui-form"><div class="landing-ui-form-body">';

            if ($this->getSettings()['groups'])
            {
                $i = 0;
                foreach ($this->getSettings()['groups'] as $gCode => $group)
                {
                    ?>
					<div class="settings-config-block <?= ($i == 0) ? 'settings-config-block-show' : 'settings-config-block-hide' ?>" data-code="<?= $gCode ?>">
                        <?
                        foreach ($this->getSettings()['fields'] as $fCode => $field)
                        {
                            if ($field['group'] == $gCode)
                            {
                                switch ($field['type'])
                                {
                                    case 'input':
                                        echo '<div class="landing-ui-field"><div class="landing-ui-field-header">'.$field['name'].'</div>
                                        <input name ="'.$fCode.'" type="text" class="landing-ui-field-input" value="';
                                        if (isset($field['value'])) {
                                            echo $field['value'];
                                        } elseif (isset($field['default'])) {
                                            echo $field['default'];
                                        }
                                        echo '"></div>';
                                        break;
                                    case 'select':
                                        echo '<div class="landing-ui-field"><div class="landing-ui-field-header">'.$field['name'].'</div>
                                        <select name ="'.$fCode.'" class="landing-ui-field-select">';
                                        if (isset($field['values'])) {
                                            foreach ($field['values'] as $key => $option) {
                                                $selected = '';
                                                if($key == $field['value']) $selected = 'selected';
                                                echo '<option value="'.$key.'"'.$selected.'>'.$option.'</option>';
                                            }
                                        } elseif (isset($field['value'])) {
                                            echo $field['value'];
                                        }
                                        echo '</select></div>';
                                        break;
                                }
                            }
                        }
                        ?>
					</div>
                    <?
                    ++$i;
                }
            }
            echo '
</div><div class="landing-ui-form-footer"></div></div>
</div></div><div class="landing-ui-panel-content-element landing-ui-panel-content-footer"><button type="button" class="landing-ui-button landing-ui-button-content-save" data-id="save_block_content" title="'
                .Loc::getMessage(\SotbitOrigami::moduleId.'_SAVE')
                .'"><span class="landing-ui-button-text">'
                .Loc::getMessage(\SotbitOrigami::moduleId.'_SAVE').'</span></button>
<button type="button" class="landing-ui-button landing-ui-button-content-cancel" data-id="cancel_block_content" title="'
                .Loc::getMessage(\SotbitOrigami::moduleId.'_CLOSE')
                .'"><span class="landing-ui-button-text">'
                .Loc::getMessage(\SotbitOrigami::moduleId.'_CLOSE').'</span></button></div>
<button type="button" class="landing-ui-button landing-ui-panel-content-close" data-id="close" title="'
                .Loc::getMessage(\SotbitOrigami::moduleId.'_CLOSE').'"><span class="landing-ui-button-text"></span></button></div>
</form>';


            echo '

<div data-id="block_action" class="landing-ui-panel landing-ui-panel-block-action landing-ui-show">
<button type="button" class="landing-ui-button landing-ui-button-action" data-id="down" title="'
                .Loc::getMessage
                (\SotbitOrigami::moduleId.'_DOWN').'">
<span class="landing-ui-button-text">&nbsp;</span></button>
<button type="button" class="landing-ui-button landing-ui-button-action" data-id="up" title="'
                .Loc::getMessage
                (\SotbitOrigami::moduleId.'_UP').'"><span class="landing-ui-button-text">&nbsp;</span></button>
<button type="button" class="landing-ui-button landing-ui-button-action" data-id="actions"
title="'.Loc::getMessage(\SotbitOrigami::moduleId.'_ADD_ACTIONS')
                .'" id="actions_'.$this->getId()
                .'"><span class="landing-ui-button-text">'
                .Loc::getMessage(\SotbitOrigami::moduleId.'_ACTIONS').'</span></button>
<button type="button" class="landing-ui-button landing-ui-button-action" data-id="remove" title="'
                .Loc::getMessage
                (\SotbitOrigami::moduleId.'_DELETE').'"><span class="landing-ui-button-text">&nbsp;</span></button>
<button type="button" class="landing-ui-button landing-ui-button-action" data-id="collapse" title="'
                .Loc::getMessage
                (\SotbitOrigami::moduleId.'_COLLAPSE').'">
<span class="landing-ui-button-text">
<span class="fa fa-caret-right"></span></span>
</button></div>




<div data-id="create_action" class="landing-ui-panel landing-ui-panel-create-action landing-ui-show">
			<button type="button" class="landing-ui-button landing-ui-button-plus" data-id="insert_after">
				<span class="landing-ui-button-text">
					'.Loc::getMessage(\SotbitOrigami::moduleId.'_ADD').'
				</span>
			</button>
		</div>';
        }
        echo '</div></div>';
    }

    public function setSettings($settings = [])
    {
        if ($settings)
        {
            $this->settings = $settings;
            if(file_exists($_SERVER['DOCUMENT_ROOT'].$this->getDir().'/settings.php'))
            {
            	file_put_contents($_SERVER['DOCUMENT_ROOT'].$this->getDir().'/settings.php', serialize($settings));
            }
        } else {

            if (file_exists($_SERVER['DOCUMENT_ROOT'].$this->getDir().'/settings.php'))
            {
                $this->settings = unserialize(file_get_contents($_SERVER['DOCUMENT_ROOT'].$this->getDir().'/settings.php'));
            }
            if (!is_array($this->settings))
            {
                if (file_exists($_SERVER['DOCUMENT_ROOT'].$this->getDir().'/settings.php'))
                {
                    $this->settings = include $_SERVER['DOCUMENT_ROOT'].$this->getDir().'/settings.php';
                }
            }
        }
    }


    public function delete()
    {
        if (strpos($this->getId(), 'tmp') !== false) {
            $this->rmDir();
        } else {
            BlockTable::delete($this->getId());
            $this->rmDir();
        }
    }

    public function getBackgroundImage($img)
    {
        $path = $_SERVER["DOCUMENT_ROOT"].\CFile::GetPath($img);

        if(file_exists($path))
        {
            $arImg = pathinfo($path);
            $jpg = $arImg["extension"];
            $imageString = file_get_contents($path);
            $newPath = $this->getDir().'/background.'.$jpg;
            $file = file_put_contents($_SERVER['DOCUMENT_ROOT'].$newPath, $imageString);

            if($file)
            {
                $path = \CFile::Delete($img);
                return $newPath;
            }
        }

        return false;



    }

    public function save($page)
    {

        global $USER;
        if (strpos($this->getId(), 'tmp') !== false) {
            $block = [
                'CODE'        => $this->getCode(),
                'PART'        => $this->getPart(),
                'SORT'        => $this->getSort(),
                'ACTIVE'      => $this->getActive(),
                'CREATED_BY'  => $USER->GetID(),
                'DATE_CREATE' => new DateTime(date('Y-m-d H:i:s'),
                    'Y-m-d H:i:s'),
                'MODIFIED_BY' => $USER->GetID(),
                'DATE_MODIFY' => new DateTime(date('Y-m-d H:i:s'),
                    'Y-m-d H:i:s'),
            ];
            $rs = BlockTable::add($block);
            if ($rs->isSuccess()) {
                $this->setId($rs->getId());

                if (!is_dir($_SERVER['DOCUMENT_ROOT'].$page.'/blocks/')) {
                    mkdir($_SERVER['DOCUMENT_ROOT'].$page.'/blocks/');
                }

                mkdir($_SERVER['DOCUMENT_ROOT'].$page.'/blocks/'
                    .$this->getCode().'_'.$rs->getId());
                copy($_SERVER['DOCUMENT_ROOT'].$this->getDir().'/content.php',
                    $_SERVER['DOCUMENT_ROOT'].$page.'/blocks/'.$this->getCode()
                    .'_'
                    .$rs->getId().'/content.php');
                copy($_SERVER['DOCUMENT_ROOT'].$this->getDir().'/settings.php',
                    $_SERVER['DOCUMENT_ROOT'].$page.'/blocks/'.$this->getCode()
                    .'_'
                    .$rs->getId().'/settings.php');
                $files = scandir($_SERVER['DOCUMENT_ROOT'].$this->getDir());
                foreach ($files as $file) {
                    if ($file != "." && $file != "..") {
                        unlink($_SERVER['DOCUMENT_ROOT'].$this->getDir()
                            .DIRECTORY_SEPARATOR.$file);
                    }
                }
                rmdir($_SERVER['DOCUMENT_ROOT'].$this->getDir());
                $this->setDir($page.'blocks/'.$this->getCode().'_'
                    .$this->getId());
            } else {
                print_r($rs->getErrorMessages());
            }
        } else {
            $block = [
                'CODE'        => $this->getCode(),
                'PART'        => $this->getPart(),
                'SORT'        => $this->getSort(),
                'ACTIVE'      => $this->getActive(),
                'MODIFIED_BY' => $USER->GetID(),
                'DATE_MODIFY' => new DateTime(date('Y-m-d H:i:s'),
                    'Y-m-d H:i:s'),
            ];
            $rs = BlockTable::update($this->getId(), $block);
            if ($rs->isSuccess()) {

            }
        }
    }


    public function setContent()
    {
        if (file_exists($_SERVER['DOCUMENT_ROOT'].$this->getDir()
            .'/content.php')
        ) {
            $this->content = file_get_contents($_SERVER['DOCUMENT_ROOT']
                .$this->getDir().'/content.php');
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * @return string
     */
    public function getPart()
    {
        return $this->part;
    }

    /**
     * @param string $part
     */
    public function setPart($part)
    {
        $this->part = $part;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        if ($this->active == 'Y') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param bool $active
     */
    public function setActive($active = 'Y')
    {
        $this->active = $active;
    }

    public function getActive()
    {
        return $this->active;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @param string $dir
     */
    public function setDir($dir = '')
    {
        if (!$dir) {
            $FrontUser = new User();
            $frontDir = $FrontUser->getFolder();
            $rand = rand();
            if (!is_dir($_SERVER['DOCUMENT_ROOT'].$frontDir)) {
                mkdir($_SERVER['DOCUMENT_ROOT'].$frontDir);
            }
            if (!is_dir($_SERVER['DOCUMENT_ROOT'].$frontDir.'/blocks')) {
                mkdir($_SERVER['DOCUMENT_ROOT'].$frontDir.'/blocks');
            }
            if (!is_dir(
                $_SERVER['DOCUMENT_ROOT'].$frontDir.'/blocks/'.$this->getPart())
            ) {
                mkdir(
                    $_SERVER['DOCUMENT_ROOT'].$frontDir.'/blocks/'.$this->getPart()
                );
            }
            if (!is_dir(
                $_SERVER['DOCUMENT_ROOT'].$frontDir.'/blocks/'.$this->getPart()
                .'/'.$rand)
            ) {
                mkdir($_SERVER['DOCUMENT_ROOT'].$frontDir.'/blocks/'
                    .$this->getPart().'/'.$rand);
            }
            $this->dir = $frontDir.'/blocks/'.$this->getPart().'/'.$rand;

            $siteDir = SITE_DIR;
            if(is_null(SITE_DIR))
            {
				$rs = SiteTable::getList(['select' => ['DIR']]);
				while($site = $rs->fetch())
				{

					if(is_dir($_SERVER['DOCUMENT_ROOT'].$site['DIR'].'include/blocks/'))
					{
                        $siteDir = $site['DIR'];
					}
				}
            }
            copy(
                $_SERVER['DOCUMENT_ROOT'].$siteDir.'include/blocks/'.$this->getCode().'/content.php',
                $_SERVER['DOCUMENT_ROOT'].$frontDir.'/blocks/'.$this->getPart()
                .'/'.$rand
                .'/content.php'
            );

            $settings = include $_SERVER['DOCUMENT_ROOT']
	            .$siteDir.'include/blocks/'.$this->getCode().'/settings.php';
            if(!is_array($settings))
            {
                $settings = [];
            }
            file_put_contents(
                $_SERVER['DOCUMENT_ROOT'].$frontDir.'/blocks/'.$this->getPart()
                .'/'.$rand
                .'/settings.php',
                serialize($settings)
            );
            $this->setId('tmp'.$rand);
        } else {
            $this->dir = $dir;
        }
    }

    /**
     *
     */
    public function setClass()
    {
        self::includeClass();
        $classFile = $_SERVER['DOCUMENT_ROOT'].\SotbitOrigami::blockDir.'/'
            .$this->getCode().'/class.php';
        if (file_exists($classFile)) {
            $fp = fopen($classFile, 'r');
            $class = $buffer = '';
            $i = 0;
            while (!$class) {
                if (feof($fp)) {
                    break;
                }

                $buffer .= fread($fp, 512);
                $tokens = token_get_all($buffer);

                if (strpos($buffer, '{') === false) {
                    continue;
                }

                for (; $i < count($tokens); $i++) {
                    if ($tokens[$i][0] === T_CLASS) {
                        for ($j = $i + 1; $j < count($tokens); $j++) {
                            if ($tokens[$j] === '{') {
                                $class = $tokens[$i + 2][1];
                            }
                        }
                    }
                }
            }
            $this->class = $class;
        }

        if (!$this->class) {
            $this->class = '\\Sotbit\\Origami\\Actions';
        }
    }

    /**
     * @return string
     */
    public function getClass()
    {
        self::includeClass();

        return $this->class;
    }

    private function includeClass()
    {
        $classFile = $_SERVER['DOCUMENT_ROOT'].\SotbitOrigami::blockDir.'/'
            .$this->getCode().'/class.php';
        if (file_exists($classFile)) {
            include_once $classFile;
        }
    }

    private function rmDir()
    {
        if (is_dir($_SERVER['DOCUMENT_ROOT'].$this->getDir())) {
            $files = scandir($_SERVER['DOCUMENT_ROOT'].$this->getDir());
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    unlink($_SERVER['DOCUMENT_ROOT'].$this->getDir()
                        .DIRECTORY_SEPARATOR.$file);
                }
            }
            rmdir($_SERVER['DOCUMENT_ROOT'].$this->getDir());
        }
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * @return mixed
     */
    public function getCopyOf()
    {
        return $this->copyOf;
    }

    /**
     * @param mixed $copyOf
     */
    public function setCopyOf($copyOf)
    {
        $this->copyOf = $copyOf;
    }

    /**
     * @return string
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param string $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }
}

?>
