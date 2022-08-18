<?php

namespace App\Models\Access;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission as Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Permission extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($permission) {
            $permission->guard_name = 'api';
        });
    }

    protected static $logAttributes = true;
    protected static $logFillable = [
        'key',
        'description',
        'table_name'
    ];
    protected static $logName = 'Permission';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(self::$logFillable);
    }

    /**
     * Remove permission for specific table.
     *
     * @param   string  $table_name
     *
     * @return  void
     */
    public static function removeFrom($table_name): void
    {
        $permissions = self::where(['table_name' => $table_name])->get();
        $permissions = collect($permissions)->pluck('id')->toArray();
        DB::table('role_has_permissions')->whereIn('permission_id', $permissions)->delete();
        self::where(['table_name' => $table_name])->delete();
    }
}
