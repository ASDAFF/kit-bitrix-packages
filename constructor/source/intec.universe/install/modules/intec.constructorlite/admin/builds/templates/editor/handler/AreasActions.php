<?php
namespace intec\constructor\handlers;

use intec\constructor\models\build\Area;
use intec\constructor\models\build\template\Containers;
use intec\Core;
use intec\core\handling\Actions;
use intec\constructor\models\Build;

class AreasActions extends Actions
{
    /**
     * @var Build
     */
    public $build;

    public function beforeAction($action)
    {
        if (parent::beforeAction($action))
        {
            global $build;

            $this->build = $build;

            return true;
        }

        return false;
    }

    public function actionStructure()
    {
        $request = Core::$app->request;

        $area = $request->post('id');

        /** @var Area $area */
        $area = Area::find()->where([
            'id' => $area,
            'buildId' => $this->build->id
        ])->one();

        if (empty($area))
            return null;

        $containers = $area->getContainers(false)->with([
            'link',
            'area',
            'component',
            'widget',
            'block',
            'variator',
            'variator.variants'
        ])->all();
        /** @var Containers $containers */

        $container = $containers->getTree($this->build, $area);

        if (empty($container))
            return null;

        return $container->getStructure();
    }

    public function actionList()
    {
        $result = [];

        /** @var Area[] $areas */
        $areas = Area::find()->where([
            'buildId' => $this->build->id
        ])->all();

        foreach ($areas as $area)
            $result[] = [
                'id' => $area->id,
                'name' => $area->name
            ];

        return $result;
    }
}