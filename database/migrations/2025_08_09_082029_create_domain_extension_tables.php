<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1) Cities (lookup)
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('district')->nullable();
            $table->string('country')->default('Sri Lanka');
            $table->timestamps();
        });

        // 2) Addresses (user addresses)
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('label', 60)->nullable();
            $table->string('line1', 200);
            $table->string('line2', 200)->nullable();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->string('postal_code', 20)->nullable();
            $table->timestamps();
            $table->index(['user_id']);
        });

        // 3) Reservations (buyer reserves a property)
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->restrictOnDelete();
            $table->foreignId('buyer_id')->constrained('users')->restrictOnDelete();
            $table->decimal('fee', 12, 2);
            $table->string('status', 20)->default('pending'); // pending|paid|canceled|expired|completed
            $table->timestamp('reserved_at')->useCurrent();
            $table->index(['property_id']);
            $table->index(['buyer_id']);
        });

        // 4) Payments (for reservations)
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('method', 30);  // card|bank|cash|mobile
            $table->string('status', 20)->default('pending'); // pending|paid|failed|refunded
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index(['reservation_id']);
        });

        // 5) Invoices (1:1 with reservation)
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->unique()->constrained('reservations')->cascadeOnDelete();
            $table->decimal('total', 12, 2);
            $table->text('pdf_path')->nullable();
            $table->timestamp('issued_at')->useCurrent();
        });

        // 6) Reviews (1 per user per property)
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1..5
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->unique(['property_id','user_id']);
            $table->index(['property_id']);
        });

        // 7) Favorites (many:many as a pivot)
        Schema::create('favorites', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->primary(['user_id','property_id']);
        });

        // 8) Messages (buyer <-> seller, optional property)
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('to_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();
            $table->text('content');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            $table->index(['to_user_id']);
        });

        // 9) Notifications (simple)
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type', 80);
            $table->jsonb('data')->default(DB::raw("'{}'::jsonb"));
            $table->timestamp('read_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['user_id']);
        });

        // 10) Audit logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('entity', 60);
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('action', 30);
            $table->jsonb('details')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['entity','entity_id']);
        });

        // 11) Add avg_rating to existing properties (keeps your current columns)
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'avg_rating')) {
                $table->decimal('avg_rating', 3, 2)->default(0)->after('is_available');
                $table->index(['category_id','is_available']); // helpful query index
            }
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'avg_rating')) {
                $table->dropColumn('avg_rating');
            }
            $table->dropIndex(['properties_category_id_is_available_index']);
        });

        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('cities');
    }
};
