<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'job_title' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'employment_type_id' => ['required', Rule::exists('employment_types', 'id')],
            'applicant_names' => ['required', 'string', 'max:255'],
            'applicant_last_names' => ['required', 'string', 'max:255'],
            'applicant_email' => ['required', 'email', 'max:255'],
            'applicant_phone' => ['required', 'string', 'max:20'],
            'applicant_linkedin' => ['nullable', 'url', 'max:255'],
            'applicant_portfolio_link' => ['nullable', 'url', 'max:255'],
            'applicant_country' => ['required', 'string', 'max:255'],
            'applicant_city' => ['required', 'string', 'max:255'],
            'applicant_address' => ['required', 'string', 'max:255'],
            'cv' => ['required', 'file', 'mimes:pdf', 'max:51200'], // 50MB max
            'monthly_expected_salary' => ['required', 'regex:/^\d+(\.\d{1,2})?$/', 'min:0'],
            'availability_id' => ['required', Rule::exists('availabilities', 'id')],
            'work_modality_id' => ['required', Rule::exists('work_modalities', 'id')],
            'educations' => ['required', 'array', 'min:1'],
            'educations.*.education_degree' => ['required', 'string', 'max:255'],
            'educations.*.education_institution' => ['required', 'string', 'max:255'],
            'educations.*.start_date' => ['required', 'date'],
            'educations.*.end_date' => ['nullable', 'date', 'after_or_equal:educations.*.start_date'],
            'educations.*.is_ongoing' => ['required', 'boolean'],
            'experiences' => ['required', 'array', 'min:1'],
            'experiences.*.company_name' => ['required', 'string', 'max:255'],
            'experiences.*.job_title' => ['required', 'string', 'max:255'],
            'experiences.*.start_date' => ['required', 'date'],
            'experiences.*.end_date' => ['nullable', 'date', 'after_or_equal:experiences.*.start_date'],
            'experiences.*.description' => ['nullable', 'string'],
            'experiences.*.location' => ['nullable', 'string', 'max:255'],
            'experiences.*.is_current_job' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'job_title.required' => 'El título del puesto es obligatorio.',
            'job_title.string' => 'El título del puesto debe ser una cadena de texto.',
            'job_title.max' => 'El título del puesto no debe exceder los 255 caracteres.',

            'company_name.required' => 'El nombre de la empresa es obligatorio.',
            'company_name.string' => 'El nombre de la empresa debe ser una cadena de texto.',
            'company_name.max' => 'El nombre de la empresa no debe exceder los 255 caracteres.',

            'employment_type_id.required' => 'El tipo de empleo es obligatorio.',
            'employment_type_id.exists' => 'El tipo de empleo seleccionado no es válido.',

            'applicant_names.required' => 'El nombre del solicitante es obligatorio.',
            'applicant_names.string' => 'El nombre del solicitante debe ser una cadena de texto.',
            'applicant_names.max' => 'El nombre del solicitante no debe exceder los 255 caracteres.',

            'applicant_last_names.required' => 'Los apellidos del solicitante son obligatorios.',
            'applicant_last_names.string' => 'Los apellidos del solicitante deben ser una cadena de texto.',
            'applicant_last_names.max' => 'Los apellidos del solicitante no deben exceder los 255 caracteres.',

            'applicant_email.required' => 'El correo electrónico del solicitante es obligatorio.',
            'applicant_email.email' => 'El correo electrónico del solicitante debe ser una dirección de correo válida.',
            'applicant_email.max' => 'El correo electrónico del solicitante no debe exceder los 255 caracteres.',

            'applicant_phone.required' => 'El teléfono del solicitante es obligatorio.',
            'applicant_phone.string' => 'El teléfono del solicitante debe ser una cadena de texto.',
            'applicant_phone.max' => 'El teléfono del solicitante no debe exceder los 20 caracteres.',

            'applicant_linkedin.url' => 'El enlace de LinkedIn del solicitante debe ser una URL válida.',
            'applicant_linkedin.max' => 'El enlace de LinkedIn del solicitante no debe exceder los 255 caracteres.',

            'applicant_portfolio_link.url' => 'El enlace del portafolio del solicitante debe ser una URL válida.',
            'applicant_portfolio_link.max' => 'El enlace del portafolio del solicitante no debe exceder los 255 caracteres.',

            'applicant_country.required' => 'El país del solicitante es obligatorio.',
            'applicant_country.string' => 'El país del solicitante debe ser una cadena de texto.',
            'applicant_country.max' => 'El país del solicitante no debe exceder los 255 caracteres.',

            'applicant_city.required' => 'La ciudad del solicitante es obligatoria.',
            'applicant_city.string' => 'La ciudad del solicitante debe ser una cadena de texto.',
            'applicant_city.max' => 'La ciudad del solicitante no debe exceder los 255 caracteres.',

            'applicant_address.required' => 'La dirección del solicitante es obligatoria.',
            'applicant_address.string' => 'La dirección del solicitante debe ser una cadena de texto.',
            'applicant_address.max' => 'La dirección del solicitante no debe exceder los 255 caracteres.',

            'cv.required' => 'El currículum (CV) es obligatorio.',
            'cv.file' => 'El currículum (CV) debe ser un archivo.',
            'cv.mimes' => 'El currículum (CV) debe ser un archivo de tipo PDF.',
            'cv.max' => 'El currículum (CV) no debe exceder los 50MB.',

            'monthly_expected_salary.required' => 'El salario mensual esperado es obligatorio.',
            'monthly_expected_salary.regex' => 'El salario mensual esperado debe ser un número válido.',
            'monthly_expected_salary.min' => 'El salario mensual esperado no puede ser negativo.',

            'availability_id.required' => 'La disponibilidad es obligatoria.',
            'availability_id.exists' => 'La disponibilidad seleccionada no es válida.',

            'work_modality_id.required' => 'La modalidad de trabajo es obligatoria.',
            'work_modality_id.exists' => 'La modalidad de trabajo seleccionada no es válida.',

            'educations.required' => 'La información de educación es obligatoria.',
            'educations.array' => 'La información de educación debe ser un arreglo.',
            'educations.min' => 'Debe ingresar al menos una entrada de educación.',

            'educations.*.education_degree.required' => 'El título del grado académico es obligatorio.',
            'educations.*.education_degree.string' => 'El título del grado académico debe ser una cadena de texto.',
            'educations.*.education_degree.max' => 'El título del grado académico no debe exceder los 255 caracteres.',

            'educations.*.education_institution.required' => 'La institución educativa es obligatoria.',
            'educations.*.education_institution.string' => 'La institución educativa debe ser una cadena de texto.',
            'educations.*.education_institution.max' => 'La institución educativa no debe exceder los 255 caracteres.',

            'educations.*.start_date.required' => 'La fecha de inicio de la educación es obligatoria.',
            'educations.*.start_date.date' => 'La fecha de inicio de la educación debe ser una fecha válida.',

            'educations.*.end_date.date' => 'La fecha de fin de la educación debe ser una fecha válida.',
            'educations.*.end_date.after_or_equal' => 'La fecha de fin de la educación debe ser igual o posterior a la fecha de inicio.',

            'educations.*.is_ongoing.required' => 'El campo "en curso" es obligatorio.',
            'educations.*.is_ongoing.boolean' => 'El campo "en curso" debe ser verdadero o falso.',

            'experiences.required' => 'La información de experiencia laboral es obligatoria.',
            'experiences.array' => 'La información de experiencia laboral debe ser un arreglo.',
            'experiences.min' => 'Debe ingresar al menos una entrada de experiencia laboral.',

            'experiences.*.company_name.required' => 'El nombre de la empresa es obligatorio.',
            'experiences.*.company_name.string' => 'El nombre de la empresa debe ser una cadena de texto.',
            'experiences.*.company_name.max' => 'El nombre de la empresa no debe exceder los 255 caracteres.',

            'experiences.*.job_title.required' => 'El título del puesto es obligatorio.',
            'experiences.*.job_title.string' => 'El título del puesto debe ser una cadena de texto.',
            'experiences.*.job_title.max' => 'El título del puesto no debe exceder los 255 caracteres.',

            'experiences.*.start_date.required' => 'La fecha de inicio de la experiencia laboral es obligatoria.',
            'experiences.*.start_date.date' => 'La fecha de inicio de la experiencia laboral debe ser una fecha válida.',

            'experiences.*.end_date.date' => 'La fecha de fin de la experiencia laboral debe ser una fecha válida.',
            'experiences.*.end_date.after_or_equal' => 'La fecha de fin de la experiencia laboral debe ser igual o posterior a la fecha de inicio.',

            'experiences.*.description.string' => 'La descripción de la experiencia laboral debe ser una cadena de texto.',

            'experiences.*.location.string' => 'La ubicación de la experiencia laboral debe ser una cadena de texto.',
            'experiences.*.location.max' => 'La ubicación de la experiencia laboral no debe exceder los 255 caracteres.',

            'experiences.*.is_current_job.required' => 'El campo "trabajo actual" es obligatorio.',
            'experiences.*.is_current_job.boolean' => 'El campo "trabajo actual" debe ser verdadero o falso.',
        ];
    }
}
