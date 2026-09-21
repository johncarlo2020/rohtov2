<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ParticipantTableTest extends TestCase
{
    public function test_table_counts_search_and_export_only_include_clients(): void
    {
        config(['database.default' => 'participants_test', 'database.connections.participants_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
        ]]);
        try {
            (require database_path('migrations/2014_10_12_000000_create_users_table.php'))->up();
            (require database_path('migrations/2026_09_21_000000_add_is_card_apply_to_users_table.php'))->up();
            (require database_path('migrations/2024_07_16_061509_create_permission_tables.php'))->up();
            Schema::create('stations', function (Blueprint $table) { $table->id(); $table->string('name'); });
            Schema::create('station_users', function (Blueprint $table) {
                $table->id(); $table->unsignedBigInteger('user_id'); $table->unsignedBigInteger('station_id');
                $table->integer('time_spent')->default(0); $table->timestamps();
            });
            Role::findOrCreate('client', 'web');
            Role::findOrCreate('admin', 'web');
            foreach (['Client' => ['client'], 'Admin' => ['admin'], 'Both' => ['client', 'admin'], 'Neither' => []] as $name => $roles) {
                $user = User::create(['fname' => $name, 'lname' => '', 'email' => strtolower($name).'@example.com',
                    'number' => '', 'dob' => '', 'country' => '', 'password' => 'unused']);
                $user->syncRoles($roles);
            }
            $controller = new UserController;
            $params = ['draw' => 1, 'start' => 0, 'length' => 10, 'order' => [['column' => 0, 'dir' => 'asc']],
                'columns' => [['data' => 'id']], 'search' => ['value' => '']];
            $result = $controller->getUsersForDataTable(new Request($params))->getData(true);
            $this->assertSame(1, $result['iTotalRecords']);
            $this->assertCount(1, $result['aaData']);
            $this->assertSame('Client', $result['aaData'][0]['fname']);
            $this->assertArrayNotHasKey('sms_consent', $result['aaData'][0]);
            $params['search']['value'] = 'admin';
            $result = $controller->getUsersForDataTable(new Request($params))->getData(true);
            $this->assertCount(0, $result['aaData']);
            ob_start();
            $controller->export(new Request)->sendContent();
            $csv = ob_get_clean();
            $this->assertStringContainsString('client@example.com', $csv);
            $this->assertStringNotContainsString('admin@example.com', $csv);
            $this->assertStringNotContainsString('both@example.com', $csv);
            $this->assertStringNotContainsString('SMS', $csv);
            for ($i = 0; $i < 11; $i++) {
                User::create(['fname' => 'Client', 'lname' => '', 'email' => 'page'.$i.'@example.com',
                    'number' => '', 'dob' => '', 'country' => '', 'password' => 'unused'])->assignRole('client');
            }
            DB::table('stations')->insert(['id' => 1, 'name' => 'Shop for More']);
            DB::table('station_users')->insert(['user_id' => 1, 'station_id' => 1, 'time_spent' => 0, 'created_at' => now()]);
            $params['search']['value'] = '';
            $params['columns'][0]['data'] = 'name';
            $firstPage = $controller->getUsersForDataTable(new Request($params))->getData(true);
            $this->assertSame(12, $firstPage['recordsTotal']);
            $this->assertCount(10, $firstPage['data']);
            $this->assertTrue($firstPage['data'][0]['stations'][0]['completed']);
            $this->assertFalse($firstPage['data'][1]['stations'][0]['completed']);
            $params['start'] = 10;
            $nextPage = $controller->getUsersForDataTable(new Request($params))->getData(true);
            $this->assertCount(2, $nextPage['data']);
            $this->assertSame([], array_intersect(array_column($firstPage['data'], 'id'), array_column($nextPage['data'], 'id')));
        } finally {
            DB::purge('participants_test');
        }
    }
}
