<?php
namespace GDO\Licenses\tpl\page;

use GDO\Core\ModuleLoader;
use GDO\UI\GDT_Accordeon;
use GDO\UI\GDT_Pre;
use GDO\Core\GDO_Module;

$modules = ModuleLoader::instance()->getEnabledModules();
uasort($modules, function(GDO_Module $a, GDO_Module $b) {
    return strcasecmp($a->renderName(), $b->renderName());
});

foreach ($modules as $module)
{
    $name = $module->renderName();
    $title = "{$name} ({$module->license})";
    $pre = GDT_Pre::make()->textRaw($module->displayModuleLicense());
    $accordeon = GDT_Accordeon::make();
    $accordeon->title($title);
    $accordeon->addField($pre);
    echo $accordeon->render();
}
