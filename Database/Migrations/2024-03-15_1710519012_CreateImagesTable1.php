<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateImagesTable1 implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return ["CREATE TABLE IF NOT EXISTS images (
            uid VARCHAR(500) PRIMARY KEY,
            name VARCHAR(100),
            filetype VARCHAR(100),
            deleteKey VARCHAR(32) UNIQUE,
            expire_datetime DATETIME NULL,
            viewCount INT,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP


           
        );
        "];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [];
    }
}