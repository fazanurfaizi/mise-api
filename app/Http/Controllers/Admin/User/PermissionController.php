<?php

namespace App\Http\Controllers\Admin\User;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\User\Role;
use App\Models\User\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = QueryBuilder::for(Permission::class)
            ->allowedFields(['id', 'name'])
            ->allowedFilters(['name'])
            ->defaultSort('-created_at')
            ->allowedSorts('id', 'name', 'created_at')
            ->jsonPaginate();

        return response()->json([
            'data' => $permissions
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
        $permission = Permission::create([
            'name' => $request->get('name')
        ]);

        return response()->json([
            'message' => 'Permission created successfully'
        ]);
    }

    /**
     * Store a generate permission for specific table to storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {
        try {
            DB::beginTransaction();

            $permissions = [];
            $permissions[] = Permission::firstOrCreate(['name' => "{$request->get('table')}-viewAny"]);
            $permissions[] = Permission::firstOrCreate(['name' => "{$request->get('table')}-view"]);
            $permissions[] = Permission::firstOrCreate(['name' => "{$request->get('table')}-viewBin"]);
            $permissions[] = Permission::firstOrCreate(['name' => "{$request->get('table')}-create"]);
            $permissions[] = Permission::firstOrCreate(['name' => "{$request->get('table')}-update"]);
            $permissions[] = Permission::firstOrCreate(['name' => "{$request->get('table')}-delete"]);
            $permissions[] = Permission::firstOrCreate(['name' => "{$request->get('table')}-restore"]);

            $role = Role::firstOrCreate(['name' => $request->get('role')]);

            foreach ($permissions as $permission) {
                $role->givePermissionTo($permission);
            }

            DB::commit();

            return response()->json([
                'message' => 'Permission created successfully'
            ]);
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
     * @param  \App\Models\Access\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        return response()->json([
            'data' => $permission
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Access\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $permission->update([
            'name' => $request->get('name')
        ]);

        return response()->json([
            'message' => 'Permission updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Access\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->json([
            'message' => 'Permission deleted successfully'
        ]);
    }
}
