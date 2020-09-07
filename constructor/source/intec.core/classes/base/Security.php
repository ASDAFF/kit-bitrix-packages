<?php
namespace intec\core\base;

use intec\core\helpers\StringHelper;

/**
 * Компонент, используемый для защиты приложения.
 * Class Security
 * @package intec\core\base
 * @since 1.0.0
 */
class Security extends Component
{
    /**
     * Чипер, используемый для шифрования и расшифровки.
     * @var string
     * @since 1.0.0
     */
    public $cipher = 'AES-128-CBC';
    /**
     * Доступные чиперы.
     * @var array[]
     * @since 1.0.0
     */
    public $allowedCiphers = [
        'AES-128-CBC' => [16, 16],
        'AES-192-CBC' => [16, 24],
        'AES-256-CBC' => [16, 32],
    ];
    /**
     * Алгоритм шифрования ключей: sha256, sha384, sha512.
     * @var string
     * @since 1.0.0
     */
    public $kdfHash = 'sha256';
    /**
     * Алгоритм шифрования сообщений: sha256, sha384, sha512.
     * @var string
     * @since 1.0.0
     */
    public $macHash = 'sha256';
    /**
     * @var string
     * @since 1.0.0
     */
    public $authKeyInfo = 'AuthorizationKey';
    /**
     * @var int
     * @since 1.0.0
     */
    public $derivationIterations = 100000;
    /**
     * @var string
     * @since 1.0.0
     */
    public $passwordHashStrategy;
    /**
     * @var int Стоимость генерации хэша.
     * Доступны значения от 4 до 31.
     * @see generatePasswordHash()
     * @since 1.0.0
     */
    public $passwordHashCost = 13;


    /**
     * Шифрует данные, использую пароль.
     * @param string $data Данные для шифрования.
     * @param string $password Пароль для шифрования.
     * @return string Шифрованные данные.
     * @see decryptByPassword()
     * @see encryptByKey()
     * @since 1.0.0
     */
    public function encryptByPassword($data, $password)
    {
        return $this->encrypt($data, true, $password, null);
    }

    /**
     * Шифрует данные, используя криптографический ключ.
     * @param string $data Данные для шифрования.
     * @param string $inputKey Криптографический ключ.
     * @param string $info Контекст.
     * @return string Шифрованные данные.
     * @see decryptByKey()
     * @see encryptByPassword()
     * @since 1.0.0
     */
    public function encryptByKey($data, $inputKey, $info = null)
    {
        return $this->encrypt($data, false, $inputKey, $info);
    }

    /**
     * Проверяет и расшифровывает данные, шифрованные методом [[encryptByPassword()]].
     * @param string $data Данные для расшифровки.
     * @param string $password Пароль для расшифровки.
     * @return bool|string Расшифрованные данные или `false`.
     * @see encryptByPassword()
     * @since 1.0.0
     */
    public function decryptByPassword($data, $password)
    {
        return $this->decrypt($data, true, $password, null);
    }

    /**
     * Проверяет и расшифровывает данные, шифрованные методом [[encryptByKey()]].
     * @param string $data Данные для расшифровки.
     * @param string $inputKey Криптографический ключ.
     * @param string $info Контекст.
     * @return bool|string Расшифрованные данные или `false`.
     * @see encryptByKey()
     * @since 1.0.0
     */
    public function decryptByKey($data, $inputKey, $info = null)
    {
        return $this->decrypt($data, false, $inputKey, $info);
    }

