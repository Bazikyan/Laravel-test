<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/21/17
 * Time: 1:21 PM
 */

namespace App\Services;


abstract class AbstractService
{
    abstract protected static function filterByMode($user_id, $queryBuilder);
}