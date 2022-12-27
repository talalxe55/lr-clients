<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Services;
use App\Models\Status;
use App\Models\ClientType;
use App\Models\ClientServices;
use App\Models\Clients;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;


class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'view clients']);
        Permission::create(['name' => 'add client']);
        Permission::create(['name' => 'edit client']);
        Permission::create(['name' => 'change client live date']);
        Permission::create(['name' => 'change client cancel date']);
        Permission::create(['name' => 'update client']);
        Permission::create(['name' => 'delete client']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'add user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'delete user']);
        Permission::create(['name' => 'view settings']);
        Permission::create(['name' => 'add setting']);
        Permission::create(['name' => 'edit setting']);
        Permission::create(['name' => 'update setting']);
        Permission::create(['name' => 'delete setting']);
        Permission::create(['name' => 'add client service']);
        Permission::create(['name' => 'view client service']);
        Permission::create(['name' => 'edit client service']);
        Permission::create(['name' => 'update client service']);
        Permission::create(['name' => 'delete client service']);
        Permission::create(['name' => 'view invoices']);
        Permission::create(['name' => 'add invoice']);
        Permission::create(['name' => 'edit invoice']);
        Permission::create(['name' => 'delete invoice']);
        Permission::create(['name' => 'update invoice']);
        Permission::create(['name' => 'view client billing']);
        Permission::create(['name' => 'generate client billing']);
        Permission::create(['name' => 'view logs']);
        Permission::create(['name' => 'view services']);
        Permission::create(['name' => 'add service']);
        Permission::create(['name' => 'edit service']);
        Permission::create(['name' => 'delete service']);

        // create roles and assign created permissions
        $userPermissions = ['view clients',
                            'add client',
                            'edit client',
                            'update client',
                            'view client service',
                            'delete client service',
                            'add client service',
                            'view client billing'
                        ];
        $adminPermissions = ['view clients',
                            'add client',
                            'edit client',
                            'change client live date',
                            'change client cancel date',
                            'update client',
                            'delete client',
                            'view users',
                            'add user',
                            'edit user',
                            'update user',
                            'delete user',
                            'view settings',
                            'add setting',
                            'edit setting',
                            'delete setting',
                            'update setting',
                            'view client service',
                            'edit client service',
                            'update client service',
                            'delete client service',
                            'add client service',
                            'view invoices',
                            'add invoice',
                            'edit invoice',
                            'update invoice',
                            'delete invoice',
                            'view client billing',
                            'generate client billing',
                            'view logs',
                            'view services',
                            'add service',
                            'delete service',
                            'edit service'
                        ];
        // this can be done as separate statements
        $role = Role::create(['name' => 'user'])->givePermissionTo($userPermissions);
        $role = Role::create(['name' => 'admin'])->givePermissionTo($adminPermissions);
        $role = Role::create(['name' => 'superadmin'])->givePermissionTo($adminPermissions);
        $user = User::factory()->create([
            'name' => 'Talal',
            'email' => 'symtalal@gmail.com',
            'password' => ('abcd1234')
        ])->assignRole('superadmin');

        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'test@gmail.com',
            'password' => ('abcd1234')
        ])->assignRole('admin');

        $user = User::factory()->create([
            'name' => 'Normal User',
            'email' => 'test123@gmail.com',
            'password' => ('abcd1234')
        ])->assignRole('user');

        $active = Status::create(['name' => 'Active']);
        $cancelled = Status::create(['name' => 'Cancelled']);

        $ecom = ClientType::create(['name' => 'E-Commerce']);
        $non_ecom = ClientType::create(['name' => 'Non-Ecommerce']);

        $service_website = Services::create(['name' => 'Website', 'amount' => 100, 'amount_type' => 'percentage']);
        $service_chat = Services::create(['name' => 'Chat', 'amount' => null, 'amount_type' => null]);
        $service_hosting = Services::create(['name' => 'Hosting', 'amount' => 50, 'amount_type' => 'fixed']);
        $service_live_answer = Services::create(['name' => 'Live Answering', 'amount' => null, 'amount_type' => null]);
        $service_blogs = Services::create(['name' => 'Blogs', 'amount' => 200, 'amount_type' => 'fixed' ]);
        
        $date = Carbon::now();
        $client_1 = Clients::create(['name' => 'Modern Exterior Systems', 'website' => 'modernexteriorsystems.com', 'status' => $active->id, 'type' => $ecom->id, 'live_at' => Carbon::now()->subDays(21), 'cancelled_at' => Carbon::now()->subDays(1)]);
        $client_2 = Clients::create(['name' => 'Cavtool', 'website' => 'cavtool.com', 'status' => $active->id, 'type' => $non_ecom->id, 'live_at' => Carbon::now()->subDays(20)]);
        $client_3 = Clients::create(['name' => 'XLT Home Pro', 'website' => 'xlthomepro.com', 'status' => $cancelled->id, 'type' => $non_ecom->id, 'live_at' => Carbon::now()->subDays(10), 'cancelled_at' => Carbon::now()]);
        $client_3 = Clients::create(['name' => 'Spirit Magnet', 'website' => 'spiritmagnet.com', 'status' => $active->id, 'type' => $ecom->id, 'live_at' => Carbon::now()->subDays(26), 'cancelled_at' => null]);

        $client1_service1 = ClientServices::create(['client_id' => $client_1->id , 'service_id' => $service_website->id, 'discount' => 10, 'quantity' => 1]);
        $client1_service2 = ClientServices::create(['client_id' => $client_1->id , 'service_id' => $service_chat->id, 'discount' => null, 'quantity' => 1]);
        $client1_service3 = ClientServices::create(['client_id' => $client_1->id , 'service_id' => $service_blogs->id, 'discount' => 10, 'quantity' => 2]);

        $client2_service1 = ClientServices::create(['client_id' => $client_2->id , 'service_id' => $service_chat->id, 'discount' => null, 'quantity' => 1]);
        $client2_service2 = ClientServices::create(['client_id' => $client_2->id , 'service_id' => $service_hosting->id, 'discount' => 10, 'quantity' => 1]);
        $client2_service3 = ClientServices::create(['client_id' => $client_2->id , 'service_id' => $service_live_answer->id, 'discount' => null, 'quantity' => 1]);

        $client3_service1 = ClientServices::create(['client_id' => $client_3->id , 'service_id' => $service_blogs->id, 'discount' => 10, 'quantity' => 2]);

        
    }
}
