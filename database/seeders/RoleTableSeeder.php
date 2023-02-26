<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = [
            'invoices',
            'invoices_list',
            'paid_invoices',
            'partial_invoices',
            'unpaid_invoices',
            'invoices_archive',
            'reports',
            'invoices_report',
            'customers_report',
            'users',
            'users_list',
            'users_permissions',
            'customers',
            'customers_list',
            'settings',
            'products',
            'sections',


            'invoice_add',
            'invoice_edit',
            'invoice_show',
            'invoice_delete',
            'excel_export',
            'payment_status_change',
            'invoice_archive',
            'invoice_print',
            'attachement_add',
            'attachement_delete',
            'attachement_download',
            'attachement_show',

            'user_add',
            'user_edit',
            'user_delete',

            'customer_add',
            'customer_edit',
            'customer_delete',

            'role_show',
            'role_add',
            'role_edit',
            'role_delete',

            'product_add',
            'product_edit',
            'product_delete',

            'section_add',
            'section_edit',
            'section_delete',

            'notification',
            'contact_show',
            'contact_edit',

            'payment_show'
        ];
        foreach ($permission as $value){
            Permission::create([
                "name"=>$value,
                "guard_name"=>"web"
            ]);
        }

        $role1 = [
            "name"=>"admin",
            "guard_name"=>"web"
        ];

        $role1 = Role::create($role1);

        $role1->givePermissionTo($permission);


    }

}
