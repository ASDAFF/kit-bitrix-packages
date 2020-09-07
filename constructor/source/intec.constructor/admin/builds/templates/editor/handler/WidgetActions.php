<?php
namespace intec\constructor\handlers;

use intec\Core;
use intec\core\handling\Actions;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\constructor\models\Build;
use intec\constructor\models\build\Template as BuildTemplate;
use intec\constructor\structure\Widget;
use intec\constructor\structure\widget\Template as WidgetTemplate;
use intec\constructor\structure\Widgets;

class WidgetActions extends Actions
{
    /**
     * @var Build
     */
    public $build;
    /**
     * @var BuildTemplate
     */
    public $template;
    /**
     * @var Widget
     */
    public $widget;
    /**
     * @var WidgetTemplate
     */
    public $widgetTemplate;
    /**
     * @var array
     */
    public $properties;

    public function beforeAction($action)
    {
        global $build;
        global $template;

        if (parent::beforeAction($action)) {
            $request = Core::$app->request;

            /** @var Widget $widget */
            $widget = $request->post('widget');
            $widgetTemplate = $request->post('template');
            $properties = $request->post('properties');

            $widgets = Widgets::all();

            if (!empty($widget)) {
                $widget = $widgets->get($widget);
            } else {
                $widget = null;
            }

            if (empty($widget))
                return false;

            if (!Type::isArray($properties))
                $properties = [];

            $widgetTemplate = $widget->getTemplate($widgetTemplate, $build);

            if (empty($widgetTemplate) && $action->id !== 'handle')
                return false;

            $this->build = $build;
            $this->template = $template;
            $this->widget = $widget;
            $this->widgetTemplate = $widgetTemplate;
            $this->properties = $properties;

            return true;
        }

        return false;
    }

    public function actionSettings()
    {
        return $this->widgetTemplate->getSettings();
    }

    public function actionHandle()
    {
        $request = Core::$app->request;
        $parameters = $request->post('parameters');

        if (!Type::isArray($parameters))
            $parameters = [];

        if (empty($this->widgetTemplate)) {
            return $this->widget->runHandler(
                $parameters,
                $this->properties,
                $this->build,
                $this->template
            );
        } else {
            return $this->widgetTemplate->runHandler(
                $parameters,
                $this->properties,
                $this->build,
                $this->template
            );
        }
    }

    public function actionHeaders()
    {
        global $APPLICATION;

        $APPLICATION->ShowAjaxHead();

        $apply = Core::$app->request->post('apply');
        $applyWidget = ArrayHelper::getValue($apply, 'widget') == true;
        $applyTemplate = ArrayHelper::getValue($apply, 'template') == true;

        if ($applyWidget)
            $this->widget->includeHeaders(['editor']);

        if ($applyTemplate)
            $this->widgetTemplate->includeHeaders(['editor']);

        exit();
    }

    public function actionView()
    {
        return $this->widgetTemplate->render(
            $this->properties,
            $this->build,
            $this->template,
            false
        );
    }

    public function actionData()
    {
        return $this->widgetTemplate->getData(
            $this->properties,
            $this->build,
            $this->template
        );
    }
}