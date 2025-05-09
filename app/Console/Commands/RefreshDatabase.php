<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh database and seed with default data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Refreshing database...');
        
        // Backup data penting jika diperlukan di sini
        
        // Refresh migrasi
        $this->call('migrate:refresh');
        
        // Jalankan seeder
        $this->call('db:seed');
        
        $this->info('Database has been refreshed and seeded!');
        $this->info('Default admin account:');
        $this->info('Email: admin@admin.com');
        $this->info('Password: admin123');
    }
}
