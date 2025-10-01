<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminCreateRequest;
use App\Http\Requests\AdminUpdateRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function store(AdminCreateRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $admin = Admin::create($payload->toArray());
            DB::commit();

            return $this->success($admin, 'admin is created successfully');
        } catch (Exception $e) {
            return $this->internalServerError();
        }
    }

    public function index()
    {
        try {
            $admins = Admin::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success($admins, 'admin list are retrived successfully');
        } catch (Exception $e) {
            return $this->internalServerError();
        }
    }

    public function show($id)
    {
        try {
            $admin = Admin::findOrFail($id);

            return $this->success($admin, 'admin is retrived successfully');
        } catch (Exception $e) {
            return $this->internalServerError();
        }
    }

    public function update($id, AdminUpdateRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $admin = Admin::findOrFail($id);
            $admin->update($payload->toArray());
            DB::commit();

            return $this->success($payload, 'admin is updated successfully');
        } catch (Exception $e) {
            return $this->internalServerError();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $admin = Admin::findOrfail($id);
            $admin->update(['status' => 'DELETED']);
            $admin->delete($id);

            DB::commit();

            return $this->success($admin, 'admin is deleted successfully');
        } catch (Exception $e) {
            return $this->internalServerError();
        }
    }
}
