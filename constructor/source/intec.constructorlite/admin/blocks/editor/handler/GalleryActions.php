<?php
namespace intec\constructor\handlers;

use intec\Core;
use intec\core\handling\Actions;
use intec\core\helpers\Encoding;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\core\net\Url;
use intec\core\web\UploadedFile;
use intec\constructor\models\block\Template;
use intec\constructor\structure\block\Resources;
use intec\constructor\structure\block\resources\File;

class GalleryActions extends Actions
{
    /**
     * @var Template
     */
    public $record;
    /**
     * @var Resources
     */
    public $resources;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action))
        {
            global $record;

            $this->record = $record;
            $this->resources = $record->getResources();

            return true;
        }

        return false;
    }

    /**
     * @param File $file
     * @return array
     */
    protected function getFileStructure($file)
    {
        if (!$file instanceof File)
            return null;

        return [
            'name' => $file->getName(),
            'path' => Url::encodeParts(
                $file->getPath(true)->getValue('/'),
                '/',
                true
            ),
            'value' => '#RESOURCES#/'.Url::encode(
                $file->getName(),
                true
            )
        ];
    }

    public function actionList()
    {
        /** @var File[] $files */
        $files = $this->resources->getFiles();
        $result = [];

        foreach ($files as $file)
            $result[] = $this->getFileStructure($file);

        return $result;
    }

    public function actionUpload()
    {
        $file = UploadedFile::getInstanceByName('file');

        if ($file) {
            $file->name = StringHelper::convert($file->name, null, Encoding::UTF8);
            $file = $this->resources->addFile($file);
            return $this->getFileStructure($file);
        }

        return null;
    }

    public function actionDelete()
    {
        $request = Core::$app->request;
        $files = $request->post('files');
        $result = [];

        if (Type::isArray($files))
            foreach ($files as $file) {
                $file = StringHelper::convert($file, null, Encoding::UTF8);
                $file = new File($this->resources, $file);

                if ($file->delete())
                    $result[] = $file->getName();
            }

        return $result;
    }
}