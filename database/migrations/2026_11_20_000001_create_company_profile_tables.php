<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahasa', function (Blueprint $table) {
            $table->increments('id_bahasa');
            $table->string('kode_bahasa', 10)->unique();
            $table->string('nama_bahasa', 50);
            $table->string('lokal_bahasa', 50);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->index(['status', 'is_default']);
        });
        Schema::create('halaman_website', function (Blueprint $table) {
            $table->increments('id_halaman');
            $table->string('slug', 100)->unique();
            $table->string('nama_halaman', 100)->unique();
            $table->text('deskripsi');
            $table->integer('urutan');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->index(['status', 'urutan']);
        });
        Schema::create('konten_website', function (Blueprint $table) {
            $table->increments('id_konten');
            $table->unsignedInteger('id_halaman');
            $table->unsignedInteger('id_bahasa');
            $table->string('judul', 255);
            $table->text('isi');
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->foreign('id_halaman')->references('id_halaman')->on('halaman_website')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('id_bahasa')->references('id_bahasa')->on('bahasa')->restrictOnDelete()->cascadeOnUpdate();
            $table->unique(['id_halaman', 'id_bahasa', 'judul'], 'konten_page_language_section_unique');
            $table->index(['id_bahasa', 'status']);
        });
        Schema::create('menu_navigasi', function (Blueprint $table) {
            $table->increments('id_menu');
            $table->unsignedInteger('id_halaman');
            $table->string('nama_menu', 100);
            $table->string('ikon', 50)->nullable();
            $table->integer('urutan');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->foreign('id_halaman')->references('id_halaman')->on('halaman_website')->restrictOnDelete()->cascadeOnUpdate();
            $table->unique(['id_halaman', 'nama_menu']);
            $table->index(['status', 'urutan']);
        });
        Schema::create('kontak', function (Blueprint $table) {
            $table->increments('id_kontak');
            $table->unsignedInteger('id_bahasa');
            $table->text('alamat');
            $table->string('email', 100);
            $table->string('telepon', 50);
            $table->string('peta_lokasi', 255)->nullable();
            $table->string('jam_operasional', 100)->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->foreign('id_bahasa')->references('id_bahasa')->on('bahasa')->restrictOnDelete()->cascadeOnUpdate();
            $table->unique(['id_bahasa', 'email']);
            $table->index(['id_bahasa', 'status']);
        });
    }

    public function down(): void
    {
        foreach (['kontak', 'menu_navigasi', 'konten_website', 'halaman_website', 'bahasa'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
