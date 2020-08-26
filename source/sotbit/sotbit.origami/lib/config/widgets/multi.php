<?php

namespace Sotbit\Origami\Config\Widgets;


use Sotbit\Origami\Config\Widget;

class Multi extends Widget
{
    public function show()
    {
        $massive = $this->getCurrentValue();

        $input = "<script type=\"text/javascript\">
			function ".htmlspecialchars($this->getCode())."(){
				var div = document.createElement(\"div\");
                div.innerHTML = \"<input type='text' name='".htmlspecialchars($this->getCode())."[]' />\";
            document.getElementById('".htmlspecialchars($this->getCode())."').appendChild(div);
			}
		</script>

										<span name='"
            .htmlspecialchars($this->getCode())."' id='"
            .htmlspecialchars($this->getCode())."'>";
        if (isset($massive) && is_array($massive) && (!empty($massive[0]))) {
            foreach ($massive as $element) {
                if (!empty($element)) {
                    $input .= "<input type='text' name='"
                        .htmlspecialchars($this->getCode())."[]' value='"
                        .$element."' /><br />";
                }
            }
        } else {
            $input .= "<input type='text' name='"
                .htmlspecialchars($this->getCode())."[]' value='' /><br />";
        }
        $input .= "</span>
		<input type='button' value='+' onclick=\""
            .htmlspecialchars($this->getCode())."()\">";
        echo $input;
    }
}

?>