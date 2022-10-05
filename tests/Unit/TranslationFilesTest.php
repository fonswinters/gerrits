<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Class TranslationFiles
 *
 * Tests translation files for the other languages contains the same keys
 *
 * @package Tests\Unit
 */
class TranslationFilesTest extends TestCase
{

    private array $translationKeys = [];

    private array $languageFunctionPatterns = [
        "/__\('(.*?)'[),]/",
        "/trans\('(.*?)'[),]/",
        "/lang\('(.*?)'[),]/"
    ];

    private array $skippableStartKeys = [
        'KMS::',
        'form.',
        'calendar.',
        'validation.',
        'passwords.',
        'company.',
        'routes',
        'site/routes',
        'errors.',
        'form.',
    ];

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $localLanguageFolder = resource_path('lang/' . app()->getLocale());
        $this->assertFileExists($localLanguageFolder);

        $languageFiles = File::allFiles( resource_path('lang/' . app()->getLocale()));

        foreach ($languageFiles as $languageFile) {

            $fileName = $languageFile->getRelativePathname();
            $translationFileKey = Str::replaceLast('.' . $languageFile->getExtension(), '', $fileName);

            $this->getKeysFromArray($translationFileKey);
        }
    }

    /**
     * Recurring function for grabbing the translations out of an array till it's a string
     *
     * @param  string  $translationParentKey
     */
    private function getKeysFromArray(string $translationParentKey)
    {
        foreach (__($translationParentKey) as $translationKey => $translationValue) {
            if(is_array($translationValue)) {
                $this->getKeysFromArray($translationParentKey . '.' . $translationKey);
                continue;
            }

            $this->assertIsString($translationValue);
            $this->translationKeys[] = $translationParentKey . '.' . $translationKey;
        }
    }

    /**
     * @group TranslationFilesTest
     * @test
     * @throws \Exception
     */
    public function checkOtherLocalesIfTranslationsKeysExists()
    {
        $missingTranslations = collect();

        foreach (config('languages.available') as $availableAppLanguage)
        {
            // We can skip the local languages, because we generated the test out of the keys of that translation.
            if($availableAppLanguage == app()->getLocale()) continue;

            $foundTranslations = [];

            foreach ($this->translationKeys as $translationKey) {

                try {
                    $translation = __($translationKey, [], $availableAppLanguage);
                    $this->assertIsString($translation);
                    $foundTranslations[$translationKey] = $translation; // Put it into the foundTranslation for the length check
//                    echo 'Found translation key for locale - ' . $availableAppLanguage .' | ' . $translationKey . PHP_EOL;
                }
                catch (\Exception $exception) {
                    $missingTranslations->push((object) [
                        'message' => $exception->getMessage(),
                    ]);
                }
            }
        }

        if($missingTranslations->isNotEmpty()) {
            echo PHP_EOL;
            foreach ($missingTranslations as $missingTranslation) echo $missingTranslation->message . PHP_EOL;
            echo PHP_EOL;
            $this->assertEmpty($missingTranslations->count(), 'Not all the translations from the global language are present in the other available languages.');
        }
    }

    /**
     * @group TranslationFilesTest
     * @test
     * @throws \Exception
     */
    public function checkUsageOfTranslations()
    {

        $fileList = [];
        $this->addFilesContainLanguageFunctionsOutOfDirectory($fileList, app_path());
        $this->addFilesContainLanguageFunctionsOutOfDirectory($fileList, resource_path());

        // Create a list of all the used translation keys
        $usedTranslations = $this->createUsedTranslationList($fileList);

        $unusedTranslationsKeys = $this->translationKeys;
        $resolvedTranslations = [];

        // Loop through the used translations are defined they are defined.
        foreach ($usedTranslations as $key => $usedTranslation ) {

//            echo $usedTranslation.PHP_EOL;

            // Skip translations keys starting with, contains variables or already resolved keys
            if(Str::startsWith($usedTranslation, $this->skippableStartKeys) || Str::contains($usedTranslation, ['.$', '. $', '($']) || in_array($usedTranslation, $resolvedTranslations)) continue;

            // Validate that the translation is found.
            $this->assertContains($usedTranslation, $unusedTranslationsKeys, 'Found translation "' . $usedTranslation .'" is not defined in the languages files.');

            // Remove the translations from the unusedTranslationsKeys because it is used
            unset($unusedTranslationsKeys[array_search($usedTranslation, $unusedTranslationsKeys)]);

            // Append the resolve translation, in case it is used multiple times
            $resolvedTranslations[] = $usedTranslation;
        }

        $unusedTranslationErrors = collect();

        // Loop through the remaining defined translations keys
        foreach ($unusedTranslationsKeys as $key => $unusedTranslationsKey) {

            // Dont show through errors for the skippable start keys
            if(Str::startsWith($unusedTranslationsKey, $this->skippableStartKeys)) continue;

            $unusedTranslationErrors->push((object) [
                'message' => 'The translation key "' . $unusedTranslationsKey .'" is not used within the application. If this is not correct and if used in a undetectable way, then exclude it from this test.',
            ]);
        }

        if($unusedTranslationErrors->isNotEmpty()) {
            echo PHP_EOL;
            foreach ($unusedTranslationErrors as $unusedTranslationError) echo $unusedTranslationError->message . PHP_EOL;
            echo PHP_EOL;
            $this->assertEmpty($unusedTranslationErrors->count(), 'Not defined translations are used in the application or you should extends skippable keys.');
        }
    }

    /**
     * Map all the files out of the index into the file list
     *
     * @param  array  $fileList
     * @param  string  $directoryPath
     */
    private function addFilesContainLanguageFunctionsOutOfDirectory(array &$fileList, string $directoryPath)
    {

//        echo 'Directory indexed: "' . $directoryPath . '"' . PHP_EOL;
        $directory = new \DirectoryIterator($directoryPath);

        foreach ($directory as $file) {

            // Exclude itself
            if($file->isDot()) continue;

            // If directory then search within that directory
            if($file->isDir()) {
                $this->addFilesContainLanguageFunctionsOutOfDirectory($fileList, $file->getPathname());
                continue;
            }

            $fileContent = file_get_contents($file->getPathname());
            if(!Str::contains($fileContent, ['__(', 'trans(', '@lang('])) continue;

//            echo 'File addded that contains translation function: "' . $file->getPathname() . '"' . PHP_EOL ;
            $fileList[] = $file->getPathname();
        }
    }

    /**
     * Generate a list of used translations
     *
     * @param  array  $fileList
     * @return array
     */
    private function createUsedTranslationList(array $fileList): array
    {
        $usedTranslations = [];

        foreach ($fileList as $localizedFile)
        {
            $fileContent = file_get_contents($localizedFile);

            foreach ($this->languageFunctionPatterns as $pattern) {
                preg_match_all($pattern, $fileContent, $matches);
                $usedTranslations = array_merge($usedTranslations, $matches[1]);
            }
        }

        return $usedTranslations;
    }

}
