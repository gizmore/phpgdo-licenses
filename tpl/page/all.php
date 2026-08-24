<?php
namespace GDO\Licenses\tpl\page;

use GDO\Core\GDO_Module;
use GDO\Core\Module_Core;
use GDO\Core\ModuleLoader;
use GDO\Licenses\Module_Licenses;
use GDO\UI\GDT_Accordeon;
use GDO\Markdown\GDT_Markdown;

$ml = Module_Licenses::instance();

$modules = ModuleLoader::instance()->getEnabledModules();
uasort($modules, function (GDO_Module $a, GDO_Module $b)
{
	return strcasecmp($a->renderName(), $b->renderName());
});

$renderLicense = static function (GDO_Module $module) use ($ml): void
{
	$name = $module->renderName();
	$title = "{$name} ({$ml->getModuleMainLicenseName($module)})";
	$markdown = GDT_Markdown::make()->var($ml->getModuleLicense($module));
	$accordeon = GDT_Accordeon::make();
	$accordeon->titleRaw($title);
	$accordeon->addField($markdown);
	echo $accordeon->render();
};

# The standard license is stated once, from the authoritative Core copy.
$renderLicense(Module_Core::instance());

$exceptions = array_filter($modules, static function (GDO_Module $module): bool
{
	return !$module->isGDOLicense();
});
if ($exceptions)
{
	echo GDT_Markdown::make()->var(t('md_licenses_exceptions'))->render();
	foreach ($exceptions as $module)
	{
		$renderLicense($module);
	}
}
