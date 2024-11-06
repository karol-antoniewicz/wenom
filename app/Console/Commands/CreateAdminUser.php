<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\{Hash, Validator};

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin-user {--user=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Erstelle einen Admin-Benutzer. Optionale Parameter: --email, --password';

    /**
     * Validation rules
     *
     * @var array
     */
    private array $validationRules = [
        'email' => [
            'required', 'email:rfc,dns', 'unique:users,email',
        ],
        'password' => [
            'required', 'string', 'min:8', 'confirmed',
        ],
        'password_confirmation' => [
            'required', 'string', 'min:8',
        ],
    ];

    /**
     * Custom attributes
     *
     * @var array<string, string>
     */
    private array $customAttributes = [
        'email' => 'E-Mail-Adresse',
        'password' => 'Passwort',
        'password_confirmation' => 'Password Bestätigung',
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        // Get input from console or arguments
        $email = $this->option('user') ?? $this->ask($this->customAttributes['email']);
        $password = $this->option('password') ?? $this->secret($this->customAttributes['password']);
        $passwordConfirmation = $this->option('password')
            ?? $this->secret($this->customAttributes['password_confirmation']);

        $data = [
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ];

        // Validate input
        $validator = Validator::make($data, $this->validationRules, [], $this->customAttributes);

        // Show error messages if validator fails
        if ($validator->fails()) {
            $this->error('Technischer Admin koennte nicht erstellt werden:');

            foreach ($validator->errors()->all() as $error) {
                $this->error("- {$error}");
            }

            return Command::FAILURE;
        }

        // Create the user (without events prepared only for the importer)
        User::withoutEvents(fn (): User => User::factory()->administrator()->create([
            'vorname' => 'admin',
            'nachname' => 'admin',
            'kuerzel' => 'admin',
            'email' => $email,
            'password' => Hash::make($password),
        ]));

        // Display the success message
        $this->info("Technischer Admin wurde erfolgreich angelegt mit die E-Mail-Adresse: {$email}");

        return Command::SUCCESS;
    }
}
