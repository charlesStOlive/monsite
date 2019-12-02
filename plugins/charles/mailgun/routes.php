<?php

Route::post('mail', 'Charles\Mailgun\Controllers\MailController@mails')
    ->name('mails');

Route::options('api/mg/formSubmit', function() {
    return Response::make('You are connected to the API');
});

Route::post('api/mg/formSubmit','Charles\Mailgun\Controllers\PostController@formSubmit');

Route::get('maker/pdfreport/{user_id}/{sendDate?}/', 'Charles\Mailgun\Controllers\PdfReportController@index');
//
Route::get('maker/pdfcv/{user_key}', 'Charles\Mailgun\Controllers\PdfCvController@index');
Route::get('maker/pdflm/{user_key}', 'Charles\Mailgun\Controllers\PdfCvController@download_lm');
Route::get('maker/pdflms/{user_key}', 'Charles\Mailgun\Controllers\PdfCvController@stream_lm');
Route::get('maker/pdfcvtest/{user_key}', 'Charles\Mailgun\Controllers\PdfCvController@test');
Route::get('maker/pdfcvdownload/{user_key}', 'Charles\Mailgun\Controllers\PdfCvController@downloadCv');
//
Route::get('api/user/{userkey?}', 'Charles\Mailgun\Controllers\UserApiController@index');
//
Route::options('api/{any}', function() {
    return Response::make('You are connected to the API');
});
Route::options('maker/{any}', function() {
    return Response::make('You are connected to the API');
});
Route::get('api/pdfreportglobal/{user_id}/{region_id}/{is_pdf}/{sendDate?}/', 'Charles\Mailgun\Controllers\PdfReportGlobalController@index');



