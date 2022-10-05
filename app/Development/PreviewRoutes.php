<?php


namespace App\Development;

use Illuminate\Support\Facades\Route;


final class PreviewRoutes
{

    /**
     * Route that are resolved from the Alias route to Rest Route by the AliasMiddleware.
     * Through the controller the models will be bind by Implicit Route Model Binding,
     * so no need to add Route::model in here.
     */
    public static function web(){
        Route::prefix('preview')
            ->group(function () {

            Route::prefix('mail')
                ->group(function (){
                    Route::get('set_password_kms', PreviewController::class.'@setPasswordMailKms')->name('preview.mail.set_password_kms');
                    Route::get('reset_password_kms', PreviewController::class.'@resetPasswordMailKms')->name('preview.mail.reset_password_kms');
                    Route::get('order_status/{status}/{staffOrCustomer}/{order?}', PreviewController::class.'@orderStatusMail')->name('preview.mail.order_status');
                    Route::get('order_manual_action/{order?}', PreviewController::class.'@orderManualActionRequired')->name('preview.mail.order_manual_action');
                    Route::get('contact', PreviewController::class.'@contact')->name('preview.mail.contact');
                    Route::get('vacancy_apply', PreviewController::class.'@vacancyApply')->name('preview.mail.vacancy');
                    Route::get('event_signup_received', PreviewController::class.'@eventSignUpReceived')->name('preview.mail.eventSignUpReceived');
                    Route::get('event_signup_response', PreviewController::class.'@eventSignUpResponse')->name('preview.mail.eventSignUpResponse');
                });

            Route::prefix('logistics')
                ->group(function (){
                    Route::get('invoice/{order?}', PreviewController::class.'@invoice')->name('preview.mail.invoice');
                    Route::get('pdf/invoice/{order?}', PreviewController::class.'@invoicePdf')->name('preview.mail.pdf.invoice');
                    Route::get('pdfDownload/invoice/{order?}', PreviewController::class.'@invoicePdfDownload')->name('preview.mail.pdf.download.invoice');
                });

            Route::prefix('view')
                ->group(function (){
                    Route::get('kms/auth/login', PreviewController::class.'@loginViewKms')->name('preview.view.kms.auth.login');
                    Route::get('kms/auth/forgot_password', PreviewController::class.'@forgotPasswordKms')->name('preview.view.kms.auth.forgot_password');
                    Route::get('kms/auth/set', PreviewController::class.'@setKms')->name('preview.view.kms.auth.set');
                    Route::get('kms/auth/reset', PreviewController::class.'@resetKms')->name('preview.view.kms.auth.reset');
                });
        });
    }
}