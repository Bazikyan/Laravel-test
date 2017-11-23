<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/17/17
 * Time: 7:39 AM
 */

namespace App\Services\ReportGroupByStatement;


use App\Services\ReportBuilderService;

class GroupByDay extends ReportBuilderService
{
    public function getGroupByStatement()
    {
        $this->report->groupBy(\DB::raw("DATE_FORMAT(reports.created_at, '%d %c %Y')"));
        return $this;
    }
}