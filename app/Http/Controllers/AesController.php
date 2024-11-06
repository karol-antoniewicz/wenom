<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class AesController extends Controller
{
    // TODO: To be removed after testing #239
    public function __invoke(): string
	{
		$encryptedData = File::get(path: '../database/seeders/data/enm.json.aes.base64');

		$decodedData = base64_decode(string: $encryptedData, strict: true);

		abort_unless(
			boolean: $decodedData,
			code: Response::HTTP_BAD_REQUEST,
			message: 'The input contains character from outside the base64 alphabet.',
		);

		$decryptedData = openssl_decrypt(
			data: substr(string: $decodedData, offset: 16),
			cipher_algo: 'aes-256-cbc-hmac-sha256',
			passphrase: $this->getKey(),
			options: OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING,
			iv: $this->getIv($decodedData)
		);

		abort_unless(
			boolean: $decryptedData,
			code: Response::HTTP_BAD_REQUEST,
			message: openssl_error_string(),
		);

		return $decryptedData;
	}

	private function getIv(string $string): string
	{
		return substr(string: $string, offset: 0, length: 16);
	}

	private function getKey(): string
	{
		return hash_pbkdf2(
			algo: 'sha256',
			password: config(key: 'wenom.aes_password'),
			salt: config(key: 'wenom.aes_salt'),
			iterations: 65536,
			length: 32,
			binary: true
		);
	}
}
