<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CJSCore::Init(array("ajax"));

use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 * @var array $arParams
 * @var string $sTemplateId
 */

$arResult["AJAX_PARAMS"] = JavaScript::toObject($arResult["~AJAX_PARAMS"]);

?>
<script>
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var id = root.data('id');
        var voted = <?= (!$arResult["VOTED"] && $arParams["READ_ONLY"]!=="Y") ? 'false' : 'true' ?>;
        var container = $('[data-role="container"]', root);
        var votes = $('[data-role="container.vote"]', container);

        root.update = function (rating) {
            votes.each(function () {
                var vote = $(this);
                var active = vote.data('active');
                if (!active) {
                    if (vote.index() < rating) {
                        vote.attr('data-focus', true);
                    } else {
                        vote.attr('data-focus', false);
                    }
                }
            })
        };

        votes.each(function () {
            var vote = $(this);
            var rating = vote.index() + 1;
            var active = vote.data('active');
            var value = vote.data('value');
            var arParams = <?= $arResult["AJAX_PARAMS"] ?>;

            if (!voted) {
                vote.on('click', function () {

                    arParams['vote'] = 'Y';
                    arParams['vote_id'] = id;
                    arParams['rating'] = value;

                    $.post(
                        '/bitrix/components/bitrix/iblock.vote/component.php',
                        arParams,
                        function (data) {
                            root.html(data);
                        }
                    );
                });

                if (!active) {
                    vote.on('mouseover', function () {
                        root.update(rating);
                    });
                    vote.on('mouseout', function () {
                        root.update(0);
                    });
                }
            }
        });

        root.update(<?= round($sVoteDisplayValue) ?>);

    })(jQuery, intec)
</script>