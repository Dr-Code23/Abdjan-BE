<?php

use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

/**
 * Determine if request url came from public user
 * @param string $key
 * @param string|null $fullString
 * @return bool
 */
function isPublicRoute(string $key = 'public', string $fullString = null): bool
{
    return Str::contains(
        $fullString ?: request()->url(),$key ?: 'public'
    );
}

/**
 * Determine if current url didn't come from public user
 * @param string $key
 * @param string|null $fullString
 * @return bool
 */
function isNotPublicRoute(string $key = 'public', string $fullString = null): bool
{
    return !Str::contains($fullString ?: request()->url(),$key ?: 'public');
}

/**
 * @param bool $isUpdate
 * @param array|null $rules
 * @return array|string[]
 */
function imageRules(bool $isUpdate , array $rules = null): array
{
        return $rules ?: [
            $isUpdate ? 'sometimes' : 'required',
            'image',
            'mimes:jpg,png,jpeg,jfif',
            'max:1000'
        ];
}


/**
 * @param bool $isUpdate
 * @param array|null $rules
 * @return array
 */
function passwordRules(bool $isUpdate = false , array $rules = null): array
{
    return $rules ?: [
        $isUpdate ? 'sometimes' : 'required',
        'confirmed' ,
        Password::min(6)
            ->mixedCase()
            ->numbers()
            ->symbols()
    ];
}


/**
 * Translate Error Message
 * @param string $key1
 * @param string $key2
 * @return string
 */
function translateErrorMessage(string $key1, string $key2): string
{
    return __('messages.' . $key1) . ' ' . __('validation.' . $key2);
}

/**
 * Translate Success Message
 * @param string $key1
 * @param string $key2
 * @return string
 */
function translateSuccessMessage(string $key1, string $key2): string
{
    return __('messages.' . $key1) . ' ' . __('messages.' . $key2);
}

/**
 * Add Translation Rules To The Main Array
 * @param array $allRoles
 * @param array $wantToValidate
 * @param array|null $customValidationRules
 * @return void
 */
function addTranslationRules(
    array &$allRoles,
    array $wantToValidate = ['name'],
    array $customValidationRules = null): void
{
    $descriptionIncluded = (bool)array_search('description', $wantToValidate);

    $allRoles['name'] = ['required' , 'array'];
    if($descriptionIncluded){
        $allRoles['description'] = ['required' , 'array'];
    }

    foreach (config('translatable.locales') as $locale) {
        $nameRules = $customValidationRules['name'] ?? ['string' , 'max:255'];

        if ($descriptionIncluded) {
            $descriptionRules = $customValidationRules['description'] ?? ['string'];
        }

        if ($locale == app()->getLocale()) {

            if ($descriptionIncluded) {
                array_unshift($descriptionRules , 'required');
            }
            array_unshift($nameRules , 'required');

        } else {
            array_unshift($nameRules , 'sometimes');
            if ($descriptionIncluded) {
                array_unshift($descriptionRules , 'sometimes');
            }
        }
        $allRoles['name.' . $locale] = $nameRules;
        if ($descriptionIncluded) {
            $allRoles['description.' . $locale] = $descriptionRules;
        }
    }
}

/**
 * Customize Error Messages For Translated Columns
 *
 * @param array $allMessages
 * @param array $wantToTranslate
 * @return void
 */
function addCustomTranslationMessages(
    array &$allMessages,
    array $wantToTranslate = ['name']
): void
{
    $descriptionIncluded = (bool)array_search('description', $wantToTranslate);
    foreach (config('translatable.locales') as $locale) {
        $allMessages['name:' . $locale . ".required"] = translateErrorMessage('title', 'required');
        $allMessages['name:' . $locale . ".string"] = translateErrorMessage('title', 'string');
        $allMessages['name:' . $locale . ".max"] = translateErrorMessage('title', 'max.string');
        if ($descriptionIncluded) {
            $allMessages['description:' . $locale . ".required"] = translateErrorMessage('description', 'required');
            $allMessages['description:' . $locale . ".string"] = translateErrorMessage('description', 'string');
        }
    }
}

/**
 * @param string $class
 * @param $request
 * @param array $errors
 * @param int|null $id
 * @param string $idColumnName
 * @param array|null $parentId
 * @param string|null $msg
 * @param string $nameColumn
 * @return void
 */
function checkIfNameExists(
    string $class,
           $request ,
    array &$errors,
    int $id = null ,
    string $idColumnName = 'id',
    array $parentId = null,
    string $msg = null,
    string $nameColumn = 'name'
): void
{
    $record =  (new $class)::where(function($query) use ($request , $nameColumn){
        $defaultLocales = config('translatable.locales');
        $default = false;
        foreach($request->name as $locale => $value){
            if(in_array($locale , $defaultLocales)){
                if(!$default){
                    $query->where($nameColumn . '->' . $locale , $value);
                    $default = true;
                }
                else {
                    $query->orWhere($nameColumn . '->' . $locale , $value);
                }
            }
        }

    })
        ->where(function($query) use ($idColumnName, $id){
            if($id){
                $query->where($idColumnName , '!=', $id);
            }
        })
        ->where(function($query) use ($parentId){
            if($parentId != null){
                $query->where('parent_id' ,$parentId[0] ,$parentId[1]);
            }
        })
        ->first();

    if($record){

        foreach ($record->getTranslations()[$nameColumn] as $locale => $value){

            foreach ($request->name as $requestLocale => $localeValue){
                if($locale == $requestLocale && $localeValue == $value){
                    $errors["$nameColumn." . $locale] = $msg ?: translateErrorMessage($nameColumn , 'exists');
                }
            }
        }
    }
}
/**
 * Translate Single Word
 *
 * @param string $word
 * @return string
 */
function translateWord(string $word): string
{
    return __('messages.' . $word);
}


function addTranslatedKeysRules(array &$rules , array $translatedKeys = [
    'title' => ['required','array'],
    'description'=> ['required','array']
]){
    $translatedKeys = ['title' , 'description'];
    $availableLocales = config('translatable.locales');

    foreach($translatedKeys as $key){
        $keyRules = ['required','array'];

        foreach($availableLocales as $locale){

            array_unshift(
                $keyRules ,
                $locale == app()->getLocale() ? 'required' : 'sometimes'
            );
            $rules["$key.$locale"] = $keyRules;
        }

        $rules[$key] = $keyRules;
    }
}


function setToken(string $token){

    if(!is_dir(__DIR__.'/../../tests/results')){
        mkdir(__DIR__.'/../../tests/results');
        chmod(__DIR__.'/../../tests/results' , 0777);
    }

    $handle = fopen(__DIR__.'/../../tests/results/token.txt' , 'w');
    fwrite($handle , $token);
    fclose($handle);
}

function getToken(): bool|string
{
    return file_get_contents(__DIR__.'/../../tests/results/token.txt');
}
