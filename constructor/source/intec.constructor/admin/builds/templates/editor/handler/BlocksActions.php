<?php
namespace intec\constructor\handlers;

use intec\Core;
use intec\core\handling\Actions;
use intec\constructor\models\block\Template as BlockTemplate;
use intec\constructor\models\Build;
use intec\constructor\models\build\Template;
use intec\constructor\models\build\template\Block;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\StringHelper;

class BlocksActions extends Actions
{
    /**
     * @var Build
     */
    public $build;
    /**
     * @var Template
     */
    public $template;

    public function beforeAction($action)
    {
        if (parent::beforeAction($action))
        {
            global $build;
            global $template;

            $this->build = $build;
            $this->template = $template;

            return true;
        }

        return false;
    }

    public function actionCreate()
    {
        $request = Core::$app->request;

        $template = $request->post('template');
        $template = StringHelper::convert($template, null, Encoding::UTF8);
        $template = BlockTemplate::findOne($template);

        if (empty($template))
            return null;

        $block = new Block();
        $block->containerId = 0;
        $block->templateId = $this->template->id;
        $block->populateRelation('template', $this->template);

        if (!$block->importFrom($template))
            return null;

        return $block->getStructure();
    }

    public function actionCopy()
    {
        $request = Core::$app->request;

        $block = $request->post('block');
        $block = StringHelper::convert($block, null, Encoding::UTF8);

        /** @var Block $block */
        $block = Block::findOne($block);

        if (empty($block))
            return null;

        $copy = new Block();
        $copy->containerId = 0;
        $copy->populateRelation('template', $this->template);

        if (!$copy->importFrom($block))
            return null;

        return $copy->getStructure();
    }

    public function actionConvert()
    {
        $request = Core::$app->request;

        $code = $request->post('code');
        $name = $request->post('name');
        $block = $request->post('block');

        $code = StringHelper::convert($code, null, Encoding::UTF8);
        $name = StringHelper::convert($name, null, Encoding::UTF8);
        $block = StringHelper::convert($block, null, Encoding::UTF8);

        /** @var Block $block */
        $block = Block::findOne($block);

        if (empty($block))
            return [
                'result' => false,
                'error' => 'Block not found'
            ];

        $template = new BlockTemplate();
        $template->code = $code;

        if ($block->exportTo($template)) {
            if (!empty($name)) {
                $template->name = $name;
                $template->save();
            }

            return [
                'result' => true
            ];
        }

        $error = $template->getFirstErrors();
        $error = ArrayHelper::getFirstValue($error);

        return [
            'result' => false,
            'error' => $error
        ];
    }

    public function actionView()
    {
        global $APPLICATION;

        $request = Core::$app->request;

        $block = $request->post('id');
        $block = StringHelper::convert($block, null, Encoding::UTF8);

        /** @var Block $block */
        $block = Block::find()
            ->where([
                'id' => $block
            ])
            ->one();

        if (empty($block))
            die();

        $block->populateRelation('template', $this->template);
        $APPLICATION->ShowAjaxHead();
        $block->render(true, true);

        die();
    }
}