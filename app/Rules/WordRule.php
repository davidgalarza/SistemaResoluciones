<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class WordRule implements Rule
{
    private $file;

    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    public function passes($attribute, $value)
    {
        $extension = strtolower($this->file->getClientOriginalExtension());

        return in_array($extension, ['doc', 'docx', 'dotm']);
    }

    public function message()
    {
        return 'The excel file must be a file of type: csv, xls, xlsx.';
    }
}
