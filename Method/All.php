<?php
namespace GDO\Licenses\Method;

use GDO\UI\MethodPage;
use GDO\Core\WithFileCache;

/**
 * Display all licenses.
 * 
 * @author gizmore
 * @version 7.0.1
 * @since 6.11.0
 */
final class All extends MethodPage
{
	use WithFileCache;
	
    public function getMethodTitle() : string
    {
    	return t('licenses');
    }
    
}
