<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/17/17
 * Time: 12:42 PM
 */

namespace App\Services;

use App\User;
use Illuminate\Database\Query\Builder;

/**
 * Class UserService
 * @package App\Services
 * @property Builder $userBuilder
 */

class UserService extends AbstractService
{
    private $userBuilder;

    /**
     * UserService constructor.
     */
    public function __construct()
    {
        $this->userBuilder = User::query();
    }

    /**
     * @param integer $user_id
     * @param array $filters
     * @return Builder $userBuilder
     */
    public static function getUsers ($user_id, $filters = [])
    {
        $userBuilder = User::query();
        $userBuilder->select(['users.*'])
            ->join('teams', 'users.id', '=', 'teams.employer_id');
        $userBuilder = self::filterByMode($user_id, $userBuilder);
        if ($filters) {
            if ($filters['firstname']) {
                $userBuilder->where('firstname', 'LIKE', '%' . $filters['firstname'] . '%');
            }
            if ($filters['lastname']) {
                $userBuilder->where('lastname', 'LIKE', '%' . $filters['lastname'] . '%');
            }
            if ($filters['email']) {
                $userBuilder->where('email', 'LIKE', '%' . $filters['email'] . '%');
            }
        }
        $userBuilder->groupBy('users.id');

        return $userBuilder;
    }

    /**
     * @param Builder $userBuilder
     * @param integer $user_id
     * @return Builder $userBuilder
     */
    protected static function getTeam ($user_id, $userBuilder)
    {

        $userBuilder->where('teams.owner_id', '=', $user_id);
        return $userBuilder;
    }

    /**
     * @param Builder $userBuilder
     * @param integer $user_id
     * @return Builder $userBuilder
     */
    protected static function getCoworkers($user_id, $userBuilder)
    {
        $userBuilder->whereIn('teams.owner_id', function (Builder $query) use ($user_id) {
                $query->select('owner_id')
                    ->from('teams')
                    ->where('employer_id', '=', $user_id)
                    ->where('owner_id', '<>', 'employer_id');
            });

        return $userBuilder;
    }

    /**
     *
     * @param Builder $userBuilder
     * @param integer $user_id
     * @return Builder $userBuilder
     */
    protected static function filterByMode($user_id, $userBuilder)
    {
        $mode = 'owner';
        if (session()->has('mode') && session('mode') == 'employer') {
            $mode = 'employer';
        }
        if ($mode == 'owner') {
            $userBuilder = self::getTeam($user_id, $userBuilder);
        }
        else {
            $userBuilder = self::getCoworkers($user_id, $userBuilder);
        }
        return $userBuilder;
    }
}