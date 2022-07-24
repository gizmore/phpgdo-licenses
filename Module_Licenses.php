<?php
namespace GDO\Licenses;

use GDO\Core\GDO_Module;
use GDO\Core\GDT_Checkbox;
use GDO\UI\GDT_Page;
use GDO\UI\GDT_Link;
use GDO\Util\FileUtil;
use GDO\Util\Strings;

/**
 * Print license and author information for all installed modules.
 *
 * @author gizmore
 * @version 7.0.1
 * @since 6.10.6
 */
final class Module_Licenses extends GDO_Module
{
    public function getConfig() : array
    {
        return [
            GDT_Checkbox::make('hook_sidebar')->initial('1'),
        ];
    }
    public function cfgFooter() { return $this->getConfigVar('hook_sidebar'); }
    
    ############
    ### Init ###
    ############
    public function onLoadLanguage() : void
    {
    	$this->loadLanguage('lang/licenses');
    }
    
    public function onInitSidebar() : void
    {
        if ($this->cfgFooter())
        {
            GDT_Page::instance()->bottomBar()->addField(
                GDT_Link::make('licenses')->href(
                    href('Licenses', 'All'))->icon('license')
            );
        }
    }
    
    ###############
    ### License ###
    ###############
    /**
     * Print license information.
     * @return string
     */
    public function getModuleLicense(GDO_Module $module) : string
    {
    	$all = '';
    	
    	$files = $module->getLicenseFilenames();
    	
    	$div = '<hr/>';
    	
    	if ($descr = $module->getModuleDescription())
    	{
    		$all .= "$descr\n$div";
    		if ($files)
    		{
    			$gdo = 0; # gdo licenses
    			foreach ($files as $file)
    			{
    				# @TODO module is bullocks. how to identify a gdo license?
    				if ($module->filePath('LICENSE') === $module->filePath($file))
    				{
    					$gdo = 1;
    				}
    			}
    			
    			$count = count($files) - $gdo;
    			if ($count)
    			{
    				$all .= "$count third-party-licenses involved:";
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
    			renderCell();
    			
    			$filename = $module->filePath($filename);
    			if (FileUtil::isFile($filename))
    			{
    				$all .= file_get_contents($filename);
    			}
    		}
    	}
    	else
    	{
    		$all .= 'UNLICENSED / PROPERITARY';
    	}
    	return $all;
    }
    
}
