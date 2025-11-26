<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserService
{
    public function getUsers(Request $request)
    {
        $query = User::query()->with('roles');

        if ($search = $request->input('search')) {
            $query->search($search);
        }

        if ($roleId = $request->input('role_id')) {
            $query->whereHas('roles', function ($q) use ($roleId) {
                $q->where('id', $roleId);
            });
        }

        if ($request->filled('date_range')) {
            $this->applyDateRangeFilter($request->date_range, $query);
        }

        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = strtolower($request->input('sort_order', 'desc')) === 'asc' ? 'asc' : 'desc';
        $sortColumn = $this->resolveSortColumn($sortBy);

        $query->orderBy($sortColumn, $sortOrder);

        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        return $query->paginate($perPage)->appends($request->query());
    }

    protected function resolveSortColumn(string $sortBy): string
    {
        return match ($sortBy) {
            'name' => 'first_name',
            'username' => 'name',
            'user_id' => 'user_id',
            'date' => 'created_at',
            default => 'id',
        };
    }

    protected function applyDateRangeFilter(string $range, Builder $query): void
    {
        $parts = preg_split('/\s*-\s*/', $range);
        $start = trim($parts[0] ?? '');
        $end = trim($parts[1] ?? $start);

        if (! $start) {
            return;
        }

        $startDate = $this->parseDate($start);
        $endDate = $this->parseDate($end);

        $query->whereBetween('created_at', [
            $startDate->copy()->startOfDay(),
            $endDate->copy()->endOfDay(),
        ]);
    }

    protected function parseDate(string $value): Carbon
    {
        $formats = ['m/d/Y', 'd/m/Y', 'Y-m-d'];

        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $value);
            if ($date instanceof \DateTime) {
                return Carbon::instance($date);
            }
        }

        return now();
    }
}
