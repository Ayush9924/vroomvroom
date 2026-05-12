<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void // this method runs when we migrate the database
    { // open the up method
        Schema::table('users', function (Blueprint $table) { // select the existing users table
            $table->boolean('is_admin')->default(false); // add a boolean column for admin status, defaulting to false (normal user)
        }); // close the table modification
    } // close the up method

    /**
     * Reverse the migrations.
     */
    public function down(): void // this method runs if we ever rollback this migration
    { // open the down method
        Schema::table('users', function (Blueprint $table) { // select the existing users table
            $table->dropColumn('is_admin'); // remove the is_admin column if we undo this step
        }); // close the table modification
    } // close the down method
};
