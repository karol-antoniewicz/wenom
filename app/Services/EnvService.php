<?php

namespace App\Services;

use Illuminate\Contracts\Filesystem\FileNotFoundException;

/**
 * The EnvService class provides functionality for reading and updating the .env file.
 */
class EnvService
{
    /**
     * Performs a bulk update on the .env file with an array of key/value pairs.
     *
     * @param array $array
     * @return void
     * @throws FileNotFoundException
     */
    public function bulkUpdate(array $array): void
    {
        collect($array)->each(fn (string $value, string $key) => $this->update($key, $value));
    }

    /**
     * Updates the .env file with a single key/value pair.
     *
     * @param string $key
     * @param string|bool|null $value
     * @return void
     * @throws FileNotFoundException
     */
    public function update(string $key, string|bool|null $value): void
    {
        // Get .env path
        $path = base_path('.env');

        // Check if file exists, otherwise throw exception
        if (!file_exists($path)) {
            throw new FileNotFoundException('.env does not exist');
        }

        // Get .env file contents
        $currentContent = file_get_contents($path);

        // If key found updates the value, otherwise creates a new key/value pair
        $newContent = str_contains($currentContent, $key . '=')
            ? preg_replace('/' . $key . '=(.*)/', $key . '=' . $this->wrapValue($value), $currentContent)
            : $currentContent . PHP_EOL . $key . '=' . $this->wrapValue($value);

        // Writes the file
        file_put_contents($path, $newContent);
    }

    /**
     * Formats the value for the .env file.
     *
     * @param string|bool|null $value
     * @return string|null
     */
    private function wrapValue(string|bool|null $value): string|null
    {
        // If value is boolean casts to string
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        // If value is null casts keep type
        if (is_null($value)) {
            return null;
        }

        // Returns string wrapped in quotation marks
        return sprintf('"%s"', $value);
    }
}
