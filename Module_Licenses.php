<?php
declare(strict_types=1);
namespace GDO\Licenses;

use GDO\Core\GDO;
use GDO\Core\GDO_Module;
use GDO\Core\GDT_Checkbox;
use GDO\Install\Installer;
use GDO\Markdown\Module_Markdown;
use GDO\UI\GDT_Link;
use GDO\UI\GDT_Page;
use GDO\Util\FileUtil;
use GDO\Util\Strings;

/**
 * Print license and author information for all installed modules.
 *
 * @version 7.0.3
 * @since 6.10.6
 * @author gizmore
 */
final class Module_Licenses extends GDO_Module
{

	public function getFriendencies(): array
	{
		return ['Markdown'];
	}

	##############
	### Config ###
	##############
	public function getConfig(): array
	{
		return [
			GDT_Checkbox::make('hook_sidebar')->initial('1'),
		];
	}

	public function onLoadLanguage(): void
	{
		$this->loadLanguage('lang/licenses');
	}

	############
	### Init ###
	############

	public function onInitSidebar(): void
	{
		if ($this->cfgFooter())
		{
			GDT_Page::instance()->bottomBar()->addField(
				GDT_Link::make('licenses')->href(
					href('Licenses', 'All'))->icon('license')
			);
		}
	}

	public function cfgFooter(): bool { return $this->getConfigValue('hook_sidebar'); }

	###############
	### License ###
	###############

	public function getModuleMainLicenseName(GDO_Module $module): string
	{
		return $module->license;
	}


	/**
	 * Print license information.
	 */
	public function getModuleLicense(GDO_Module $module): string
	{
		$all = '';

		$files = $module->getLicenseFilenames();

		$div = '<hr/>';

		if ($descr = Installer::getModuleDescription($module))
		{
			if (module_enabled('Markdown'))
			{
				$descr = Module_Markdown::DECODE($descr);
			}
			$all .= "$descr\n$div";
			if ($files)
			{
				$gdo = 0; # gdo licenses
				if ($module->license === GDO::LICENSE)
				{
					$gdo = 1;
				}
				$count = count($files) - $gdo;
				if ($count)
				{
					$all .= t('3p_involved', [$count]);
					$all .= "\n$div";
				}
			}
		}

		if ($files)
		{
			foreach ($files as $i => $filename)
			{
				if ($i > 0)
				{
					$all .= "\n$div";
				}

				$all .= GDT_Link::make()->
				labelRaw(Strings::substrFrom($filename, GDO_WEB_ROOT, $filename))->
				href($module->wwwPath($filename))->
				renderHTML();

				$filename = $module->filePath($filename);
				if (FileUtil::isFile($filename))
				{
					$all .= file_get_contents($filename);
				}
			}
		}
		else
		{
			$all .= 'GDOv7-LICENSE';
		}
		return $all;
	}

}
