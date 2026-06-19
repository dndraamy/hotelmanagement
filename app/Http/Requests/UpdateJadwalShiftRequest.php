<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJadwalShiftRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'id_pegawai' => 'required|exists:pegawai,id_pegawai',
            'id_shift'   => 'required|exists:shift,id_shift',
            'tanggal'    => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'id_pegawai.required' => 'Pegawai wajib dipilih.',
            'id_shift.required'   => 'Shift wajib dipilih.',
            'tanggal.required'    => 'Tanggal wajib diisi.',
        ];
    }
}
