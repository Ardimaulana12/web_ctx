<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const COLUMNS = [
        'id' => 'id_hubungi',
        'name' => 'nama',
        'company' => 'perusahaan',
        'subject' => 'subjek',
        'message' => 'pesan',
    ];

    public function up(): void
    {
        $this->rename('inquiries', 'hubungi_kami', self::COLUMNS);
    }

    public function down(): void
    {
        $this->rename('hubungi_kami', 'inquiries', array_flip(self::COLUMNS));
    }

    private function rename(string $from, string $to, array $columns): void
    {
        $connection = DB::connection();
        if (! in_array($connection->getDriverName(), ['mysql', 'mariadb'], true)) {
            throw new RuntimeException('This data-preserving rename requires MySQL or MariaDB.');
        }
        if (! Schema::hasTable($from) || Schema::hasTable($to)) {
            throw new RuntimeException('Unexpected contact table state; no changes were made.');
        }

        $quote = static fn (string $identifier): string => '`'.str_replace('`', '``', $identifier).'`';
        $source = $quote($connection->getTablePrefix().$from);
        $target = $quote($connection->getTablePrefix().$to);
        $definition = (array) $connection->selectOne("SHOW CREATE TABLE $source");
        $create = $definition['Create Table'];
        $changes = [];
        foreach ($columns as $old => $new) {
            // Preserve the complete server definition, including type, collation,
            // nullability, defaults, comments and AUTO_INCREMENT. CHANGE also
            // supports MariaDB 10.4, which does not support RENAME COLUMN.
            if (! preg_match('/^\s*'.preg_quote($quote($old), '/').'\s+(.+),$/m', $create, $match)) {
                throw new RuntimeException("Cannot safely read column $old; no changes were made.");
            }
            $changes[] = 'CHANGE COLUMN '.$quote($old).' '.$quote($new).' '.$match[1];
        }
        // A single ALTER avoids intermediate table/column naming states.
        $changes[] = "RENAME TO $target";
        $connection->statement("ALTER TABLE $source ".implode(', ', $changes));
    }
};
