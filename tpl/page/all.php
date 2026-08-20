<?php
namespace GDO\Licenses\tpl\page;

use GDO\Core\GDO_Module;
use GDO\Core\ModuleLoader;
use GDO\Licenses\Module_Licenses;
use GDO\UI\GDT_Accordeon;
use GDO\Markdown\Module_Markdown;
use GDO\UI\GDT_HTML;

$ml = Module_Licenses::instance();

$modules = ModuleLoader::instance()->getEnabledModules();
uasort($modules, function (GDO_Module $a, GDO_Module $b)
{
	return strcasecmp($a->renderName(), $b->renderName());
});

foreach ($modules as $module)
{
	$name = $module->renderName();
	$title = "{$name} ({$ml->getModuleMainLicenseName($module)})";
	$markdown = GDT_HTML::make()->var(Module_Markdown::DECODE($ml->getModuleLicense($module)));
	$accordeon = GDT_Accordeon::make();
	$accordeon->titleRaw($title);
	$accordeon->addField($markdown);
	echo $accordeon->render();
}
