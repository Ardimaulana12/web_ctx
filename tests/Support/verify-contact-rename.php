<?php

// Explicit, one-time integration check. Does not create/drop/truncate any table.
require dirname(__DIR__, 2).'/vendor/autoload.php';
$app = require dirname(__DIR__, 2).'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (! Schema::hasTable('inquiries') || Schema::hasTable('hubungi_kami')) {
    throw new RuntimeException('Run only before the rename migration; nothing was changed.');
}
$migrationPath = 'database/migrations/2026_11_20_000002_rename_inquiries_to_hubungi_kami.php';
$mapping = ['id_hubungi' => 'id', 'nama' => 'name', 'perusahaan' => 'company', 'subjek' => 'subject', 'pesan' => 'message'];
$snapshot = static function (string $table) use ($mapping): array {
    $key = $table === 'inquiries' ? 'id' : 'id_hubungi';
    $rows = DB::table($table)->orderBy($key)->get()->map(function ($row) use ($mapping) {
        $normalized = [];
        foreach ((array) $row as $column => $value) {
            $normalized[$mapping[$column] ?? $column] = $value;
        }

        return $normalized;
    })->all();
    $definition = (array) DB::selectOne('SHOW CREATE TABLE `'.$table.'`');
    $create = str_replace('`'.$table.'`', '`contact_table`', $definition['Create Table']);
    foreach ($mapping as $new => $old) {
        $create = str_replace('`'.$new.'`', '`'.$old.'`', $create);
    }

    return ['rows' => $rows, 'schema' => $create];
};
$before = $snapshot('inquiries');
$backup = storage_path('app/private/contact-rename-before-'.date('Ymd-His').'-'.bin2hex(random_bytes(4)).'.json');
if (file_put_contents($backup, json_encode($before, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT), LOCK_EX) === false) {
    throw new RuntimeException('Cannot save pre-migration snapshot; no changes were made.');
}
$verify = static function (string $table) use ($snapshot, $before): void {
    $after = $snapshot($table);
    if ($before !== $after) {
        throw new RuntimeException('Data/schema verification failed; stopped without any further changes. Inspect the private snapshot.');
    }
    $other = $table === 'inquiries' ? 'hubungi_kami' : 'inquiries';
    if (Schema::hasTable($other)) {
        throw new RuntimeException('Unexpected duplicate table; verification stopped.');
    }
    echo $table.': '.count($after['rows'])." original rows, values and full schema preserved.\n";
};
$run = static function (string $command, array $options): void {
    if (Artisan::call($command, $options) !== 0) {
        throw new RuntimeException(Artisan::output());
    }
};
$run('migrate', ['--path' => $migrationPath, '--force' => true]);
$verify('hubungi_kami');
$run('migrate:rollback', ['--path' => $migrationPath, '--step' => 1, '--force' => true]);
$verify('inquiries');
$run('migrate', ['--path' => $migrationPath, '--force' => true]);
$verify('hubungi_kami');
echo "Rename, rollback and reapply verified. Private pre-migration snapshot retained.\n";
