<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Task\TaskRepositories;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseApi;
use App\Exceptions\Custom\ErrorValidator;

class TaskController extends Controller
{
    public function create(Request $request, TaskRepositories $repo)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'string|required',
                'description' => 'string|required',
                'url' => 'string|required',
            ]);
    
            if ($validator->fails()) {
                throw new ErrorValidator("Error Validation", $validator->errors());
            }

            DB::beginTransaction();
            $data = $repo->create($validator->validated());
            DB::commit();
            return ResponseApi::success($data, 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            report($th);
            return ResponseApi::errorHandle($th);
        }
    }

    public function update($id, Request $request, TaskRepositories $repo)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'string|required',
                'description' => 'string|required',
                'url' => 'string|required',
            ]);
    
            if ($validator->fails()) {
                throw new ErrorValidator("Error Validation", $validator->errors());
            }

            DB::beginTransaction();
            $data = $repo->update($id, $validator->validated());
            DB::commit();
            return ResponseApi::success($data);
        } catch (\Throwable $th) {
            DB::rollBack();
            report($th);
            return ResponseApi::errorHandle($th);
        }
    }

    public function delete($id, Request $request, TaskRepositories $repo)
    {
        try {
            $data = $repo->delete($id);
            return ResponseApi::success($data);
        } catch (\Throwable $th) {
            report($th);
            return ResponseApi::errorHandle($th);
        }
    }

    public function list(Request $request, TaskRepositories $repo)
    {
        try {
            $data = $repo->list();
            return ResponseApi::success($data);
        } catch (\Throwable $th) {
            report($th);
            return ResponseApi::errorHandle($th);
        }
    }
}