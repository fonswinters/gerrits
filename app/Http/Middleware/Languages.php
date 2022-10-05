<?php


namespace App\Http\Middleware;


use Komma\KMS\Globalization\Languages\Models\Language;

class Languages
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {

        //Check if application is defined for multiple applications
        if(config('app.multipleLanguages')){
            //Check for language cookie
            $languageIso = \Cookie::get('language', false);

            // Check if there isn't a cookie defined
            // Then check for language session
            if($languageIso == false) $languageIso = \Session::get('language', false);

            // Now check if the language iso isset
            //so basically if it is an active visitor (or a return visitor by cookie)
            if( $languageIso == false){
                //Grab the default language
                $languageIso = \App::getLocale();
            }
        }
        else{
            //If not multiple language
            //Grab the default language defined in App config
            $languageIso = \App::getLocale();
        }

        // Get Language model and set to Application
        $language = Language::where('iso_2', $languageIso)->first();
        \App::setLanguage($language);

        // When using the KMS overwrite the cookie setLocale by the kms config locale
        if($request->segment(1) == 'kms'){
            \App::setLocale(config('kms.locale'));
        }

        return $next($request);
    }
}