<?php

/**
 * Translate Error Message
 * @param string $key1
 * @param string $key2
 * @return string
 */
function translateErrorMessage(string $key1, string $key2): string
{
    return __('words.' . $key1) . ' ' . __('validation.' . $key2);
}

/**
 * Translate Success Message
 * @param string $key1
 * @param string $key2
 * @return string
 */
function translateSuccessMessage(string $key1, string $key2): string
{
    return __('words.' . $key1) . ' ' . __('words.' . $key2);
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
    array $wantToValidate = ['name', 'description'],
    array $customValidationRules = null): void
{
    foreach (config('translatable.locales') as $locale) {
        $descriptionIncluded = (bool)array_search('description', $wantToValidate);
        $nameRules = $customValidationRules['name'] ?? "string|max:255";
        if ($descriptionIncluded) {
            $descriptionRules = $customValidationRules['description'] ?? "string|nullable";
        }

        if ($locale == app()->getLocale()) {

            if ($descriptionIncluded) {
                $descriptionRules = "required|" . $descriptionRules;
            }
            $nameRules = "required|" . $nameRules;
        } else {
            $nameRules = "sometimes|" . $nameRules;
            if ($descriptionIncluded) {
                $descriptionRules = "sometimes|" . $descriptionRules;
            }
        }

        $allRoles['name:' . $locale] = $nameRules;
        if ($descriptionIncluded) {
            $allRoles['description:' . $locale] = $descriptionRules;
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
 * Translate Single Word
 *
 * @param string $word
 * @return string
 */
function translateWord(string $word): string
{
    return __('words.' . $word);
}

