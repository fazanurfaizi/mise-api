<?php

namespace App\Http\Controllers\Admin;

use DB;
use Exception;
use App\Models\User\User;
use App\Models\Access\Role;
use App\Http\Resources\User\UserCollection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFields(['id', 'name', 'username', 'email', 'avatar'])
            ->allowedFilters(['name', 'email'])
            ->defaultSort('-created_at')
            ->allowedSorts('id', 'name', 'email', 'created_at')
            ->jsonPaginate();

        return response()->json([
            'data' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->get('name'),
                'username' => $request->get('username'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password')),
            ]);

            $user->markEmailAsVerified();

            $role = Role::findOrFail($request->get('role'));
            $user->assignRole($role);

            DB::commit();

            $message = __(
                'User created successfully. We sent a confirmation email to :email.',
                ['email' => $user->email]
            );

            return response()->json([
                'message' => $message
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'data' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            $user->update([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
            ]);

            if($request->get('role')) {
                $role = Role::findOrFail($request->get('role'));
                $user->assignRole($role);
            }

            DB::commit();

            return response()->json([
                'message' => 'User updated successfully'
            ]);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user = User::find($id);
            $user->delete();

            DB::commit();

            return response()->json([
                'message' => 'User deleted successfully'
            ]);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
