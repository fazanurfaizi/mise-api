<?php

namespace App\Http\Controllers\Admin;

use DB;
use Exception;
use App\Models\Product\Variant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class VariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $variants = QueryBuilder::for(Variant::class)
            ->allowedFields(['id', 'parent_id', 'name'])
            ->allowedFilters(['name'])
            ->defaultSort('-created_at')
            ->allowedSorts('id', 'parent_id', 'name')
            ->with('children')
            ->where('parent_id', null)
            ->jsonPaginate();

        return response()->json([
            'data' => $variants
        ], Response::HTTP_OK);
    }

    /**
     * Display a listing of the resource in the bin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function browseBin(Request $request)
    {
        $variants = QueryBuilder::for(Variant::class)
            ->allowedFields(['id', 'parent_id', 'name'])
            ->allowedFilters(['name'])
            ->defaultSort('-created_at')
            ->allowedSorts('id', 'parent_id', 'name')
            ->onlyTrashed()
            ->with('children')
            ->where('parent_id', null)
            ->jsonPaginate();

        return response()->json([
            'data' => $variants
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $variant = Variant::create([
                'parent_id' => $request->get('parent_id'),
                'name' => $request->get('name'),
            ]);

            DB::commit();

            return response()->json([
                'message' => __('Created successfully')
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $variant = Variant::with('children')->where('id', $id)->first();

        return response()->json([
            'data' => $variant
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $variant = Variant::with('children')->where('id', $id)->first();

            $variant->update([
                'parent_id' => $request->get('parent_id'),
                'name' => $request->get('name'),
            ]);

            DB::commit();

            return response()->json([
                'message' => __('Updated successfully')
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $variant = Variant::with('children')->where('id', $id)->first();
            $variant->delete();

            DB::commit();

            return response()->json([
                'message' => __('Deleted successfully')
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Force remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDestroy($id)
    {
        DB::beginTransaction();

        try {
            $variant = Variant::withTrashed()->findOrFail($id);
            $variant->forceDelete();

            DB::commit();

            return response()->json([
                'message' => __('Deleted successfully')
            ], Response::HTTP_OK);
        } catch (Excption $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Mulitple remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function multipleDestroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $ids = explode(',', $request->ids);

            foreach ($ids as $id) {
                $variant = Variant::findOrFail($id);
                $variant->delete();
            }

            DB::commit();

            return response()->json([
                'message' => __('Deleted successfully')
            ], Response::HTTP_OK);
        } catch (Excption $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Mulitple force remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function multipleForceDestroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $ids = explode(',', $request->ids);

            foreach ($ids as $id) {
                $variant = Variant::findOrFail($id);
                $variant->forceDelete();
            }

            DB::commit();

            return response()->json([
                'message' => __('Deleted successfully')
            ], Response::HTTP_OK);
        } catch (Excption $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $variant = Variant::withTrashed()->findOrFail($id);
            $variant->restore();

            DB::commit();

            return response()->json([
                'message' => __('Restored successfully')
            ], Response::HTTP_OK);
        } catch (Excption $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Mulitple restore the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function multipleRestore(Request $request)
    {
        DB::beginTransaction();

        try {
            $ids = explode(',', $request->ids);

            foreach ($ids as $id) {
                $variant = Variant::onlyTrashed()->findOrFail($id);
                $variant->restore();
            }

            DB::commit();

            return response()->json([
                'message' => __('Restored successfully')
            ], Response::HTTP_OK);
        } catch (Excption $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
