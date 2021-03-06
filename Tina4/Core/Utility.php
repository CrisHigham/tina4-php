<?php

namespace Tina4;
/**
 * Tina4 - This is not a 4ramework.
 * Copy-right 2007 - current Tina4 (Andre van Zuydam)
 * License: MIT https://opensource.org/licenses/MIT
 *
 * Trait Utility
 * A bunch of useful methods used across different classes in the library
 * @package Tina4
 */
trait Utility
{
    /**
     * Recursively includes directories
     * @param $dirName
     */
    public function includeDirectory($dirName): void
    {
        $d = dir($dirName);
        while (($file = $d->read()) !== false) {
            $pathInfo = pathinfo($file);

            if (isset ($pathInfo["extension"]) && strtolower($pathInfo["extension"]) === "php") {
                $fileNameRoute = realpath($dirName) . DIRECTORY_SEPARATOR . $file;

                require_once $fileNameRoute;
            } else {
                $fileNameRoute = realpath($dirName) . DIRECTORY_SEPARATOR . $file;

                if (is_dir($fileNameRoute) && ($file !== ".") && ($file !== "..")) {
                    $this->includeDirectory($fileNameRoute);
                }
            }
        }
        $d->close();
    }

    /**
     * Makes sure the field is a date field and formats the data accordingly
     * @param $dateString
     * @param $databaseFormat
     * @return bool
     */
    public function isDate($dateString, $databaseFormat)
    {
        if (is_array($dateString) || is_object($dateString)) {
            return false;
        }
        if (substr($dateString, -1, 1) == "Z")
        {
            $dateParts = explode("T", $dateString);
        } else {
            $dateParts = explode(" ", $dateString);
        }
        $d = \DateTime::createFromFormat($databaseFormat, $dateParts[0]);
        return $d && $d->format($databaseFormat) === $dateParts[0];
    }

    /**
     * Returns a formatted date
     * @param $dateString
     * @param $databaseFormat
     * @param $outputFormat
     * @return string
     */
    public function formatDate($dateString, $databaseFormat, $outputFormat)
    {
        //Hacky fix for weird dates?
        $dateString = str_replace(".000000", "", $dateString);

        if (!empty($dateString)) {
            if (substr($dateString, -1, 1) == "Z") {
                $delimiter = "T";
                $dateParts = explode($delimiter, $dateString);
                $d = \DateTime::createFromFormat($databaseFormat, $dateParts[0]);
                if ($d) {
                    return $d->format($outputFormat) . $delimiter . $dateParts[1];
                } else {
                    return null;
                }
            } else {
                if (strpos($dateString, ":") !== false) {
                    $databaseFormat .= " H:i:s";
                    if (strpos($outputFormat, "T")) {
                        $outputFormat .= "H:i:s";
                    } else {
                        $outputFormat .= " H:i:s";
                    }
                }
                $d = \DateTime::createFromFormat($databaseFormat, $dateString);
                if ($d) {
                    return $d->format($outputFormat);
                } else {
                    return null;
                }
            }
        } else {
            return null;
        }
    }

    /**
     * This tests a string result from the DB to see if it is binary or not so it gets base64 encoded on the result
     * @param $string
     * @return bool
     */
    public function isBinary($string)
    {
        //immediately return back binary if we can get an image size
        if (is_numeric($string) || empty($string) || $string === null || $string === "") return false;
        if (is_string($string) && strlen($string) > 50 && @is_array(@getimagesizefromstring($string))) return true;
        $isBinary = false;
        $string = str_ireplace("\t", "", $string);
        $string = str_ireplace("\n", "", $string);
        $string = str_ireplace("\r", "", $string);
        if (is_string($string) && ctype_print($string) === false && strspn($string, '01') === strlen($string)) {
            $isBinary = true;
        }
        return $isBinary;
    }

    /**
     * Return a camel cased version of the name
     * @param $name
     * @return string
     */
    public function camelCase($name)
    {
        $fieldName = "";
        $name = strtolower($name);
        for ($i = 0, $iMax = strlen($name); $i < $iMax; $i++) {
            if ($name[$i] === "_") {
                $i++;
                if ($i < strlen($name)) {
                    $fieldName .= strtoupper($name[$i]);
                }
            } else {
                $fieldName .= $name[$i];
            }
        }
        return $fieldName;
    }


    public function getDebugBackTrace()
    {
        global $arrRoutes;

        $routing = new \Tina4\Routing("", "", "", "", null, true);

        if (isset($_SERVER["REQUEST_URI"])) {
            $urlToParse = $_SERVER["REQUEST_URI"];
            if ($urlToParse !== "/") {
                $urlToParse .= "/";
                $urlToParse = str_replace("//", "/", $urlToParse);
            }
        } else {
            $urlToParse = "/";
        }

        $debug = debug_backtrace();
        foreach ($debug as $id => $debugInfo) {
            if (strpos($debugInfo["file"], "Tina4") === false) {
                Debug::handleError("Trace", "", $debugInfo["file"], $debugInfo["line"]);
            }
        }
        foreach ($arrRoutes as $routId => $route) {
            if ($routing->matchPath($urlToParse, $route["routePath"])) {
                Debug::handleError("Trace", "", $route["fileInfo"][0]["file"], $route["fileInfo"][0]["line"]);
                break;
            }
        }
    }

    /**
     * Deletes all folders and sub folders
     * Credit to https://paulund.co.uk/php-delete-directory-and-files-in-directory
     * @param $target
     */
    public function deleteFiles($target): void
    {
        if(is_dir($target)){
            $files = glob( $target . '/{,.}*[!.]',GLOB_MARK|GLOB_BRACE); //GLOB_MARK adds a slash to directories returned
            foreach( $files as $file ){
                $this->deleteFiles( $file );
            }

            rmdir( $target );
        } elseif(is_file($target)) {
            unlink( $target );
        }
    }

    /**
     * Gets all the files from a folder
     * @param $target
     * @param string $filter
     * @return array
     */
    public function getFiles($target, $filter="*"): array
    {
        $result = [];
        if(is_dir($target)){
            $files = glob( $target . '/{,.}*[!.]',GLOB_MARK|GLOB_BRACE); //GLOB_MARK adds a slash to directories returned
            foreach( $files as $file ){
                $result = array_merge($result, $this->getFiles( $file , $filter));
            }
        } elseif(is_file($target)) {
            if ($filter === "*")
            {
                $result[] = realpath($target);
            } else if ( strpos($target, $filter) !== false ){
                $result[] = realpath($target);
            }
        }
        return $result;
    }
}