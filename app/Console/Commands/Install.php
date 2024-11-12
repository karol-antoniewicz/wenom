<?php

namespace App\Console\Commands;

use App\Services\EnvService;
use Dotenv\Dotenv;
use Exception;
use Illuminate\Config\Repository;
use Illuminate\Console\Command;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\{Artisan, Config, DB, Validator, Mail};
use Illuminate\Validation\Rule;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lorem ipsum dolor sit amet';

    /**
     * Class constructor
     *
     * @param EnvService $service
     */
    public function __construct(private EnvService $service)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->reloadConfig();

        $this->emailQuestion();
        $this->dbQuestion();

        $this->singleQuestion(
            fn (): string => $this->ask('APP URL', config('app.url')),
            'App Url',
            'APP_URL',
            ['required', 'url' , 'regex:/^https:\/\/.+$/'],
        );

        $this->singleQuestion(
            fn (): string => $this->ask('Schulnummer', config('wenom.schulnummer')),
            'Schulnummer',
            'SCHULNUMMER',
            ['required', 'numeric'],
        );

        $this->singleQuestion(
            fn (): string => $this->ask('AES Password', config('wenom.aes_password')),
            'AES Password',
            'AES_PASSWORD',
            ['required', 'string'],
        );

        $this->singleQuestion(
            fn (): string => $this->ask('AES Salt', config('wenom.aes_salt')),
            'AES Salt',
            'AES_SALT',
            ['required', 'string'],
        );

        return Command::SUCCESS;
    }

    /** Get db credentials and test them. */
    private function dbQuestion(): void
    {
        while (true) {
            $this->singleQuestion(
                fn (): string|null => $this->ask('DB Host', config('database.connections.mysql.host')),
                'DB Host',
                'DB_HOST',
                ['required', 'string'],
            );

            $this->singleQuestion(
                fn (): int|null => $this->ask('DB PORT', config('database.connections.mysql.port')),
                'DB Port',
                'DB_PORT',
                ['required', 'numeric'],
            );

            $this->singleQuestion(
                fn (): string|null => $this->ask('DB Database', config('database.connections.mysql.database')),
                'DB Database',
                'DB_DATABASE',
                ['required', 'string'],
            );

            $this->singleQuestion(
                fn (): string|null => $this->ask('DB Username', config('database.connections.mysql.username')),
                'DB Username',
                'DB_USERNAME',
                ['required', 'string'],
            );

            $this->singleQuestion(
                fn (): string|null => $this->ask('DB Password', config('database.connections.mysql.password')),
                'DB Password',
                'DB_PASSWORD',
                ['nullable', 'string'],
            );

            $this->reloadConfig();

            try {
                DB::connection()->getPdo();
                $this->info('Datenbank verbindung erfolgreich.');
                break;
            } catch (Exception $e) {
                $this->error("Verbindung fehlgeschlagen. Ueberpruefen Sie die Konfiguration. {$e->getMessage()}");
            }
        }
    }

    /** Get email credentials and test them. */
    private function emailQuestion(): void
    {
        $encryptionOptions = [
            'tls', 'ssl', '',
        ];

        while (true) {
            $this->singleQuestion(
                fn (): string => $this->ask('Mail Host', config('wenom.mail_send_credentials.host')),
                'Mail Host',
                'MAIL_HOST',
                ['required', 'string'],
            );

            $this->singleQuestion(
                fn (): int => $this->ask('Mail Port', config('wenom.mail_send_credentials.port')),
                'Mail Port',
                'MAIL_PORT',
                ['required', 'numeric'],
            );

            $this->singleQuestion(
                fn (): string => $this->ask('Mail Username', config('wenom.mail_send_credentials.username')),
                'Mail Username',
                'MAIL_USERNAME',
                ['required', 'string'],
            );

            $this->singleQuestion(
                fn (): string => $this->ask('Mail Passwort', config('wenom.mail_send_credentials.password')),
                'Mail Passwort',
                'MAIL_PASSWORD',
                ['required', 'string'],
            );

            $this->singleQuestion(
                fn (): string => $this->choice(
                    'Mail Encryption',
                    $encryptionOptions,
                    config('wenom.mail_send_credentials.encryption'),
                ),
                'Mail Encryption',
                'MAIL_ENCRYPTION',
                ['required', 'string', Rule::in($encryptionOptions)],
            );

            $this->singleQuestion(
                fn (): string => $this->ask('Mail From Adresse', config('wenom.mail_send_credentials.from_address')),
                'Mail From Adresse',
                'MAIL_FROM_ADDRESS',
                ['required', 'email:dns,rfc'],
            );

            $this->singleQuestion(
                fn (): string => $this->ask('Mail From Name', config('wenom.mail_send_credentials.from_name')),
                'Mail From NAME',
                'MAIL_FROM_NAME',
                ['required', 'string'],
            );

            $this->reloadConfig();

            try {
                $text = 'Webnotenmanager Setup Benachrichtigung';
                $email = config('wenom.mail_send_credentials.from_address');

                Mail::raw($text, fn (Message $message): Message => $message->to($email)->subject($text));

                $this->info('Mail credentials are valid. Connection to SMTP server succeeded.');
                break;
            } catch (Exception $e) {
                $this->error("Verbindung fehlgeschlagen. Ueberpruefen Sie die Konfiguration. {$e->getMessage()}");
            }
        }
    }

    /**
     * Single question validator
     *
     * @param callable $question
     * @param string $title
     * @param string $envField
     * @param array<string, string> $rules
     */
    private function singleQuestion(callable $question, string $title, string $envField, array $rules): void
    {
        while (true) {
            $ask = $question();
            $key = strtolower($envField);

            $validator = Validator::make([$key => $ask], [$key => $rules], [], [$key => $title]);

            if ($validator->passes()) {
                $this->info("{$title} gültig");
                $this->service->update($envField, $ask);
                break;
            }

            if ($validator->fails()) {
                $this->error("{$title} ungültig");

                foreach ($validator->errors()->all() as $error) {
                    $this->error("- {$error}");
                }
            }
        }
    }

    /** Reloads config */
    private function reloadConfig(): void
    {
        Artisan::call('config:clear');
        Artisan::call('config:cache');

        $app = app();
        $envFile = $app->environmentFile();
        $filePath = $app->environmentPath() . DIRECTORY_SEPARATOR . $envFile;

        if (file_exists($filePath)) {
            $dotenv = Dotenv::createImmutable($app->environmentPath(), $envFile);
            $dotenv->load();
        }

        app()->instance('config', new Repository(require base_path('bootstrap/cache/config.php')));

        Config::set('app.debug', env('APP_DEBUG', false)); // Example
    }
}
