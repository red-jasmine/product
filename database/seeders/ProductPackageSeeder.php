<?php

namespace RedJasmine\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProductPackageSeeder extends Seeder
{
    public function run() : void
    {
        $this->brand();
        $this->category();
        $this->property();
        $this->groups();


    }

    protected function brand() : void
    {
        // 添加 品牌
        DB::table(config('red-jasmine-product.tables.prefix') .'brands')->insert([
            'id'           => 1,
            'name'         => '测试',
            'status'       => 'enable',
            'english_name' => 'test',
            'initial'      => 'T',
            'logo'         => fake()->imageUrl(360, 360)
        ]);

    }


    protected function category() : void
    {

        DB::table(config('red-jasmine-product.tables.prefix') .'product_categories')->insert([
            'id'        => 1,
            'name'      => '服装',
            'is_show'   => true,
            'parent_id' => 0,
            'status'    => 'enable',
            'image'     => fake()->imageUrl(360, 360)
        ]);

        DB::table(config('red-jasmine-product.tables.prefix') .'product_categories')->insert([
            'id'        => 2,
            'name'      => '男装',
            'parent_id' => 1,
            'is_show'   => true,
            'status'    => 'enable',
            'image'     => fake()->imageUrl(360, 360)
        ]);

        DB::table(config('red-jasmine-product.tables.prefix') .'product_categories')->insert([
            'id'        => 3,
            'name'      => '短袖',
            'parent_id' => 2,
            'is_show'   => true,
            'status'    => 'enable',
            'is_leaf'   => true,
            'image'     => fake()->imageUrl(360, 360)
        ]);
    }


