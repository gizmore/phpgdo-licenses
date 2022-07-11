<?php
namespace GDO\Licenses;

use GDO\Core\GDO_Module;
use GDO\Core\GDT_Checkbox;
use GDO\UI\GDT_Page;
use GDO\UI\GDT_Link;

/**
 * Print license information for the installed modules
 *
 * @author gizmore
 * @version 6.10.6
 * @since 6.10.6
 */
final class Module_Licenses extends GDO_Module
{
    public function onLoadLanguage() : void
    {
        $this->loadLanguage('lang/licenses');
    }
    
    public function getConfig() : array
    {
        return [
            GDT_Checkbox::make('footer'),
        ];
    }
    public function cfgFooter() { return $this->getConfigVar('footer'); }
    
    public function onInitSidebar() : void
    {
        if ($this->cfgFooter())
        {
            GDT_Page::$INSTANCE->bottomNav->addField(
                GDT_Link::make('licenses')->href(
                    href('Licenses', 'All'))->icon('license')
            );
        }
    }
}
