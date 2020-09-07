<?php
namespace intec\constructor\handlers;

use intec\Core;
use intec\core\handling\Actions;
use intec\core\helpers\Encoding;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\constructor\models\Build;
use intec\constructor\models\build\Gallery;
use intec\constructor\models\build\gallery\File;
use intec\core\net\Url;
use intec\core\web\UploadedFile;

class GalleryActions extends Actions
{
    /**
     * @var Build
     */
    public $build;
    /**
     * @var Gallery
     */
    public $gallery;

    public function beforeAction($action)
    {
        if (parent::beforeAction($action))
        {
            global $build;

            $this->build = $build;
            $this->gallery = $build->getGallery();

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
                $file->getPath(
                    Gallery::DIRECTORY_RELATIVE_SITE,
                    '/'
                ),
                '/',
                true
            ),
            'value' => '#TEMPLATE#/'.Url::encodeParts(
                    $file->getPath(
                        Gallery::DIRECTORY_RELATIVE_BUILD,
                        '/'
                    ),
                    '/',
                    true
                )
        ];
    }

    public function actionList()
    {
        /** @var File[] $files */
        $files = $this->gallery->getFiles();
        $result = [];

        foreach ($files as $file) {
            $result[] = $this->getFileStructure($file);
        }

        return $result;
    }

    public function actionUpload()
    {
        $file = UploadedFile::getInstanceByName('file');

        if ($file) {
            $file->name = StringHelper::convert($file->name, null, Encoding::UTF8);
            $file = $this->gallery->addFile($file);
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
                $file = new File($this->gallery, $file);

                if ($file->delete())
                    $result[] = $file->getName();
            }

        return $result;
    }
}