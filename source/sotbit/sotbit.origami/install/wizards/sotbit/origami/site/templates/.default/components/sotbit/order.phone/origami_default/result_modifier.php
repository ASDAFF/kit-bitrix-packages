<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $USER;
$arResult['USER'] = ['NAME' => '','PHONE' => '','EMAIL' => ''];
if($USER->IsAuthorized())
{
	$user = \CUser::GetByID($USER->GetID())->Fetch();
	$arResult['USER']['NAME'] = $user['NAME'];
	$arResult['USER']['PHONE'] = $user['PERSONAL_PHONE'];
	$arResult['USER']['EMAIL'] = $user['EMAIL'];
}