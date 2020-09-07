<?php
namespace intec\core\bitrix\web;

use \Exception;
use intec\Core;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Type;
use intec\core\processing\scss\Compiler;

class Scss
{
    /**
     * Возвращает компилятор scss файлов.
     * @return Compiler
     */
    protected function getCompiler()
    {
        return new Compiler();
    }

    /**
     * Компилирует less строку в css.
     * @param string $string Строка для компиляции.
     * @param array $parameters Параметры для компиляции.
     * @param bool $throwException Вызывать исключение при ошибке.
     * @return string Скомпилированная в css строка.
     * @throws Exception Ошибка при компиляции.
     */
    public function compile($string, $parameters = [], $throwException = false)
    {
        if (!Type::isArray($parameters))
            $parameters = [];

        $compiler = $this->getCompiler();
        $compiler->setVariables($parameters);

        try {
            $result = $compiler->compile($string);
        } catch (Exception $exception) {
            $result = null;

            if ($throwException)
                throw $exception;
        }

        return $result;
    }

    /**
     * Компилирует файл из less в css.
     * @param string $pathFrom Путь до файла, из которого нужно компилировать.
     * @param string|null $pathTo Путь до файла, в который нужно компилировать.
     * Если `null`, то не сохраняет в файл.
     * @param array $parameters Параметры less.
     * @param bool $throwException Вызывать исключение.
     * @return bool|string Содержимое скомпилированного файла.
     * @throws Exception Ошибка компиляции.
     */
    public function compileFile($pathFrom, $pathTo = null, $parameters = [], $throwException = false)
    {
        $result = null;

        if (FileHelper::isFile($pathFrom)) {
            $string = FileHelper::getFileData($pathFrom);

            if ($string) {
                $result = $this->compile($string, $parameters, $throwException);

                if ($pathTo) {
                    FileHelper::setFileData($pathTo, $result);
                }
            }
        }

        return $result;
    }
}