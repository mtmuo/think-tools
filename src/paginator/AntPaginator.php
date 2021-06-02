<?php
// +----------------------------------------------------------------------
// | think-utils
// +----------------------------------------------------------------------
// | Copyright (c) think-utils
// +----------------------------------------------------------------------
// | Date: 2021/04/23 14:58
// +----------------------------------------------------------------------
// | Author: mtmuo
// +--------------------------------------------------------------------

namespace mtmuo\think\paginator;

use think\Paginator;

class AntPaginator extends Paginator
{
    public function render()
    {
    }

    public function toArray(): array
    {
        return [
            "total" => $this->total,
            "current" => $this->currentPage,
            "pageSize" => $this->listRows,
            "hasMore" => $this->hasMore,
            "data" => $this->items,
        ];
    }
}