    /**
     * Шифрует данные.
     *
     * @param string $data Данные для шифрования.
     * @param bool $passwordBased Базированное на пароле.
     * @param string $secret Пароль или криптографический ключ.
     * @param string $info Контекст.
     * @return string Шифрованные данные.
     * @throws InvalidConfigException OpenSSL не загружен.
     * @throws Exception Ошибка OpenSSL.
     * @see decrypt()
     * @since 1.0.0
     */
    protected function encrypt($data, $passwordBased, $secret, $info)
    {
        if (!extension_loaded('openssl')) {
            throw new InvalidConfigException('Encryption requires the OpenSSL PHP extension');
        }
        if (!isset($this->allowedCiphers[$this->cipher][0], $this->allowedCiphers[$this->cipher][1])) {
            throw new InvalidConfigException($this->cipher . ' is not an allowed cipher');
        }

        list($blockSize, $keySize) = $this->allowedCiphers[$this->cipher];

        $keySalt = $this->generateRandomKey($keySize);
        if ($passwordBased) {
            $key = $this->pbkdf2($this->kdfHash, $secret, $keySalt, $this->derivationIterations, $keySize);
        } else {
            $key = $this->hkdf($this->kdfHash, $secret, $keySalt, $info, $keySize);
        }

        $iv = $this->generateRandomKey($blockSize);

        $encrypted = openssl_encrypt($data, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);
        if ($encrypted === false) {
            throw new Exception('OpenSSL failure on encryption: ' . openssl_error_string());
        }

        $authKey = $this->hkdf($this->kdfHash, $key, null, $this->authKeyInfo, $keySize);
        $hashed = $this->hashData($iv . $encrypted, $authKey);

        /*
         * Output: [keySalt][MAC][IV][ciphertext]
         * - keySalt is KEY_SIZE bytes long
         * - MAC: message authentication code, length same as the output of MAC_HASH
         * - IV: initialization vector, length $blockSize
         */
        return $keySalt . $hashed;
    }

    /**
     * Расшифровывает данные.
     *
     * @param string $data Данные для расшифровки.
     * @param bool $passwordBased Базируется на пароле.
     * @param string $secret Пароль или ключ.
     * @param string $info Контекст, @see encrypt()
     * @return bool|string Расшифрованные данные или `false`
     * @throws InvalidConfigException OpenSSL не загружен.
     * @throws Exception Ошибка OpenSSL.
     * @see encrypt()
     * @since 1.0.0
     */
    protected function decrypt($data, $passwordBased, $secret, $info)
    {
        if (!extension_loaded('openssl')) {
            throw new InvalidConfigException('Encryption requires the OpenSSL PHP extension');
        }
        if (!isset($this->allowedCiphers[$this->cipher][0], $this->allowedCiphers[$this->cipher][1])) {
            throw new InvalidConfigException($this->cipher . ' is not an allowed cipher');
        }

        list($blockSize, $keySize) = $this->allowedCiphers[$this->cipher];

        $keySalt = StringHelper::byteSubstr($data, 0, $keySize);
        if ($passwordBased) {
            $key = $this->pbkdf2($this->kdfHash, $secret, $keySalt, $this->derivationIterations, $keySize);
        } else {
            $key = $this->hkdf($this->kdfHash, $secret, $keySalt, $info, $keySize);
        }

        $authKey = $this->hkdf($this->kdfHash, $key, null, $this->authKeyInfo, $keySize);
        $data = $this->validateData(StringHelper::byteSubstr($data, $keySize, null), $authKey);
        if ($data === false) {
            return false;
        }

        $iv = StringHelper::byteSubstr($data, 0, $blockSize);
        $encrypted = StringHelper::byteSubstr($data, $blockSize, null);

        $decrypted = openssl_decrypt($encrypted, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);
        if ($decrypted === false) {
            throw new Exception('OpenSSL failure on decryption: ' . openssl_error_string());
        }

        return $decrypted;
    }

