<?php
/**
 * Invoice Ninja (https://invoiceninja.com).
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2022. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://www.elastic.co/licensing/elastic-license
 */

namespace App\Filters;

use App\Models\Design;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

/**
 * DesignFilters.
 */
class DesignFilters extends QueryFilters
{
    /**
     * Filter based on search text.
     *
     * @param string query filter
     * @return Builder
     * @deprecated
     */
    public function filter(string $filter = '') : Builder
    {
        if (strlen($filter) == 0) {
            return $this->builder;
        }

        return  $this->builder->where(function ($query) use ($filter) {
            $query->where('designs.name', 'like', '%'.$filter.'%');
        });
    }

    /**
     * Sorts the list based on $sort.
     *
     * @param string sort formatted as column|asc
     * @return Builder
     */
    public function sort(string $sort) : Builder
    {
        $sort_col = explode('|', $sort);

        return $this->builder->orderBy($sort_col[0], $sort_col[1]);
    }

    /**
     * Returns the base query.
     *
     * @param int company_id
     * @param User $user
     * @return Builder
     * @deprecated
     */
    public function baseQuery(int $company_id, User $user) : Builder
    {
        $query = DB::table('designs')
            ->join('companies', 'companies.id', '=', 'designs.company_id')
            ->where('designs.company_id', '=', $company_id)
            ->select(
                'designs.id',
                'designs.name',
                'designs.design',
                'designs.created_at',
                'designs.created_at as design_created_at',
                'designs.deleted_at',
                'designs.is_deleted',
                'designs.user_id',
            );

        /*
         * If the user does not have permissions to view all invoices
         * limit the user to only the invoices they have created
         */
        if (Gate::denies('view-list', Design::class)) {
            $query->where('designs.user_id', '=', $user->id);
        }

        return $query;
    }

    /**
     * Filters the query by the users company ID.
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function entityFilter()
    {
        //return $this->builder->whereCompanyId(auth()->user()->company()->id);
        return $this->builder->where('company_id', auth()->user()->company()->id)->orWhere('company_id', null)->orderBy('id','asc');
    }
}
