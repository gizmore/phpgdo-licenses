<?php
namespace GDO\Licenses\tpl\page;

use GDO\Core\ModuleLoader;
use GDO\UI\GDT_Accordeon;
use GDO\UI\GDT_Pre;
use GDO\UI\GDT_Headline;

$modules = ModuleLoader::instance()->getEnabledModules();

$accordeon = GDT_Accordeon::make();
$accordeon->titleRaw('Licenses');

uasort($modules, function($a, $b) {
    return strcmp($a->displayName(), $b->displayName());
});

foreach ($modules as $module)
{
    $name = $module->displayName();
    $title = "{$name} ({$module->module_license})";
    $pre = GDT_Pre::make()->textRaw($module->displayModuleLicense());
    $accordeon->addSection($title, $pre);
}

echo GDT_Headline::make()->level(2)->text('licenses')->render();

echo $accordeon->render();