    public function hkdf($algo, $inputKey, $salt = null, $info = null, $length = 0)
    {
        $test = @hash_hmac($algo, '', '', true);
        if (!$test) {
            throw new InvalidParamException('Failed to generate HMAC with hash algorithm: ' . $algo);
        }
        $hashLength = StringHelper::byteLength($test);
        if (is_string($length) && preg_match('{^\d{1,16}$}', $length)) {
            $length = (int) $length;
        }
        if (!is_int($length) || $length < 0 || $length > 255 * $hashLength) {
            throw new InvalidParamException('Invalid length');
        }
        $blocks = $length !== 0 ? ceil($length / $hashLength) : 1;

        if ($salt === null) {
            $salt = str_repeat("\0", $hashLength);
        }
        $prKey = hash_hmac($algo, $inputKey, $salt, true);

        $hmac = '';
        $outputKey = '';
        for ($i = 1; $i <= $blocks; $i++) {
            $hmac = hash_hmac($algo, $hmac . $info . chr($i), $prKey, true);
            $outputKey .= $hmac;
        }

        if ($length !== 0) {
            $outputKey = StringHelper::byteSubstr($outputKey, 0, $length);
        }
        return $outputKey;
    }

    public function pbkdf2($algo, $password, $salt, $iterations, $length = 0)
    {
        if (function_exists('hash_pbkdf2')) {
            $outputKey = hash_pbkdf2($algo, $password, $salt, $iterations, $length, true);
            if ($outputKey === false) {
                throw new InvalidParamException('Invalid parameters to hash_pbkdf2()');
            }
            return $outputKey;
        }

        $test = @hash_hmac($algo, '', '', true);
        if (!$test) {
            throw new InvalidParamException('Failed to generate HMAC with hash algorithm: ' . $algo);
        }
        if (is_string($iterations) && preg_match('{^\d{1,16}$}', $iterations)) {
            $iterations = (int) $iterations;
        }
        if (!is_int($iterations) || $iterations < 1) {
            throw new InvalidParamException('Invalid iterations');
        }
        if (is_string($length) && preg_match('{^\d{1,16}$}', $length)) {
            $length = (int) $length;
        }
        if (!is_int($length) || $length < 0) {
            throw new InvalidParamException('Invalid length');
        }
        $hashLength = StringHelper::byteLength($test);
        $blocks = $length !== 0 ? ceil($length / $hashLength) : 1;

        $outputKey = '';
        for ($j = 1; $j <= $blocks; $j++) {
            $hmac = hash_hmac($algo, $salt . pack('N', $j), $password, true);
            $xorsum = $hmac;
            for ($i = 1; $i < $iterations; $i++) {
                $hmac = hash_hmac($algo, $hmac, $password, true);
                $xorsum ^= $hmac;
            }
            $outputKey .= $xorsum;
        }

        if ($length !== 0) {
            $outputKey = StringHelper::byteSubstr($outputKey, 0, $length);
        }
        return $outputKey;
    }

    public function hashData($data, $key, $rawHash = false)
    {
        $hash = hash_hmac($this->macHash, $data, $key, $rawHash);
        if (!$hash) {
            throw new InvalidConfigException('Failed to generate HMAC with hash algorithm: ' . $this->macHash);
        }
        return $hash . $data;
    }

    public function validateData($data, $key, $rawHash = false)
    {
        $test = @hash_hmac($this->macHash, '', '', $rawHash);
        if (!$test) {
            throw new InvalidConfigException('Failed to generate HMAC with hash algorithm: ' . $this->macHash);
        }
        $hashLength = StringHelper::byteLength($test);
        if (StringHelper::byteLength($data) >= $hashLength) {
            $hash = StringHelper::byteSubstr($data, 0, $hashLength);
            $pureData = StringHelper::byteSubstr($data, $hashLength, null);

            $calculatedHash = hash_hmac($this->macHash, $pureData, $key, $rawHash);

            if ($this->compareString($hash, $calculatedHash)) {
                return $pureData;
            }
        }
        return false;
    }

    private $_useLibreSSL;
    private $_randomFile;

