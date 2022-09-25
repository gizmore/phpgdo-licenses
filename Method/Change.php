<?php
namespace GDO\Licenses\Method;

use GDO\Form\GDT_Form;
use GDO\Form\MethodForm;
use GDO\Core\GDO;
use GDO\Core\GDT_Text;
use GDO\Core\GDT_String;
use GDO\Core\ModuleLoader;

/**
 * Change all licenses on the fly.
 * 
 * @author gizmore
 * @since 7.0.1
 */
final class Change extends MethodForm
{
	public function createForm(GDT_Form $form) : void
	{
		$form->addFields(
			GDT_Text::make('text')->notNull(),
			GDT_String::make('filename')->initial('LICENSE')->notNull(),
		);
		$form->addFormButtons();
	}
	
	public function formValidated(GDT_Form $form)
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
