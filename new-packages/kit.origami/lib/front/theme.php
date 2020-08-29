<?php

namespace Sotbit\Origami\Front;

class Theme
{
    const DEFAULT_THEME = '/local/templates/kit_origami/assets/css';
    const CUSTOM_THEME  = '/local/templates/kit_origami/theme/custom';

    /**
     * @var User
     */
    private $frontUser;

    public function __construct($site = SITE_ID)
    {
        $this->frontUser = new User($site);
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        $settings = [];

        $dir = $this->getFrontUser()->getFolder();
        if ($dir)
        {
            if (file_exists($_SERVER['DOCUMENT_ROOT'].$dir.'/theme/settings.php'))
            {
                $settings = unserialize(file_get_contents($_SERVER['DOCUMENT_ROOT'].$dir.'/theme/settings.php'));
                if (!is_array($settings))
                {
                    $settings = [];
                }
            }
        }

        return $settings;
    }

    /**
     * @return User
     */
    public function getFrontUser()
    {
        return $this->frontUser;
    }

    /**
     * @param array $settings
     */
    public function writeSettings($settings = [])
    {
        $dir = $this->getFrontUser()->getFolder();
        file_put_contents(
            $_SERVER['DOCUMENT_ROOT'].$dir.'/theme/settings.php',
            serialize($settings)
        );
    }

    public function getTheme()
    {
        $theme = self::DEFAULT_THEME;
        $dir = $this->getFrontUser()->getFolder();
        if (file_exists($_SERVER['DOCUMENT_ROOT'].$dir.'/theme/style.css')) {
            $theme = $dir.'/theme';
        } elseif (file_exists($_SERVER['DOCUMENT_ROOT'].self::CUSTOM_THEME
            .'/style.css')
        ) {
            $theme = self::CUSTOM_THEME;
        }
        
        return $theme;
    }
}