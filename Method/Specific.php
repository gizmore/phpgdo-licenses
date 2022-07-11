<?php
namespace GDO\Licenses\Method;

use GDO\Core\Method;
use GDO\Core\GDT_Module;
use GDO\Core\GDT_Response;
use GDO\UI\GDT_Pre;

final class Specific extends Method
{
    public function gdoParameters() : array
    {
        return [
            GDT_Module::make('module')->installed()->notNull(),
        ];
    }
    
    public function execute()
    {
        $mod = $this->gdoParameterValue('module');
        return GDT_Response::makeWith(
            GDT_Pre::make()->textRaw(
                $mod->displayModuleLicense()));
    }
    
}
