<?php

namespace App\Models\Access;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Permission extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    /**
     * The roles that belong to the Permission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    /**
     * Get the rolePermission that owns the Permission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rolePermission(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RolePermission::class);
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
     * Generate permission for specific table.
     *
     * @param   string  $table_name
     * @param   boolean $is_maintenance
     *
     * @return  void
     */
    public static function generateFor($table_name, $is_maintenance = false): void
    {
        $permissions = [];
        $permissions[] = self::firstOrCreate(['key' => "browse_{$table_name}", 'description' => "Browse {$table_name}", 'table_name' => $table_name]);
        $permissions[] = self::firstOrCreate(['key' => "read_{$table_name}", 'description' => "Read {$table_name}", 'table_name' => $table_name]);
        $permissions[] = self::firstOrCreate(['key' => "edit_{$table_name}", 'description' => "Edit {$table_name}", 'table_name' => $table_name]);
        $permissions[] = self::firstOrCreate(['key' => "add_{$table_name}", 'description' => "Add {$table_name}", 'table_name' => $table_name]);
        $permissions[] = self::firstOrCreate(['key' => "delete_{$table_name}", 'description' => "Delete {$table_name}", 'table_name' => $table_name]);

        if($is_maintenance) {
            $permissions[] = self::firstOrCreate(['key' => "maintenance_{$table_name}", 'description' => "Maintenance {$table_name}", 'table_name' => $table_name]);
        }

        $administrator = Role::where('name', 'administrator')->firstOrFail();

        if(!is_null($administrator)) {
            foreach ($permissions as $permission) {
                $rolePermission = RolePermission::where('role_id', $administrator->id)
                    ->where('permission_id', $permission->id)
                    ->first();

                if(is_null($rolePermission)) {
                    $rolePermission = new RolePermission();
                    $rolePermission->role_id = $administrator->id;
                    $rolePermission->permission_id = $permission->id;
                    $rolePermission->save();
                }
            }
        }
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
        RolePermission::whereIn('permission_id', $permissions)->delete();
        self::where(['table_name' => $table_name])->delete();
    }
}
