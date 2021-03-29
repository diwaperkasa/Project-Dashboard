<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tasks extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    static public function datatable()
    {
        $request = app('request');

        $column = is_array($request->input('columns')) ? $request->input('columns') : [];
        $order = is_array($request->input('order')) ? $request->input('order') : [];
        $where = [];

        foreach ($column as $col) {
            $search = $col['search'];

            if (! empty($search['value'])) {
                if ($search['regex'] === 'true') {
                    $where[] = [$col['name'], $search['value']];
                } else {
                    $where[] = [$col['name'], 'like', '%'.$search['value'].'%'];
                }
            }
        }

        $search = $request->input('search.value');

        $data = self::where($where)
            ->whereNull('tasks.deleted_at')
            ->when(isset($search), function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('tasks.name', 'like', "%{$search}%");
                    $query->orWhere('tasks.description', 'like', "%{$search}%");
                    $query->orWhere('tasks.url', 'like', "%{$search}%");
                });
            })
            ->when(! empty($order), function ($query) use ($column, $order) {
                foreach ($order as $o) {
                    if ($col_name = @$column[ $o['column'] ]['name']) {
                        $query->orderBy($col_name, strtoupper($o['dir']));
                    }
                }
            })
            ->offset((int) $request->input('start') ?: 0)
            ->limit((int) $request->input('length') ?: 10)
            ->get();
        
        $length = self::where($where)
            ->whereNull('tasks.deleted_at')
            ->when(isset($search), function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('tasks.name', 'like', "%{$search}%");
                    $query->orWhere('tasks.description', 'like', "%{$search}%");
                    $query->orWhere('tasks.url', 'like', "%{$search}%");
                });
            })
            ->count();

        $totalLength = self::where($where)
            ->whereNull('tasks.deleted_at')
            ->count();

        return (object) [
            'data' => $data,
            'recordsTotal' => $totalLength,
            'recordsFiltered' => $length,
            'draw' => (int) ($request->input('draw') ?: 0)
        ];
    }
}
