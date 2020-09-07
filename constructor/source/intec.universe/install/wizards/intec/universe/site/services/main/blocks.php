<? include(__DIR__.'/../.begin.php') ?>
<?

use intec\core\base\Collection;
use intec\core\helpers\FileHelper;
use intec\core\io\Path;
use intec\constructor\models\block\Category;
use intec\constructor\models\block\Template;

/**
 * @var Collection $data
 * @var CWizardBase $wizard
 * @var CWizardStep $this
 */

$directory = Path::from(__DIR__.'/blocks');
$categories = Category::find()
    ->indexBy('code')
    ->all();

$entries = FileHelper::getDirectoryEntries($directory->value, false);

foreach ($entries as $category) {
    $categoryDirectory = $directory->add($category);
    $categoryFile = $categoryDirectory->add('category.json');

    if (!FileHelper::isDirectory($categoryDirectory->value))
        continue;

    if (!FileHelper::isFile($categoryFile->value))
        continue;

    $category = new Category([
        'code' => $category
    ]);

    if ($categories->exists($category->code) || $category->importFromFile($categoryFile->value)) {
        $entries = FileHelper::getDirectoryEntries($categoryDirectory->value, false);

        foreach ($entries as $template) {
            $templateDirectory = $categoryDirectory->add($template);

            if (!FileHelper::isDirectory($templateDirectory->value))
                continue;

            $template = new Template([
                'code' => $template
            ]);

            $template->categoryCode = $category->code;
            $template->importFromDirectory($templateDirectory->value);
        }
    }
}

?>
<? include(__DIR__.'/../.end.php') ?>