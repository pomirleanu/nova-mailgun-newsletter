<?php

    use Illuminate\Support\Facades\Route;
    use Pomirleanu\MailgunNewsletter\Http\Controllers\MailgunNewsletterController;


    Route::get('templates', MailgunNewsletterController::class.'@getTemplates');
    Route::delete('templates/{templateName}', MailgunNewsletterController::class.'@deleteTemplate');
    Route::get('lists', MailgunNewsletterController::class.'@getLists');
    Route::delete('lists/{address}', MailgunNewsletterController::class.'@deleteList');
