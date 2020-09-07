<?php
namespace intec;

require(__DIR__ . '/CoreBase.php');

use intec\core\CoreBase;
use intec\core\di\Container;
use intec\core\helpers\Encoding;

class Core extends CoreBase
{
}

spl_autoload_register(['intec\Core', 'autoload'], true, true);
Core::$classes = require(__DIR__.'/classes.php');
Core::$container = new Container();

global $DBType;
global $DBHost;
global $DBName;
global $DBLogin;
global $DBPassword;

$DBHostParts = explode(':', $DBHost);
$DBHostAddress = $DBHostParts[0];
$DBHostPort = isset($DBHostParts[1]) ? $DBHostParts[1] : null;

(new core\base\Application([
    'id' => 'intec.core',
    'basePath' => $_SERVER['DOCUMENT_ROOT'],
    'charset' => Encoding::resolve(SITE_CHARSET),
    'components' => [
        'db' => [
            'dsn' => $DBType.':host='.$DBHostAddress.';dbname='.$DBName.(!empty($DBHostPort) ? ';port='.$DBHostPort : null),
            'username' => $DBLogin,
            'password' => $DBPassword,
            'charset' => Encoding::resolve(SITE_CHARSET, Encoding::TYPE_DATABASE)
        ]
    ]
]));

Core::setAlias('@root/linked', dirname(dirname(dirname(dirname(__DIR__)))));
Core::setAlias('@bitrix', '@root'.BX_ROOT);
Core::setAlias('@upload', '@root/upload');
Core::setAlias('@modules', '@bitrix/modules');
Core::setAlias('@resources', '@bitrix/resources');
Core::setAlias('@templates', '@bitrix/templates');
Core::setAlias('@themes', '@bitrix/themes');
Core::setAlias('@intec/core/module', dirname(__DIR__));
Core::setAlias('@intec/core/libraries', '@intec/core/module/libraries');
Core::setAlias('@intec/core/resources', '@resources/'.Core::$app->id);

require(__DIR__.'/web.php');