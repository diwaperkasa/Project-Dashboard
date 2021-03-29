<?php

namespace App\Repositories\Task;

use App\Interfaces\Api\CrudInterfaces;
use App\Model\Tasks;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaskRepositories implements CrudInterfaces
{
    public function create($request)
    {
        $createStatus = Tasks::create($request);
        return $createStatus;
    }

    public function update($id, $request)
    {
        $data = Tasks::find($id);

        if (! $data) {
            throw new NotFoundHttpException("Data is not found");
        }

        $data->update($request);
        return $request;
    }

    public function delete($id)
    {
        $data = Tasks::find($id);

        if (! $data) {
            throw new NotFoundHttpException("Data is not found");
        }

        return $data->delete();
    }

    public function list()
    {
        return Tasks::datatable();
    }
}