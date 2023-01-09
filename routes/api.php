<?php
Route::prefix('v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        /**
         * Работа с заметками
         */
        Route::get("/notes", "NoteController@index");
        Route::post("/notes", "NoteController@create");
        Route::get("/notes/{id}", "NoteController@view");
        Route::put("/notes/{id}", "NoteController@update");
        Route::delete("/notes/{id}", "NoteController@destroy");

        Route::get("/notes/folder/{id}", "NoteController@viewFolder");
    });
});