    /**
     * Генерирует набор случайных байтов.
     * @param int $length Длина.
     * @return string Сгенерированный набор байт.
     * @throws InvalidParamException Неверная длина.
     * @throws Exception При неудаче.
     * @since 1.0.0
     */
    public function generateRandomKey($length = 32)
    {
        if (!is_int($length)) {
            throw new InvalidParamException('First parameter ($length) must be an integer');
        }

        if ($length < 1) {
            throw new InvalidParamException('First parameter ($length) must be greater than 0');
        }

        // always use random_bytes() if it is available
        if (function_exists('random_bytes')) {
            return random_bytes($length);
        }

        // The recent LibreSSL RNGs are faster and likely better than /dev/urandom.
        // Parse OPENSSL_VERSION_TEXT because OPENSSL_VERSION_NUMBER is no use for LibreSSL.
        // https://bugs.php.net/bug.php?id=71143
        if ($this->_useLibreSSL === null) {
            $this->_useLibreSSL = defined('OPENSSL_VERSION_TEXT')
                && preg_match('{^LibreSSL (\d\d?)\.(\d\d?)\.(\d\d?)$}', OPENSSL_VERSION_TEXT, $matches)
                && (10000 * $matches[1]) + (100 * $matches[2]) + $matches[3] >= 20105;
        }

        // Since 5.4.0, openssl_random_pseudo_bytes() reads from CryptGenRandom on Windows instead
        // of using OpenSSL library. LibreSSL is OK everywhere but don't use OpenSSL on non-Windows.
        if ($this->_useLibreSSL
            || (
                DIRECTORY_SEPARATOR !== '/'
                && substr_compare(PHP_OS, 'win', 0, 3, true) === 0
                && function_exists('openssl_random_pseudo_bytes')
            )
        ) {
            $key = openssl_random_pseudo_bytes($length, $cryptoStrong);
            if ($cryptoStrong === false) {
                throw new Exception(
                    'openssl_random_pseudo_bytes() set $crypto_strong false. Your PHP setup is insecure.'
                );
            }
            if ($key !== false && StringHelper::byteLength($key) === $length) {
                return $key;
            }
        }

        // mcrypt_create_iv() does not use libmcrypt. Since PHP 5.3.7 it directly reads
        // CryptGenRandom on Windows. Elsewhere it directly reads /dev/urandom.
        if (function_exists('mcrypt_create_iv')) {
            $key = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
            if (StringHelper::byteLength($key) === $length) {
                return $key;
            }
        }

        // If not on Windows, try to open a random device.
        if ($this->_randomFile === null && DIRECTORY_SEPARATOR === '/') {
            // urandom is a symlink to random on FreeBSD.
            $device = PHP_OS === 'FreeBSD' ? '/dev/random' : '/dev/urandom';
            // Check random device for special character device protection mode. Use lstat()
            // instead of stat() in case an attacker arranges a symlink to a fake device.
            $lstat = @lstat($device);
            if ($lstat !== false && ($lstat['mode'] & 0170000) === 020000) {
                $this->_randomFile = fopen($device, 'rb') ?: null;

                if (is_resource($this->_randomFile)) {
                    // Reduce PHP stream buffer from default 8192 bytes to optimize data
                    // transfer from the random device for smaller values of $length.
                    // This also helps to keep future randoms out of user memory space.
                    $bufferSize = 8;

                    if (function_exists('stream_set_read_buffer')) {
                        stream_set_read_buffer($this->_randomFile, $bufferSize);
                    }
                    // stream_set_read_buffer() isn't implemented on HHVM
                    if (function_exists('stream_set_chunk_size')) {
                        stream_set_chunk_size($this->_randomFile, $bufferSize);
                    }
                }
            }
        }

        if (is_resource($this->_randomFile)) {
            $buffer = '';
            $stillNeed = $length;
            while ($stillNeed > 0) {
                $someBytes = fread($this->_randomFile, $stillNeed);
                if ($someBytes === false) {
                    break;
                }
                $buffer .= $someBytes;
                $stillNeed -= StringHelper::byteLength($someBytes);
                if ($stillNeed === 0) {
                    // Leaving file pointer open in order to make next generation faster by reusing it.
                    return $buffer;
                }
            }
            fclose($this->_randomFile);
            $this->_randomFile = null;
        }

        throw new Exception('Unable to generate a random key');
    }

