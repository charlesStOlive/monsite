<?php


Route::post('mail', 'Charles\Mailgun\Controllers\MailController@mails')
    ->name('mails');
