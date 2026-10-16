<?php

namespace App\Models;

use A17\Twill\Models\Model;

class Redirect extends Model
{
    protected $fillable = [
        'published',
        'title',
        'from_path',
        'to_path',
        'status_code',
    ];

    public static function normalizePath(string $path): string
    {
        $path = '/' . trim(rawurldecode(parse_url($path, PHP_URL_PATH) ?? ''), '/');

        return mb_strtolower($path);
    }
}
