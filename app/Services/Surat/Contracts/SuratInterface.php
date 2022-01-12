<?php

namespace App\Services\Surat\Contracts;

interface SuratInterface
{
    /**
     * Validation array form surat.
     *
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function rules();

    /**
     * Form surat attribute.
     *
     * @return array
     */
    public function form();
}
