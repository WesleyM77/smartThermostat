<?php

namespace Classes;

class Secrets {

    // The cached contents of the secrets file.
    private static Array $jsonContents;

    // Allows us to run unit tests with a base example file.
    public static bool $useExampleFile;

    public static function get(String $secret): ?String {
        if (empty(self::$jsonContents)) {
            $filename = 'secrets.json';
            if (self::$useExampleFile) {
                $filename = 'secrets.example.json';
            }
            // Depending on if you run this class's test manually or by running the entire folder of tests, the filepath
            // needs to change a tiny bit, so accommodate that.
            $filename = './' . $filename;
            $backupFilename = './.' . $filename;

            $json = file_get_contents($filename);
            if ($json === FALSE) {
                $json = file_get_contents($backupFilename);
            }
            Secrets::$jsonContents = json_decode($json, TRUE);
        }

        return self::$jsonContents[$secret] ?? NULL;
    }
}