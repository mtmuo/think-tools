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

namespace mtmuo\think;

use mtmuo\think\paginator\AntPaginator;
use think\Service;
use mtmuo\think\command\make\SyncFacade;
use mtmuo\think\command\make\MakeFacade;
use mtmuo\think\command\Table;


class ToolsService extends Service
{
    public $bind = [
        'think\Paginator' => AntPaginator::class,
    ];

    public function boot()
    {
        $this->commands(SyncFacade::class, MakeFacade::class, Table::class);
    }
}