    /**
     * Генерирует случайную строку определенной длины.
     * @param int $length Длина.
     * @return string Сгенерированная строка.
     * @throws Exception При неудаче.
     * @since 1.0.0
     */
    public function generateRandomString($length = 32)
    {
        if (!is_int($length)) {
            throw new InvalidParamException('First parameter ($length) must be an integer');
        }

        if ($length < 1) {
            throw new InvalidParamException('First parameter ($length) must be greater than 0');
        }

        $bytes = $this->generateRandomKey($length);
        return strtr(substr(base64_encode($bytes), 0, $length), '+/', '_-');
    }

    /**
     * Генерирует хэш пароля и соль.
     * @param string $password Пароль для хеширования.
     * @param int $cost Стоимость генерации.
     * @return string Хеш пароля.
     * @throws Exception Возникает при неверно переданном пароле или стоимости.
     * @see validatePassword()
     * @since 1.0.0
     */
    public function generatePasswordHash($password, $cost = null)
    {
        if ($cost === null) {
            $cost = $this->passwordHashCost;
        }

        if (function_exists('password_hash')) {
            /** @noinspection PhpUndefinedConstantInspection */
            return password_hash($password, PASSWORD_DEFAULT, ['cost' => $cost]);
        }

        $salt = $this->generateSalt($cost);
        $hash = crypt($password, $salt);

        if (!is_string($hash) || strlen($hash) !== 60) {
            throw new Exception('Unknown error occurred while generating hash.');
        }

        return $hash;
    }

    /**
     * Проверяет пароль по хэшу.
     * @param string $password Пароль для сравнения.
     * @param string $hash Хэш пароля для проверки.
     * @return bool Пароль корректен.
     * @throws InvalidParamException При неверных параметрах пароля или хеша.
     * @see generatePasswordHash()
     * @since 1.0.0
     */
    public function validatePassword($password, $hash)
    {
        if (!is_string($password) || $password === '') {
            throw new InvalidParamException('Password must be a string and cannot be empty.');
        }

        if (!preg_match('/^\$2[axy]\$(\d\d)\$[\.\/0-9A-Za-z]{22}/', $hash, $matches)
            || $matches[1] < 4
            || $matches[1] > 30
        ) {
            throw new InvalidParamException('Hash is invalid.');
        }

        if (function_exists('password_verify')) {
            return password_verify($password, $hash);
        }

        $test = crypt($password, $hash);
        $n = strlen($test);
        if ($n !== 60) {
            return false;
        }

        return $this->compareString($test, $hash);
    }

    /**
     * Генерирует соль для использования в генерации хэша пароля.
     * @param int $cost Стоимость.
     * @return string Случайная соль.
     * @throws InvalidParamException Если стоимость не в диапазоне от 4 до 31.
     * @since 1.0.0
     */
    protected function generateSalt($cost = 13)
    {
        $cost = (int) $cost;
        if ($cost < 4 || $cost > 31) {
            throw new InvalidParamException('Cost must be between 4 and 31.');
        }

        $rand = $this->generateRandomKey(20);
        $salt = sprintf("$2y$%02d$", $cost);
        $salt .= str_replace('+', '.', substr(base64_encode($rand), 0, 22));

        return $salt;
    }

    /**
     * Сравнивает две строки.
     * @param string $expected Строка для сравнения.
     * @param string $actual Поддерживаемая пользователем строка.
     * @return bool Строки эквивалентны.
     * @since 1.0.0
     */
    public function compareString($expected, $actual)
    {
        $expected .= "\0";
        $actual .= "\0";
        $expectedLength = StringHelper::byteLength($expected);
        $actualLength = StringHelper::byteLength($actual);
        $diff = $expectedLength - $actualLength;
        for ($i = 0; $i < $actualLength; $i++) {
            $diff |= (ord($actual[$i]) ^ ord($expected[$i % $expectedLength]));
        }
        return $diff === 0;
    }
}
