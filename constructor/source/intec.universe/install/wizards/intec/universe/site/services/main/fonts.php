<? include(__DIR__.'/../.begin.php') ?>
<?

use intec\core\base\Collection;
use intec\core\helpers\FileHelper;
use intec\core\io\Path;
use intec\constructor\models\Font;
use intec\constructor\models\font\File;
use intec\constructor\models\font\Link;

/**
 * @var Collection $data
 * @var CWizardBase $wizard
 * @var CWizardStep $this
 */

$directory = Path::from(__DIR__.'/fonts');
$uploadDirectory = Path::from('@intec/constructor/upload')
    ->getRelativeFrom('@upload')
    ->add('fonts')
    ->getValue('/');

$fontsExternal = [
    'Roboto' => 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;subset=cyrillic',
    'Montserrat' => 'https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700&amp;subset=cyrillic'
];

/** CUSTOM START */
/** CUSTOM END */

$entries = FileHelper::getDirectoryEntries($directory->value, false);
$fonts = Font::find()->all()->indexBy('code');
$formats = [
    Font::FORMAT_OTF => 'otf',
    Font::FORMAT_SVG => 'svg',
    Font::FORMAT_TTF => 'ttf',
    Font::FORMAT_EOT => 'eot',
    Font::FORMAT_WOFF => 'woff',
    Font::FORMAT_WOFF2 => 'woff2'
];

foreach ($entries as $font) {
    if ($fonts->exists($font))
        continue;

    $font = new Font([
        'code' => $font
    ]);

    $font->active = true;
    $font->name = $font->code;
    $font->sort = 500;
    $font->type = Font::TYPE_LOCAL;

    if ($font->save()) {
        foreach (Font::getWeightsValues() as $weight) {
            foreach (Font::getStylesValues() as $style) {
                foreach (Font::getFormatsValues() as $format) {
                    if (empty($formats[$format]))
                        continue;

                    $fileFormat = $formats[$format];
                    $path = $directory->add($font->code)->add($weight.'-'.$style.'.'.$fileFormat);

                    if (!FileHelper::isFile($path->value))
                        continue;

                    $file = CFile::MakeFileArray($path->value);

                    if (!empty($file)) {
                        $file['name'] = $font->code.'-'.$weight.'-'.$style.'.'.$fileFormat;
                        $file = CFile::SaveFile($file, $uploadDirectory);

                        if (!empty($file)) {
                            $file = new File([
                                'fileId' => $file
                            ]);

                            $file->fontCode = $font->code;
                            $file->weight = $weight;
                            $file->style = $style;
                            $file->format = $format;
                            $file->save();
                        }
                    }
                }
            }
        }
    }
}

foreach ($fontsExternal as $font => $link) {
    if ($fonts->exists($font))
        continue;

    $font = new Font([
        'code' => $font
    ]);

    $font->active = true;
    $font->name = $font->code;
    $font->sort = 500;
    $font->type = Font::TYPE_EXTERNAL;

    if ($font->save()) {
        $link = new Link([
            'value' => $link
        ]);

        $link->fontCode = $font->code;
        $link->save();
    }
}

?>
<? include(__DIR__.'/../.end.php') ?>