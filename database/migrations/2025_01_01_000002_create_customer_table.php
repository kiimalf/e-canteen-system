<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->string('idcustomer', 100)->primary();
            $table->string('nama', 255);
        });

        // Create trigger for auto-generating idcustomer
        DB::unprepared("
            CREATE TRIGGER generate_idcustomer
            BEFORE INSERT ON customer
            FOR EACH ROW
            BEGIN
                DECLARE last_id INT;
                
                SELECT IFNULL(MAX(CAST(SUBSTRING(idcustomer, 7) AS UNSIGNED)), 0)
                INTO last_id
                FROM customer;
                SET NEW.idcustomer = CONCAT('Guest_', LPAD(last_id + 1, 5, '0'));
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS generate_idcustomer');
        Schema::dropIfExists('customer');
    }
};
