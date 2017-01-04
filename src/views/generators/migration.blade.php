<?php echo '<?php' ?>

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AbleSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for storing roles
        Schema::create('{{ $able['roles_table'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for storing groups
        Schema::create('{{ $able['groups_table'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('company_id')->unsigned()->nullable();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for storing permissions
        Schema::create('{{ $able['permissions_table'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('{{ $able['permission_role_table'] }}', function (Blueprint $table) {
            $table->integer('{{ $able['permission_foreign_key'] }}')->unsigned();
            $table->integer('{{ $able['role_foreign_key'] }}')->unsigned();

            $table->foreign('{{ $able['permission_foreign_key'] }}')->references('id')->on('{{ $able['permissions_table'] }}')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('{{ $able['role_foreign_key'] }}')->references('id')->on('{{ $able['roles_table'] }}')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['{{ $able['permission_foreign_key'] }}', '{{ $able['role_foreign_key'] }}']);
        });

        // Create table for associating roles to groups (Many-to-Many)
        Schema::create('{{ $able['role_group_table'] }}', function (Blueprint $table) {
            $table->integer('{{ $able['role_foreign_key'] }}')->unsigned();
            $table->integer('{{ $able['group_foreign_key'] }}')->unsigned();

            $table->foreign('{{ $able['role_foreign_key'] }}')->references('id')->on('{{ $able['roles_table'] }}')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('{{ $able['group_foreign_key'] }}')->references('id')->on('{{ $able['groups_table'] }}')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['{{ $able['role_foreign_key'] }}', '{{ $able['group_foreign_key'] }}']);
        });

        // Create table for associating groups to users (Many-to-Many)
        Schema::create('{{ $able['group_user_table'] }}', function (Blueprint $table) {
            $table->integer('{{ $able['group_foreign_key'] }}')->unsigned();
            $table->integer('{{ $able['user_foreign_key'] }}')->unsigned();

            $table->foreign('{{ $able['group_foreign_key'] }}')->references('id')->on('{{ $able['groups_table'] }}')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('{{ $able['user_foreign_key'] }}')->references('{{ $user->getKeyName() }}')->on('{{ $user->getTable() }}')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('{{ $able['company_foreign_key'] }}')->references('id')->on('{{ $able['companies_table'] }}')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['{{ $able['group_foreign_key'] }}', '{{ $able['user_foreign_key'] }}']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('{{ $able['permission_role_table'] }}');
        Schema::drop('{{ $able['permissions_table'] }}');
        Schema::drop('{{ $able['role_group_table'] }}');
        Schema::drop('{{ $able['group_user_table'] }}');
        Schema::drop('{{ $able['roles_table'] }}');
        Schema::drop('{{ $able['groups_table'] }}');
    }
}
