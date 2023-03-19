<?php

function translateErrorMessage(string $key1 , string $key2): string
{
   return __('words.' . $key1) . ' ' . __('validation.' . $key2);
}

function translateSuccessMessage(string $key1 , string $key2): string
{
    return __('words.' . $key1) . ' ' . __('words.' . $key2);
}

function translateWord(string $word): string
{
    return __('words.' . $word);
}
