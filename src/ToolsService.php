<?php
// +----------------------------------------------------------------------
// | think-utils-ToolsService
// +----------------------------------------------------------------------
// | Copyright (c) 2021 http://www.bajiukeji.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Date: 2021/04/17 14:06
// +----------------------------------------------------------------------
// | Author: bajiu <bajiu@bajiukeji.com>
// +--------------------------------------------------------------------

namespace zt\think;

use think\Service;
use zt\think\command\make\SyncFacade;
use zt\think\command\make\MakeFacade;

class ToolsService extends Service
{
    public function boot()
    {
        $this->commands(SyncFacade::class,MakeFacade::class);
    }
}
