<?php
namespace GDO\Licenses\Method;

use GDO\Core\WithFileCache;
use GDO\UI\MethodPage;

/**
 * Display all licenses for all modules and what the module does.
 *
 * @version 7.0.1
 * @since 6.11.0
 * @author gizmore
 */
final class All extends MethodPage
{

	use WithFileCache;

	public function getMethodTitle(): string
	{
		return t('licenses');
	}

}
