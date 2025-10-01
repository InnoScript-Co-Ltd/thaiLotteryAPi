<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function store(UserCreateRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $user = User::create($payload->toArray());
            DB::commit();

            return $this->success($user, 'user is created successfully');
        } catch (Exception $e) {
            return $this->internalServerError();
        }
    }

    public function index()
    {
        try {
            $users = User::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success($users, 'user list are retrived successfully');
        } catch (Exception $e) {
            return $this->internalServerError();
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);

            return $this->success($user, 'user is retrived successfully');
        } catch (Exception $e) {
            return $this->internalServerError();
        }
    }

    public function update($id, UserUpdateRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            $user->update($payload->toArray());
            DB::commit();

            return $this->success($payload, 'user is updated successfully');
        } catch (Exception $e) {
            return $this->internalServerError();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrfail($id);
            $user->update(['status' => 'DELETED']);
            $user->delete($id);

            DB::commit();

            return $this->success($user, 'user is deleted successfully');
        } catch (Exception $e) {
            return $this->internalServerError();
        }
    }
}
