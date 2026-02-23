<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use ZipArchive;

class BackupService
{
    /**
     * Create a database-only backup (SQL dump).
     * 
     * @return array Result with status and message
     */
    public function createBackup()
    {
        // Increase time limit for large backups
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $tempPath = null;
        $zipPath = null;

        try {
            // Ensure backups directory exists
            if (!Storage::exists('backups')) {
                Storage::makeDirectory('backups');
            }

            $dbName = DB::connection()->getDatabaseName();
            $filename = 'backup-data-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';
            
            // Use system temp directory for generating the backup file
            // This avoids permissions/path issues within the storage directory
            $tempPath = tempnam(sys_get_temp_dir(), 'backup_db_');
            
            // Open temp file for writing
            $handle = fopen($tempPath, 'w+');
            if (!$handle) {
                throw new \Exception("Could not create temporary backup file at: " . $tempPath);
            }

            // Header information
            fwrite($handle, "-- DATABASE DATA BACKUP\n");
            fwrite($handle, "-- Database: " . $dbName . "\n");
            fwrite($handle, "-- Generated: " . Carbon::now() . "\n");
            fwrite($handle, "-- This file contains ONLY database structure and data, NO application code.\n\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            // Get all tables
            $tables = DB::select('SHOW TABLES');

            foreach ($tables as $table) {
                $tableArray = (array)$table;
                $tableName = reset($tableArray);
                
                // Get create table statement
                $createTable = DB::select("SHOW CREATE TABLE `$tableName`");
                $createTableSql = $createTable[0]->{'Create Table'};
                
                fwrite($handle, "-- Table structure for table `$tableName`\n");
                fwrite($handle, "DROP TABLE IF EXISTS `$tableName`;\n");
                fwrite($handle, $createTableSql . ";\n\n");
                
                // Dump data in chunks to save memory
                fwrite($handle, "-- Dumping data for table `$tableName`\n");
                
                DB::table($tableName)->orderByRaw('1')->chunk(100, function ($rows) use ($handle, $tableName) {
                    foreach ($rows as $row) {
                        $row = (array) $row;
                        $fields = array_map(function ($value) {
                            if (is_null($value)) return 'NULL';
                            // Escape single quotes and backslashes
                            $value = str_replace(["\\", "'"], ["\\\\", "\\'"], $value);
                            return "'" . $value . "'";
                        }, $row);
                        
                        fwrite($handle, "INSERT INTO `$tableName` VALUES (" . implode(', ', $fields) . ");\n");
                    }
                });
                
                fwrite($handle, "\n");
            }
            
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            fclose($handle);

            // Try to zip if ZipArchive is available
            if (class_exists('ZipArchive')) {
                $zipFilename = 'backup-data-' . Carbon::now()->format('Y-m-d-H-i-s') . '.zip';
                $zipPath = tempnam(sys_get_temp_dir(), 'backup_zip_');
                
                $zip = new ZipArchive();
                if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                    // Add the SQL file to the zip with just the filename (flat structure)
                    if (!$zip->addFile($tempPath, $filename)) {
                        $zip->close();
                        throw new \Exception("Failed to add SQL file to ZIP archive.");
                    }
                    
                    if (!$zip->close()) {
                        throw new \Exception("Failed to close and save ZIP archive.");
                    }
                    
                    // Store the ZIP file
                    Storage::putFileAs('backups', new \Illuminate\Http\File($zipPath), $zipFilename);
                    
                    // Cleanup
                    if (file_exists($tempPath)) unlink($tempPath);
                    if (file_exists($zipPath)) unlink($zipPath);
                    
                    return ['success' => true, 'message' => 'Database backup created successfully (Zipped).', 'file' => $zipFilename];
                }
            }

            // Store the SQL file (if zip failed or not available)
            Storage::putFileAs('backups', new \Illuminate\Http\File($tempPath), $filename);
            
            // Cleanup
            if (file_exists($tempPath)) unlink($tempPath);

            return ['success' => true, 'message' => 'Database backup created successfully (SQL).', 'file' => $filename];

        } catch (\Exception $e) {
            // Clean up temp files if exist
            if ($tempPath && file_exists($tempPath)) @unlink($tempPath);
            if ($zipPath && file_exists($zipPath)) @unlink($zipPath);
            
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
