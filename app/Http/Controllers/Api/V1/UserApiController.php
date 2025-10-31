<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Interfaces\UserRepo;
use Illuminate\Http\Request;

class UserApiController extends Controller {
    public function __construct(private UserRepo $repo) {}

    public function all() {
        $lista = $this->repo->all();
        return response()->json(['users' => $lista]);
    }

    public function get(Request $request) {
        try {
            $id = filter_var($request->route('id'), FILTER_VALIDATE_INT);
            $item = $this->repo->get($id);
            return response()->json(['user' => $item]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function add(Request $request) {
        try {
            $user = User::factory()->make($request->input());
            $this->repo->add($user);
            return response()->json(['user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function update(Request $request) {
        try {
            $id = filter_var($request->route('id'), FILTER_VALIDATE_INT);
            $user = $this->repo->get($id);
            $user->fill($request->all());
            $this->repo->update($user);
            return response()->json(['user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function remove(Request $request) {
        try {
            $id = filter_var($request->input('id'), FILTER_VALIDATE_INT);
            $this->repo->delete($id);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}
