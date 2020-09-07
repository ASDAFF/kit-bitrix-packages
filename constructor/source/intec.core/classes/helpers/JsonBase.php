<?php
namespace intec\core\helpers;

use intec\core\base\InvalidParamException;
use intec\core\base\Arrayable;
use intec\core\web\JsExpression;

class JsonBase
{
    public static $jsonErrorMessages = [
        'JSON_ERROR_DEPTH' => 'The maximum stack depth has been exceeded.',
        'JSON_ERROR_STATE_MISMATCH' => 'Invalid or malformed JSON.',
        'JSON_ERROR_CTRL_CHAR' => 'Control character error, possibly incorrectly encoded.',
        'JSON_ERROR_SYNTAX' => 'Syntax error.',
        'JSON_ERROR_UTF8' => 'Malformed UTF-8 characters, possibly incorrectly encoded.',
        'JSON_ERROR_RECURSION' => 'One or more recursive references in the value to be encoded.',
        'JSON_ERROR_INF_OR_NAN' => 'One or more NAN or INF values in the value to be encoded',
        'JSON_ERROR_UNSUPPORTED_TYPE' => 'A value of a type that cannot be encoded was given',
    ];

    public static function encode($value, $options = 320, $convert = false)
    {
        $expressions = [];
        $value = static::processData($value, $expressions, uniqid('', true));

        if ($convert)
            $value = Encoding::convert($value, Encoding::UTF8, Encoding::getDefault());

        set_error_handler(function () {
            static::handleJsonError(JSON_ERROR_SYNTAX);
        }, E_WARNING);
        $json = json_encode($value, $options);
        restore_error_handler();
        static::handleJsonError(json_last_error());

        if ($convert)
            $json = Encoding::convert($json, null, Encoding::UTF8);

        return $expressions === [] ? $json : strtr($json, $expressions);
    }

    public static function htmlEncode($value, $convert = true)
    {
        return static::encode($value, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS, $convert);
    }

    public static function decode($json, $asArray = true, $convert = false)
    {
        if (is_array($json)) {
            throw new InvalidParamException('Invalid JSON data.');
        } elseif ($json === null || $json === '') {
            return null;
        }

        if ($convert)
            $json = Encoding::convert($json, Encoding::UTF8, Encoding::getDefault());

        $decode = json_decode((string) $json, $asArray);
        static::handleJsonError(json_last_error());

        if ($convert)
            $decode = Encoding::convert($decode, null, Encoding::UTF8);

        return $decode;
    }

    protected static function handleJsonError($lastError)
    {
        if ($lastError === JSON_ERROR_NONE) {
            return;
        }

        $availableErrors = [];
        foreach (static::$jsonErrorMessages as $const => $message) {
            if (defined($const)) {
                $availableErrors[constant($const)] = $message;
            }
        }

        if (isset($availableErrors[$lastError])) {
            throw new InvalidParamException($availableErrors[$lastError], $lastError);
        }

        throw new InvalidParamException('Unknown JSON encoding/decoding error.');
    }

    protected static function processData($data, &$expressions, $expPrefix)
    {
        if (is_object($data)) {
            if ($data instanceof JsExpression) {
                $token = "!{[$expPrefix=" . count($expressions) . ']}!';
                $expressions['"' . $token . '"'] = $data->expression;

                return $token;
            } elseif ($data instanceof \JsonSerializable) {
                return static::processData($data->jsonSerialize(), $expressions, $expPrefix);
            } elseif ($data instanceof Arrayable) {
                $data = $data->toArray();
            } elseif ($data instanceof \SimpleXMLElement) {
                $data = (array) $data;
            } else {
                $result = [];
                foreach ($data as $name => $value) {
                    $result[$name] = $value;
                }
                $data = $result;
            }

            if ($data === []) {
                return new \stdClass();
            }
        }

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $data[$key] = static::processData($value, $expressions, $expPrefix);
                }
            }
        }

        return $data;
    }
}
