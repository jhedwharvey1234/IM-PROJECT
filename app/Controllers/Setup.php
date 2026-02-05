<?php

namespace App\Controllers;

class Setup extends BaseController
{
    public function index()
    {
         $migration = service('migrations');
         try {
             $migration->latest();
             echo "Migration ran successfully.";
         } catch (\Exception $e) {
             echo "Migration failed: " . $e->getMessage();
         }
    }
    public function dropTable()
    {
         $migration = service('migrations');
         try {
             $migration->regress(0);
             echo "Migration rolled back successfully.";
         } catch (\Exception $e) {
             echo "Rollback failed: " . $e->getMessage();
         }
    }
    public function UserSeed()
    {
         $seeder = \Config\Database::seeder();
         try {
             $seeder->call('UserSeeder');
             echo "Seeding completed successfully.";
         } catch (\Exception $e) {
             echo "Seeding failed: " . $e->getMessage();
         }
    }
}
