<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->insert([
            'title' => 'Post 1',
            'post' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In non convallis orci. Vivamus fringilla, augue quis porta scelerisque, ligula leo vulputate nisl, a semper justo orci in velit. Vivamus vel massa dictum, dictum odio sed, sodales nunc. Pellentesque ullamcorper tempor enim eu posuere. Pellentesque nec nulla vitae leo tempor rhoncus id sit amet lectus. Proin cursus molestie diam vitae aliquam. Vivamus facilisis rutrum purus, efficitur aliquam orci condimentum eu. Maecenas sed elementum justo, pharetra porta eros. Nulla eu tellus bibendum, pulvinar massa at, eleifend quam. Nam pellentesque risus eu nisi molestie, a scelerisque urna ultrices. Sed ac eros suscipit, fermentum dolor pretium, pellentesque lorem. Phasellus tristique eleifend blandit. Ut efficitur enim aliquam diam gravida laoreet. Suspendisse mattis nisl eu faucibus tempor. Quisque condimentum enim odio, sit amet elementum nulla malesuada eu.'
        ]);

        DB::table('posts')->insert([
            'title' => 'Post 2',
            'post' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In non convallis orci. Vivamus fringilla, augue quis porta scelerisque, ligula leo vulputate nisl, a semper justo orci in velit. Vivamus vel massa dictum, dictum odio sed, sodales nunc. Pellentesque ullamcorper tempor enim eu posuere. Pellentesque nec nulla vitae leo tempor rhoncus id sit amet lectus. Proin cursus molestie diam vitae aliquam. Vivamus facilisis rutrum purus, efficitur aliquam orci condimentum eu. Maecenas sed elementum justo, pharetra porta eros. Nulla eu tellus bibendum, pulvinar massa at, eleifend quam. Nam pellentesque risus eu nisi molestie, a scelerisque urna ultrices. Sed ac eros suscipit, fermentum dolor pretium, pellentesque lorem. Phasellus tristique eleifend blandit. Ut efficitur enim aliquam diam gravida laoreet. Suspendisse mattis nisl eu faucibus tempor. Quisque condimentum enim odio, sit amet elementum nulla malesuada eu.'
        ]);
    }
}
