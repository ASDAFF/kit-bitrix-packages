<?php

namespace Kit\Origami\Front;

class User
{
    private $id        = '';
    private $site      = 's1';
    private $canSave   = false;
    private $canChange = false;
    const TMP_DIR = '/bitrix/tmp/kit_origami';

    public function __construct($site = SITE_ID)
    {
        $this->setSite($site);
        $this->setCanChange();
        $this->setCanSave();
        if ($this->isCanChange()) {
            $this->setId();
        }
        else{
            unset($_SESSION['KIT_ORIGAMI']);
        }
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     */
    public function setId()
    {
        if (!isset($_SESSION['KIT_ORIGAMI']))
        {
            global $USER;
            $_SESSION['KIT_ORIGAMI'] = rand();
            $userId = $USER->GetID();
            if ($userId > 0)
            {
                $_SESSION['KIT_ORIGAMI'] = $_SESSION['KIT_ORIGAMI'].'_'.$userId;
            }
        }
        $this->id = $_SESSION['KIT_ORIGAMI'];
        $this->makeDirs();
    }

    /**
     * @return string
     */
    public function getFolder()
    {
        return self::TMP_DIR.'/'.$this->getId();
    }

    /**
     *
     */
    private function makeDirs()
    {
        $id = $this->getId();
        if (!is_dir($_SERVER['DOCUMENT_ROOT'].self::TMP_DIR)) {
            mkdir($_SERVER['DOCUMENT_ROOT'].self::TMP_DIR);
        }
        if (!is_dir($_SERVER['DOCUMENT_ROOT'].self::TMP_DIR.'/'.$id)) {
            mkdir($_SERVER['DOCUMENT_ROOT'].self::TMP_DIR.'/'.$id);
        }
        if (!is_dir($_SERVER['DOCUMENT_ROOT'].self::TMP_DIR.'/'.$id.'/blocks')
        ) {
            mkdir($_SERVER['DOCUMENT_ROOT'].self::TMP_DIR.'/'.$id.'/blocks');
        }
        if (!is_dir($_SERVER['DOCUMENT_ROOT'].self::TMP_DIR.'/'.$id.'/theme')
        ) {
            mkdir($_SERVER['DOCUMENT_ROOT'].self::TMP_DIR.'/'.$id.'/theme');
        }
    }

    /**
     * @return bool
     */
    public function isCanSave()
    {
        return $this->canSave;
    }

    /**
     *
     */
    public function setCanSave()
    {
        global $USER;
        if ($USER->isAdmin()){
            $this->canSave = true;
        } elseif (\Kit\Origami\Config\Option::get('DEMO', $this->getSite()))
        {
            $this->canSave = false;
        } else {
            $this->canSave = false;
        }
    }

    /**
     * @return bool
     */
    public function isCanChange()
    {
        return $this->canChange;
    }

    /**
     *
     */
    public function setCanChange()
    {
        global $USER;

        $detect = new \Bitrix\Conversion\Internals\MobileDetect;
        if($detect->isMobile())
        {
            $this->canChange = false;
            return;
        }

        if ($USER->isAdmin())
        {
            $this->canChange = true;
        } elseif (\Kit\Origami\Config\Option::get('DEMO', $this->getSite()))
        {
            $this->canChange = true;
        } else {
            $this->canChange = false;
        }

    }

    /**
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param string $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

}