<?php
namespace intec\constructor\handlers;

use intec\Core;
use intec\core\base\InvalidParamException;
use intec\core\db\ActiveRecord;
use intec\core\handling\Actions;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\Json;
use intec\core\helpers\Type;
use intec\constructor\structure\Block as Model;
use intec\constructor\structure\BlockInterface as ModelInterface;

class BlockActions extends Actions
{

    /**
     * @var ActiveRecord|ModelInterface
     */
    public $record;

    public function beforeAction($action)
    {
        if (parent::beforeAction($action))
        {
            global $record;

            $this->record = $record;

            return true;
        }

        return false;
    }

    public function actionSave()
    {
        $request = Core::$app->request;
        $data = $request->post('data');

        try {
            $data = Json::decode($data);
        } catch (InvalidParamException $exception) {
            return false;
        }

        if (!Type::isArray($data))
            return false;

        $data = ArrayHelper::convertEncoding($data, null, Encoding::UTF8);
        $model = Model::from($data, $this->record->getResources());

        $this->record->setAttribute('data', $model->getStructure());
        $this->record->save();

        return true;
    }
}