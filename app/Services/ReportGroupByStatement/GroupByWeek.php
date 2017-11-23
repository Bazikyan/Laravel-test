<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/17/17
 * Time: 7:38 AM
 */

namespace App\Services\ReportGroupByStatement;


use App\Services\ReportBuilderService;

class GroupByWeek extends ReportBuilderService
{
    public function getGroupByStatement()
    {
        $this->report->groupBy(\DB::raw("DATE_FORMAT(reports.created_at, '%u %Y')"));
        return $this;
    }
}