    protected function property() : void
    {

        $groups = [
            [
                'id'   => 1,
                'name' => '中码',
            ],
            [
                'id'   => 2,
                'name' => '欧码',
            ],
        ];

        foreach ($groups as $group) {
            $group['status'] = 'enable';
            DB::table(config('red-jasmine-product.tables.prefix') .'product_property_groups')->insert($group);
        }

        $properties = [

            [
                'id'     => 20000,
                'name'   => '颜色',
                'type'   => 'select',
                'unit'   => '',
                'status' => 'enable',
            ],
            [
                'id'     => 30000,
                'name'   => '尺码',
                'type'   => 'select',
                'unit'   => '',
                'status' => 'enable',
            ],
            [
                'id'                => 1200000,
                'name'              => '风格',
                'type'              => 'select',
                'unit'              => '',
                'status'            => 'enable',
                'is_allow_multiple' => true,
                'is_allow_alias'    => true,
            ],
            [
                'id'     => 1300000,
                'name'   => '年份',
                'type'   => 'select',
                'unit'   => '',
                'status' => 'enable',
            ]
        ];

        foreach ($properties as $property) {
            DB::table(config('red-jasmine-product.tables.prefix') .'product_properties')->insert($property);
        }


        $colors = [
            [
                'id'     => 2000001,
                'pid'    => 20000,
                'name'   => '红色',
                'status' => 'enable',
            ],
            [
                'id'     => 2000002,
                'pid'    => 20000,
                'name'   => '白色',
                'status' => 'enable',
            ],
            [
                'id'     => 2000003,
                'pid'    => 20000,
                'name'   => '黄色',
                'status' => 'enable',
            ],
            [
                'id'     => 2000004,
                'pid'    => 20000,
                'name'   => '黑色',
                'status' => 'enable',
            ],
            [
                'id'     => 2000005,
                'pid'    => 20000,
                'name'   => '蓝色',
                'status' => 'enable',
            ],
        ];


        $sizes = [
            [
                'id'       => 3000001,
                'pid'      => 30000,
                'name'     => 'S',
                'status'   => 'enable',
                'group_id' => 1,
            ],
            [
                'id'       => 3000002,
                'pid'      => 30000,
                'name'     => 'M',
                'status'   => 'enable',
                'group_id' => 1,
            ],
            [
                'id'       => 3000003,
                'pid'      => 30000,
                'name'     => 'L',
                'status'   => 'enable',
                'group_id' => 1,
            ],
            [
                'id'       => 3000004,
                'pid'      => 30000,
                'name'     => 'XL',
                'status'   => 'enable',
                'group_id' => 1,
            ],
            [
                'id'       => 3000005,
                'pid'      => 30000,
                'name'     => 'XXL',
                'status'   => 'enable',
                'group_id' => 1,
            ],
            [
                'id'       => 3000006,
                'pid'      => 30000,
                'name'     => 'XXXL',
                'status'   => 'enable',
                'group_id' => 1,
            ],
            [
                'id'       => 3000007,
                'pid'      => 30000,
                'name'     => 'XXXXL',
                'status'   => 'enable',
                'group_id' => 1,
            ],
            [
                'id'       => 3000011,
                'pid'      => 30000,
                'name'     => '34',
                'status'   => 'enable',
                'group_id' => 2,
            ],
            [
                'id'       => 3000012,
                'pid'      => 30000,
                'name'     => '36',
                'status'   => 'enable',
                'group_id' => 2,
            ],
            [
                'id'       => 3000013,
                'pid'      => 30000,
                'name'     => '38',
                'status'   => 'enable',
                'group_id' => 2,
            ],
            [
                'id'       => 3000014,
                'pid'      => 30000,
                'name'     => '40',
                'status'   => 'enable',
                'group_id' => 2,
            ],
            [
                'id'       => 3000015,
                'pid'      => 30000,
                'name'     => '42',
                'status'   => 'enable',
                'group_id' => 2,
            ],
            [
                'id'       => 3000016,
                'pid'      => 30000,
                'name'     => '44',
                'status'   => 'enable',
                'group_id' => 2,
            ],
            [
                'id'       => 3000017,
                'pid'      => 30000,
                'name'     => '46',
                'status'   => 'enable',
                'group_id' => 2,
            ],
        ];


        $styles = [
            [
                'id'     => 120000001,
                'pid'    => 1200000,
                'name'   => '简约',
                'status' => 'enable',
            ],
            [
                'id'     => 120000002,
                'pid'    => 1200000,
                'name'   => '休闲',
                'status' => 'enable',
            ],
            [
                'id'     => 120000003,
                'pid'    => 1200000,
                'name'   => '运动',
                'status' => 'enable',
            ],
            [
                'id'     => 120000004,
                'pid'    => 1200000,
                'name'   => '民族',
                'status' => 'enable',
            ],
            [
                'id'     => 120000005,
                'pid'    => 1200000,
                'name'   => '复古',
                'status' => 'enable',
            ],

        ];


        $years = [
            [
                'id'     => 130000001,
                'pid'    => 1300000,
                'name'   => '2022',
                'status' => 'enable',
            ],
            [
                'id'     => 130000002,
                'pid'    => 1300000,
                'name'   => '2023',
                'status' => 'enable',
            ],
            [
                'id'     => 130000003,
                'pid'    => 1300000,
                'name'   => '2024',
                'status' => 'enable',
            ],
            [
                'id'     => 130000004,
                'pid'    => 1300000,
                'name'   => '2025',
                'status' => 'enable',
            ],
        ];


        foreach ([...$colors, ...$sizes, ...$styles, ...$years] as $value) {
            $value['status'] = 'enable';
            DB::table(config('red-jasmine-product.tables.prefix') .'product_property_values')->insert($value);
        }


    }


    protected function groups() : void
    {
        DB::table(config('red-jasmine-product.tables.prefix') .'product_groups')->insert([
            'id'         => 1,
            'owner_type' => 'seller',
            'owner_id'   => 1,
            'name'       => '男装',
            'parent_id'  => 0,
            'status'     => 'enable',
            'is_leaf'    => false,
            'image'      => fake()->imageUrl(360, 360)
        ]);


        DB::table(config('red-jasmine-product.tables.prefix') .'product_groups')->insert([
            'id'         => 2,
            'owner_type' => 'seller',
            'owner_id'   => 1,
            'name'       => '短袖',
            'parent_id'  => 1,
            'status'     => 'enable',
            'is_leaf'    => false,
            'image'      => fake()->imageUrl(360, 360)
        ]);
    }
}
