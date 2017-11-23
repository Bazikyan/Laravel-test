<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/17/17
 * Time: 6:16 AM
 */

namespace App\Services\ReportGroupByStatement;

use App\Services\ReportBuilderService;


class GroupByAll extends ReportBuilderService
{
//    public function __construct(Request $request, $user_id)
//    {
//        parent::__construct($request, $user_id);
//    }

    public function getGroupByStatement()
    {
        return $this;
    }
}