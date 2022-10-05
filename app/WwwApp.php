<?php

namespace App;

use Illuminate\Foundation\Application;
use Komma\KMS\Globalization\Languages\Models\Language;

/**
 * This is an extension of the laravel App.
 * It changes the public folder to www
 * Don't forget to update the server.php file.
 * Don't forget to check your gulpfile.js
 *
 * And make language and site accessible by App::{function}
 *
 * Class WwwApp
 * @package App
 */
class WwwApp extends Application
{
    /** @var Language $language */
    protected $language = null;

    //Set living time to 1 month
    private $languageCookieLivingTime = 312480;

    public function publicPath()
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'www';
    }

    /**
     * Get language
     *
     * @return Language
     */
    public function getLanguage():Language
    {
        if(!$this->language) {
            $language = Language::where('iso_2', '=', strtolower(config('app.locale')))->first();
            if($language) $this->language = $language;
        }

        return $this->language;
    }

    /**
     * Set application language by Language Model
     *
     * @param Language $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        $iso_2 = $language->iso_2;

        //Make new cookie and set App locale
        \Cookie::queue('language', $iso_2, $this->languageCookieLivingTime);

        //Also make a session just in case
        \Session::put('language', $iso_2);

        \App::setLocale($iso_2);
    }

    /**
     * Set application language by iso_2
     *
     * @param $iso_2
     */
    public function changeLanguageByIso2($iso_2){

        $language = Language::where('iso_2', '=', $iso_2)->first();

        $this->setLanguage($language);
    }

    public function reload(){
        return redirect(\Request::root() . $_SERVER['REQUEST_URI']);
    }

}