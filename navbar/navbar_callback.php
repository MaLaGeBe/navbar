<?php
!defined('EMLOG_ROOT') && exit('access deined!');

function callback_init()
{
    $nav_bar = Storage::getInstance('nav_bar');
    $nav_bar->setValue('menus', array(0 => 'default'), 'array');
    $nav_bar->setValue('menu_data', array(0 => 'default'), 'array');
    $nav_bar->setValue('menu_locations', [], 'array');
}

function callback_rm()
{
    $nav_bar = Storage::getInstance('nav_bar');
    $nav_bar->deleteAllName('YES');
}
