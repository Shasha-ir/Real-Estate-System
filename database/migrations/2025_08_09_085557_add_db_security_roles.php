<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
DO $$
BEGIN
  IF NOT EXISTS (SELECT 1 FROM pg_roles WHERE rolname = 'app_readonly') THEN CREATE ROLE app_readonly; END IF;
  IF NOT EXISTS (SELECT 1 FROM pg_roles WHERE rolname = 'app_writer')   THEN CREATE ROLE app_writer;   END IF;
  IF NOT EXISTS (SELECT 1 FROM pg_roles WHERE rolname = 'app_admin')    THEN CREATE ROLE app_admin;    END IF;
END $$;

GRANT USAGE ON SCHEMA public TO app_readonly, app_writer, app_admin;

GRANT SELECT ON ALL TABLES IN SCHEMA public TO app_readonly;
GRANT SELECT, INSERT, UPDATE ON ALL TABLES IN SCHEMA public TO app_writer;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO app_admin;

GRANT SELECT ON ALL SEQUENCES IN SCHEMA public TO app_readonly;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO app_writer, app_admin;

ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT SELECT ON TABLES TO app_readonly;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT SELECT, INSERT, UPDATE ON TABLES TO app_writer;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO app_admin;
SQL);
    }

    public function down(): void
    {
        // Keep roles for submission (safe). No down migration.
    }
};
