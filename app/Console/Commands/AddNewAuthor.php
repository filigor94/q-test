<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\ClientService;
use Illuminate\Console\Command;

class AddNewAuthor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qclient:add-new-author {--api-email=} {--api-password=} {--first-name=} {--last-name=} {--birthday=} {--place-of-birth=} {--biography=} {--gender=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds a new author via Q API';

    protected ClientService $clientService;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->validateOptions();

        $this->clientService = app(ClientService::class);

        if (!auth()->attempt([
            'email' => $this->option('api-email'),
            'password' => $this->option('api-password')
        ])) {
            throw new \Exception('Invalid Credentials');
        }

        $response = $this->clientService->createAuthor(
            $this->option('first-name'),
            $this->option('last-name'),
            $this->option('birthday'),
            $this->option('place-of-birth'),
            $this->option('gender'),
            $this->option('biography'),
        );

        auth()->logout();

        if (!$response->ok()) {
            return 1;
        }

        $this->info('New author has been successfully created');

        return 0;
    }

    private function validateOptions(): void
    {
        $requiredOptions = collect([
            'api_email' => $this->option('api-email'),
            'api_password' => $this->option('api-password'),
            'first_name' => $this->option('first-name'),
            'last_name' => $this->option('last-name'),
            'birthday' => $this->option('birthday'),
            'gender' => $this->option('gender'),
            'place_of_birth' => $this->option('place-of-birth'),
        ])->filter(fn($option) => is_null($option));

        if ($requiredOptions->count()) {
            throw new \Exception(
                'The following options are required: '
                .implode(', ', $requiredOptions->keys()->toArray())
            );
        }

        if (!in_array($this->option('gender'), ['male', 'female'])) {
            throw new \Exception('Gender should be "male" or "female"');
        }
    }
}
