<?php
   
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
    
class AddSocialLoginField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('social_id')->nullable();
            $table->string('social_type')->nullable();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('social_id');
            $table->dropColumn('social_type');
         });
    }
}