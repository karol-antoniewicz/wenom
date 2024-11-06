<?php

namespace App\Services;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * The GzipService class provides functionality for encoding and decoding data using Gzip compression.
 */
class GzipService
{
    /**
     * Encodes the given data using Gzip compression.
     *
     * This method compresses the provided string using Gzip with a compression level of 9 (maximum compression).
     *
     * @param string $data
     * @return string
     * @throws Exception
     */
	public function encode(string $data): string
	{
        // Performs Gzip encoding
        $encodedData = @gzencode($data, 9);

        // Check if encoding was successful, otherwise throw exception
        if (false === $encodedData) {
            throw(new Exception(error_get_last()));
        }

        // Returns encoded data
        return $encodedData;
	}

    /**
     * Decodes the given Gzip compressed data.
     *
     * This method decompresses the provided Gzip compressed string.
     *
     * @param string $data T
     * @return string
     * @throws Exception
     */
	public function decode(string $data): string
	{
        // Performs Gzip decoding
        $decodedData = @gzdecode($data);

        // Check if decoding was successful, otherwise throw exception
        if (false === $decodedData) {
            throw(new Exception(error_get_last()));
        }

        // Returns decoded string
        return $decodedData;
	}
}
