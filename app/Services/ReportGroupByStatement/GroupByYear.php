<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/17/17
 * Time: 7:35 AM
 */

namespace App\Services\ReportGroupByStatement;


use App\Services\ReportBuilderService;


class GroupByYear extends ReportBuilderService
{
    public function getGroupByStatement()
    {
        $this->report->groupBy(\DB::raw('YEAR(reports.created_at)'));
        return $this;
    }
}