<?php

namespace App\Services;

use Exception;

/**
 * AesService class provides functionality for encrypting and decrypting data using AES (Advanced Encryption Standard)
 */
class AesService
{
    /**
     * The cipher algorithm used for encryption.
     *
     * @var string
     */
    private string $encryptCipher = 'aes-256-cbc';

    /**
     * The cipher algorithm used for decryption.
     *
     * @var string
     */
    private string $decryptCipher = 'aes-256-cbc-hmac-sha256';

    /**
     * OpenSSL options for encryption and decryption.
     *
     * @var int
     */
    private int $options = OPENSSL_NO_PADDING;

    /**
     * Encrypts the given data using the configured encryption cipher.
     *
     * @param $data
     * @return string
     * @throws Exception
     */
	public function encrypt($data): string
	{
        // Check if selected encryption cipher is available, otherwise throw exception
        if (!in_array($this->encryptCipher, openssl_get_cipher_methods())) {
            throw (new Exception("{$this->encryptCipher} wird von Ihrer OpenSSL-Version unterstützt."));
        }

        // Get initialization vector
		$initializationVector = $this->generateInitializationVector();

        // Perform OpenSSL encryption
		$encryptedData = openssl_encrypt(
			$this->pad($data),
			$this->encryptCipher,
			$this->getPassphrase(),
			$this->options,
			$initializationVector,
		);

        // Check if encryption was successful, otherwise throw exception
		if (false === $encryptedData) {
			throw (new Exception(error_get_last()));
		}

        // Return encrypted data
		return $this->prependInitializationVector(base64_encode($encryptedData), $initializationVector);
	}

    /**
     * Decrypts the given data using the configured decryption cipher.
     *
     * @param string $data
     * @return string
     * @throws Exception
     */
	public function decrypt(string $data): string
	{
        // Check if selected encryption cipher is available, otherwise throw exception
        if (!in_array($this->decryptCipher, openssl_get_cipher_methods())) {
            throw (new Exception("{$this->encryptCipher} wird von Ihrer OpenSSL-Version unterstützt."));
        }

        // Base64 decoded data
		$decodedData = base64_decode($data, true);

        // Check if decoding was successful, otherwise throw exception
		if (false === $decodedData) {
			throw (new Exception(error_get_last()));
		}

        // Perform OpenSSL decryption
		$decryptedData = openssl_decrypt(
            $this->dataWithoutInitializationVector($decodedData),
            $this->decryptCipher,
            $this->getPassphrase(),
            $this->options,
            $this->extractInitializationVector($decodedData)
		);

        // Check if decryption was successful, otherwise throw exception
		if (false === $decryptedData) {
			throw (new Exception('Ein Fehler ist beim AES-CBC Entschlüsseln aufgetreten: '. error_get_last()));
		}

        // Return decrypted data
		return $decryptedData;
	}

    /**
     * Generates the passphrase used for encryption and decryption.
     *
     * @return string
     */
    private function getPassphrase(): string
	{
		return hash_pbkdf2(
            'sha256',
            config('wenom.aes_password'),
            config('wenom.aes_salt'),
            65536,
            32,
            true,
        );
	}

    /**
     * Generates a random Initialization Vector (IV) for the encryption.
     *
     * @return string
     */
    private function generateInitializationVector(): string
	{
		return openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->encryptCipher));
	}

    /**
     * Extracts the Initialization Vector (IV) from the given string.
     *
     * @param string $string
     * @return string
     */
    private function extractInitializationVector(string $string): string
	{
		return substr($string, 0, 16);
	}

    /**
     * Prepends the Initialization Vector (IV) to the given string.
     *
     * @param string $string
     * @param string $initializationVector
     * @return string
     */
    private function prependInitializationVector(string $string, string $initializationVector): string
	{
		return substr_replace($string, $initializationVector, 0, 0);
	}

    /**
     * Removes the Initialization Vector (IV) from the given data string.
     *
     * @param string $data
     * @return string
     */
    private function dataWithoutInitializationVector(string $data): string
	{
		return substr($data, 16);
	}

    /**
     * Pads the given string to a multiple of the block size.
     *
     * @param string $plainText
     * @return string
     */
    private function pad(string $plainText): string
	{
		if (strlen($plainText) % 16 == 0) {
			return $plainText;
		}

		return str_pad($plainText, strlen($plainText) + 16 - strlen($plainText) % 16, "\0");
	}
}
