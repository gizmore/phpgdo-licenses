<?php
namespace GDO\Licenses\Method;

use GDO\Core\GDO;
use GDO\Core\GDT;
use GDO\Core\GDT_String;
use GDO\Core\GDT_Text;
use GDO\Core\ModuleLoader;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;

/**
 * Change all GDOv7 module licenses on the fly, unless they have a free license.
 *
 * @since 7.0.1
 * @author gizmore
 */
final class Change extends MethodForm
{

	public function isTrivial(): bool
	{
		return false;
	}

	public function createForm(GDT_Form $form): void
	{
		$form->addFields(
			GDT_Text::make('text')->notNull(),
			GDT_String::make('filename')->initial('LICENSE')->notNull(),
			GDT_AntiCSRF::make(),
		);
		$form->actions()->addFields(GDT_Submit::make());
	}

	public function formValidated(GDT_Form $form): GDT
	{
		$text = $form->getFormVar('text');
		$filename = $form->getFormVar('filename');
		$this->changeLicenses($text, $filename);
	}

	public function changeLicenses(string $text, string $filename)
	{
		$modules = ModuleLoader::instance()->getEnabledModules();
		$numWritten = 1;
		foreach ($modules as $module)
		{
			if ($module->license === GDO::LICENSE)
			{
				$path = $module->filePath($filename);
				file_put_contents($path, $text);
				$numWritten++;
			}
		}

		# Core
		$path = GDO_PATH . $filename;
		file_put_contents($path, $text);

		$this->message('msg_licenses_changed', [
			$numWritten, html($filename)]);
	}

}
