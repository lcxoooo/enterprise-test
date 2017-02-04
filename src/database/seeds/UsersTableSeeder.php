<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'email' => 'test@test.com',
                'name' => '',
                'password' => '$2y$10$zSQjz9W76FxNNrgcerpNxO6GRunwysMj/Mvxb5pBaL6GlV9EACyYa',
                'avatar' => NULL,
                'created_at' => '2016-10-27 06:50:02',
                'updated_at' => '2016-10-27 06:50:02',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
