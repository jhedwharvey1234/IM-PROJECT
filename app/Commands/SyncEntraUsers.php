<?php

namespace App\Commands;

use App\Libraries\EntraDirectorySyncService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SyncEntraUsers extends BaseCommand
{
    protected $group = 'Entra';
    protected $name = 'entra:sync-users';
    protected $description = 'Sync all Azure/Entra directory users into local users table using app-only Graph access.';
    protected $usage = 'entra:sync-users';

    public function run(array $params)
    {
        try {
            $service = new EntraDirectorySyncService();
            $result = $service->syncAllUsers();

            CLI::write('Entra sync completed.', 'green');
            CLI::write('Fetched : ' . $result['fetched']);
            CLI::write('Created : ' . $result['created']);
            CLI::write('Updated : ' . $result['updated']);
            CLI::write('Skipped : ' . $result['skipped']);
            CLI::write('Failed  : ' . $result['failed']);

            if (!empty($result['errors']) && is_array($result['errors'])) {
                CLI::newLine();
                CLI::write('Errors (first 20):', 'yellow');
                foreach ($result['errors'] as $error) {
                    CLI::write('- ' . $error, 'red');
                }
            }

            return EXIT_SUCCESS;
        } catch (\Throwable $e) {
            CLI::error('Entra sync failed: ' . $e->getMessage());
            return EXIT_ERROR;
        }
    }
}
