<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

if ($data['state'] == 'loading') {
    $this->getProperties()->set('template-menu-show', false);
}