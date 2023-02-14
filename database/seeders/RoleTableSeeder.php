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
            'الفواتير',
            'قائمة الفواتير',
            'الفواتير المدفوعة',
            'الفواتير المدفوعة جزئيا',
            'الفواتير الغير مدفوعة',
            'ارشيف الفواتير',
            'التقارير',
            'تقرير الفواتير',
            'تقرير العملاء',
            'المستخدمين',
            'قائمة المستخدمين',
            'صلاحيات المستخدمين',
            'العملاء',
            'قائمة العملاء',
            'الاعدادات',
            'المنتجات',
            'الاقسام',


            'اضافة فاتورة',
            'تعديل فاتوره',
            'عرض الفاتوره',
            'حذف الفاتورة',
            'تصدير EXCEL',
            'تغير حالة الدفع',
            'ارشفة الفاتورة',
            'طباعةالفاتورة',
            'اضافة مرفق',
            'حذف المرفق',
            'تحميل المرفق',
            'عرض المرفق',

            'اضافة مستخدم',
            'تعديل مستخدم',
            'حذف مستخدم',

            'اضافة عميل',
            'تعديل عميل',
            'حذف عميل',

            'عرض صلاحية',
            'اضافة صلاحية',
            'تعديل صلاحية',
            'حذف صلاحية',

            'اضافة منتج',
            'تعديل منتج',
            'حذف منتج',

            'اضافة قسم',
            'تعديل قسم',
            'حذف قسم',
            'الاشعارات',
            'عرض التواصل',
            'تعديل التواصل',
            'عرض الدفعات'
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
