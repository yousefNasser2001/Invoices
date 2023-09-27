<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $permissions = [
            INVOICES_PERMISSION,
            INVOICES_LIST_PERMISSION,
            PAID_INVOICES_PERMISSION,
            PARTIAL_PAID_INVOICES_PERMISSION,
            UNPAID_INVOICES_PERMISSION,
            ARCHIVED_INVOICES_PERMISSION,
            REPORTS_PERMISSION,
            INVOICES_REPORT_PERMISSION,
            EMPLOYEES_LIST_PERMISSION,
            USERS_PERMISSION,
            USERS_LIST_PERMISSION,
            USERS_ROLES_PERMISSION,
            SETTINGS_PERMISSION,
            PRODUCTS_PERMISSION,
            SECTIONS_PERMISSION,
            CREATE_INVOICE_PERMISSION,
            DELETE_INVOICE_PERMISSION,
            EXCEL_PERMISSION,
            CHANGE_STATUS_PERMISSION,
            EDIT_INVOICE_PERMISSION,
            ARCHIVE_INVOICE_PERMISSION,
            PRINT_INVOICE_PERMISSION,
            CREATE_ATTACHEMENT_PERMISSION,
            DELETE_ATTACHEMENT_PERMISSION,
            CREATE_USER_PERMISSION,
            EDIT_USER_PERMISSION,
            DELETE_USER_PERMISSION,
            VIEW_ROLE_PERMISSION,
            CREATE_ROLE_PERMISSION,
            EDIT_ROLE_PERMISSION,
            DELETE_ROLE_PERMISSION,
            CREATE_PRODUCT_PERMISSION,
            EDIT_PRODUCT_PERMISSION,
            DELETE_PRODUCT_PERMISSION,
            CREATE_SECTION_PERMISSION,
            EDIT_SECTION_PERMISSION,
            DELETE_SECTION_PERMISSION,
            NOTIFICATIONS_PERMISSION,
        ];

        foreach ($permissions as $permission) {
            Permission::CREATE(['name' => $permission]);
        }
        $id = Role::CREATE(['name' => User::SUPER_ADMIN_ROLE])->givePermissionTo($permissions)->id;
    }
}
