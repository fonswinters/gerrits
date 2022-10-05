<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class KommaHelpers
{

    /**
     * Generate the modifies string for the given className
     *
     * @param  string  $className
     * @param $modifiers
     */
    static function modifiers(string $className, $modifiers)
    {
        if (!empty($modifiers)) {

            // If $modifiers is a string instead of a array, put it a array
            if (!is_array($modifiers) && is_string($modifiers)) {
                $modifiers = [$modifiers];
            }

            $modifierClasses = '';
            foreach ($modifiers as $modifier) {
                $modifierClasses .= ' '.$className.'--'.$modifier;
            }
            echo $modifierClasses;
        }
    }

    static function komma_ip()
    {
        if ($_SERVER['REMOTE_ADDR'] == '5.172.219.238') return true;
        return false;
    }

    /**
     * Make the place-holder replacements on a line.
     * This is a duplicate from the translator class
     * Illuminate\Translation\Translator
     *
     *
     * @param  string $line
     * @param  array $replace
     * @return string
     */
    static function makeReplacements($line, array $replace)
    {
        $replace = KommaHelpers::sortReplacements($replace);

        foreach ($replace as $key => $value) {
            $line = str_replace(':' . $key, $value, $line);
        }

        return $line;
    }

    /**
     * Sort the replacements array.
     * This is a duplicate from the translator class
     * Illuminate\Translation\Translator
     *
     * @param  array $replace
     * @return array
     */
    static function sortReplacements(array $replace)
    {
        return (new Collection($replace))->sortBy(function ($value, $key) {
            return mb_strlen($key) * -1;
        });
    }

    /**
     * @param $string
     * @param int $length
     * @return null|string|string[]
     */
    static function str_limit_full_word($string, $length = 75){
        $length = abs((int)$length);
        if(strlen($string) > $length) {
            $string = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1...', $string);
        }
        return($string);
    }

    /**
     * Returns the maximum upload size in bytes
     *
     * @return int
     */
    public static function fileUploadMaxSize() {
        static $max_size = -1;

        if ($max_size < 0) {
            // Start with post_max_size.
            $post_max_size = self::parseSize(ini_get('post_max_size'));
            if ($post_max_size > 0) {
                $max_size = $post_max_size;
            }

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = self::parseSize(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }
        return $max_size;
    }

    /**
     * Returns the max post size in bytes
     */
    public static function maxPostSize()
    {
        $iniPostSize = strtolower(ini_get('post_max_size'));

        if(strpos($iniPostSize, 'm') !== FALSE) {
            $maxPostSizeInBytes = (int)(str_replace('M', '', $iniPostSize)) * 1024 * 1024;
        } else if(strpos($iniPostSize, 'g') !== FALSE) {
            $maxPostSizeInBytes = (int)(str_replace('g', '', $iniPostSize)) * 1024 * 1024 * 1024;
        } else {
            $maxPostSizeInBytes = (int) $iniPostSize;
        }
        return $maxPostSizeInBytes;
    }

    /**
     * Returns a php ini file size as bytes. taking into account that it may have unit characters in the size.
     *
     * @param $size
     * @return float
     */
    public static function parseSize($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        else {
            return round($size);
        }
    }

    /**
     * Gets a class name (with or without namespace) and returns the class name only without the namespace
     *
     * @param object|string $classInstanceOrReference
     * @param bool $snakecase If true then the shortModelClassName will be formatted to snake_case.
     * @param bool $removeModelString Wether or not to remove the string 'model' if it is in the class name
     * @return string
     */
    public static function getShortNameFromClass($classInstanceOrReference, bool $snakecase = false, bool $removeModelString = false): string
    {
        if(!is_object($classInstanceOrReference) && !is_string($classInstanceOrReference)) throw new \InvalidArgumentException("Argument 1 passed to this method must be an class instance. But was a: ".gettype($classInstanceOrReference));
        $FQCNSplit = (is_object($classInstanceOrReference)) ? explode('\\', get_class($classInstanceOrReference)) : explode('\\', $classInstanceOrReference);
        if($FQCNSplit == $classInstanceOrReference) return $classInstanceOrReference;
        $shortModelClassName = $FQCNSplit[count($FQCNSplit)-1];

        if($snakecase) return snake_case($shortModelClassName);

        return $shortModelClassName;
    }

    /**
     * Logs how long the code ran since the start of the request
     * @param string $note An extra comment you can add to the log.
     */
    public static function howLongSinceStart($note = null)
    {
        $backtrace = debug_backtrace();
        $backtrace = $backtrace[2];
        $functionInClassString = $backtrace['function'].'()';
        if (isset($backtrace['object'])) {
            $functionInClassString .= ' '.get_class($backtrace['object']).':';
        }

        $timestamp = constant('LARAVEL_START');

        $start = Carbon::createFromTimestamp($timestamp);
        $now = Carbon::now();
        $seconds = $now->diffInSeconds($start);
        \Log::debug('It took '.$seconds.' second(s) since start to reach '.$functionInClassString.'. '.($note ? 'Note: '.$note : ''));
    }

    /**
     *Parses the output of debug_backtrace to a string for the console
     * @param array $debug_backtrace
     * @return string
     */
    public static function parseBacktraceToConsoleString(array $debug_backtrace, $stackDepth = 10):string
    {
        $resultString = PHP_EOL;

        foreach($debug_backtrace as $itemNumber => $debug_backtrace_line) {
            $maxFilePathLength = 32;

            if(isset($debug_backtrace_line['file'])) {
                $filePath = strrev(substr(strrev($debug_backtrace_line['file']), 0, $maxFilePathLength));
                if (strlen($debug_backtrace_line['file']) > $maxFilePathLength) {
                    $filePath = '...' . $filePath;
                }
            } else {
                $filePath = 'n/a';
            }

            $line = isset($debug_backtrace_line['line']) ? $debug_backtrace_line['line'] : 'n/a';

            $shortClassName = isset($debug_backtrace_line['class']) ? self::getShortNameFromClass($debug_backtrace_line['class']) : '';

            $type = isset($debug_backtrace_line['type']) ? $debug_backtrace_line['type'] : '';

            $arguments = [];
            foreach ($debug_backtrace_line['args'] as $argument) {
                if(is_scalar($argument)) {
                    $arguments[] = (string) $argument;
                } elseif(is_array($argument)) {
                    $arguments[] = '[.....]';
                } else if (is_object($argument)) {
                    $arguments[] = get_class($argument);
                }
            }
            $arguments = implode(', ', $arguments);

            //                          MyClass           ->                          Myfunction                    (  arg1, arg2, ...) on line 312                              of file
            $resultString .= PHP_EOL.str_pad($filePath.':'.$line, $maxFilePathLength + 7).' '.$shortClassName.$type.$debug_backtrace_line['function'].'('.$arguments.')';
            if($itemNumber >= $stackDepth - 1) break;
        }

        return $resultString;
    }
}