<?php

namespace App\Http\Controllers;

use App\Http\Requests\SecureImportRequest;
use App\Http\Resources\Export\DatenResource;
use App\Models\Daten;
use App\Models\User;
use App\Services\{DataImportService, GzipService};
use Exception;
use Illuminate\Http\{JsonResponse, Response};
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as Status;

/**
 * Controller for secure data transfer operations.
 */
class SecureTransferController extends Controller
{
    /**
     *  Method for a basic check, probably for health or connectivity.
     *
     * @return JsonResponse
     */
    public function check(): JsonResponse
    {
        return response()->json(['message' => 'Erfolg.']);
    }

    /**
     * Method for importing data securely.
     *
     * @param SecureImportRequest $request
     * @param GzipService $gzipService
     * @return JsonResponse|Response
     */
    public function import(SecureImportRequest $request, GzipService $gzipService): Response|JsonResponse
    {
        // Retrieving the uploaded file from the request.
        $file = $request->file('file');

        // Attempt to decompress the file content using GZIP.
        try {
            $decodedData = $gzipService->decode($file->getContent());
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ein Fehler ist beim Dekomprimieren der Daten aufgetreten: '. $e->getMessage(),
            ], Status::HTTP_BAD_REQUEST);
        }

        // Decoding the decrypted data from JSON.
        $json = json_decode($decodedData, true);

        // Check for JSON decoding errors.
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'message' => 'Ein Fehler ist beim JSON-Dekodierung aufgetreten: '. json_last_error_msg(),
            ], Status::HTTP_BAD_REQUEST);
        }

        // Validating the 'enmRevision' from the decoded JSON.
        if ($json['enmRevision'] != config('wenom.revision')) {
  //          return response()->json(['message' => 'Die Revisionsnummern der Synchronisation stimmt nicht mit der des SVWS-Servers überein. Die Sychronisation wird abgebrochen.'], Status::HTTP_UPGRADE_REQUIRED);
        }

        // Validating the 'schulnummer' from the decoded JSON.
        if ($json['schulnummer'] != config('wenom.schulnummer')) {
            return response()->json(['message' => 'Schulnummer nicht gültig'], Status::HTTP_BAD_REQUEST);
        }

        // Executing the import service with the validated data and returning a response.
        return (new DataImportService($json))->execute()->response();
    }

    /**
     * Method for exporting data securely.
     *
     * @param GzipService $gzipService
     * @return Response
     */
    public function export(GzipService $gzipService): Response
    {
        try {
            $data = json_encode(new DatenResource(Daten::firstOrCreate()));
        } catch (Exception $e) {
            return response([
                'message' => "Ein Fehler ist beim Json Enkodierung der Daten aufgetreten: {$e->getMessage()}",
            ], Status::HTTP_INTERNAL_SERVER_ERROR);
        }

        // As for now there is no counterpart on the server #347
        try {
            return response($gzipService->encode($data));
        } catch (Exception $e) {
            return response([
                'message' => "Ein Fehler ist beim Komprimieren der Daten aufgetreten: {$e->getMessage()}",
            ], Status::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Truncate the database.
     * Deletes all imported users except the system users (missing ext_id)
     * Does not truncate oauth_clients in order to keep the Oauth2 connection.
     *
     * @return JsonResponse
     */
    public function truncate(): JsonResponse
    {
        // List of tables not to be truncated
        $excludedTables = [
            'migrations', 'users', 'oauth_clients', 'settings', 'oauth_access_tokens',
        ];

        // Disable foreign key checks to avoid constraint violations
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Get a list of all tables in the database
        $tables = DB::select('SHOW TABLES');
        $tables = array_map('current', $tables); // Convert objects to an array of table names

        // Preparing tables from the schema to be truncated, skipping excluded tables
        $tablesToTruncate = Arr::where($tables, fn (string $tableName): bool => !in_array($tableName, $excludedTables));

        // Truncate the tables
        collect($tablesToTruncate)->each(fn (string $tableName): null => DB::table($tableName)->truncate());

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Remove all imported users
        $usersToDelete = User::lehrer();
        $deletedUserCount = $usersToDelete->count();
        $usersToDelete->each(fn (User $user): bool => $user->delete());

        // Return the response in JSON format
        return response()->json([
            'message' => [
                'tables' => [
                    'kept' => count($excludedTables),
                    'kept_tables' => $excludedTables,
                    'truncated' => count($tablesToTruncate),
                ],
                'users' => [
                    'kept' => User::count(),
                    'deleted' => $deletedUserCount,
                ],
            ],
        ]);
    }
}
