<?php

namespace Tests\Feature;

use Database\Seeders\WebsiteSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class WebsiteMigrationTest extends TestCase
{
    public function test_migration_rollback_and_reapply_in_isolated_mysql_database(): void
    {
        $original = DB::getDefaultConnection();
        if (! in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'])) {
            $this->markTestSkipped('Requires a MySQL-compatible server.');
        }
        $database = 'ctx_erd_test_'.bin2hex(random_bytes(8));
        $connection = DB::connection();
        $connection->statement("CREATE DATABASE `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        try {
            config(['database.connections.erd_test' => array_replace(config("database.connections.$original"), ['database' => $database, 'url' => null])]);
            DB::setDefaultConnection('erd_test');
            Schema::clearResolvedInstance('db.schema');
            $migration = require database_path('migrations/2026_11_20_000001_create_company_profile_tables.php');
            $migration->up();
            $this->assertCount(5, Schema::getTables($database));
            $this->seed(WebsiteSeeder::class);
            $this->assertSame(2, DB::table('bahasa')->count());
            $this->assertSame(6, DB::table('halaman_website')->count());
            $this->assertSame(14, DB::table('konten_website')->count());
            $this->assertSame(6, DB::table('menu_navigasi')->count());
            $this->assertSame(2, DB::table('kontak')->count());
            $this->assertCount(2, Schema::getForeignKeys('konten_website'));
            $migration->down();
            $this->assertCount(0, Schema::getTables($database));
            $migration->up();
            $this->seed(WebsiteSeeder::class);
            $this->assertSame(14, DB::table('konten_website')->count());
        } finally {
            DB::setDefaultConnection($original);
            Schema::clearResolvedInstance('db.schema');
            DB::purge('erd_test');
            $connection->statement("DROP DATABASE `$database`");
        }
    }
}
