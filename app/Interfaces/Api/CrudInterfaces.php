<?php

namespace App\Interfaces\Api;

interface CrudInterfaces {
    public function create($request);
    public function update($id, $request);
    public function delete($id);
    public function list();
}