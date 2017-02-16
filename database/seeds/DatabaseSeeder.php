<?php

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Project::truncate();
        Task::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call(UserTableSeeder::class);
        $this->call(ProjectTableSeeder::class);
        $this->call(TaskTableSeeder::class);

        Model::reguard();
    }
}
