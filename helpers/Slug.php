<?php
final class Slug { public static function make(string $value): string { $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value; return trim(strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $value)), '-'); } }
