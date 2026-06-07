<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormField extends Model
{
    protected $table = 'master.form_fields';

    protected $fillable = [
        'pelayanan_id',
        'name',
        'label',
        'type',
        'required',
        'options',
        'placeholder',
        'help_text',
        'order',
        'validation_rules',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'required' => 'boolean',
            'active' => 'boolean',
            'options' => 'array',
            'validation_rules' => 'array',
            'order' => 'integer',
        ];
    }

    public function pelayanan(): BelongsTo
    {
        return $this->belongsTo(Pelayanan::class, 'pelayanan_id');
    }

    /**
     * Scope untuk mendapatkan field yang aktif saja
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope untuk mengurutkan berdasarkan order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Get validation rules untuk field ini
     */
    public function getValidationRule(): array
    {
        $rules = [];

        if ($this->required) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        // Tambahkan rules berdasarkan type
        switch ($this->type) {
            case 'email':
                $rules[] = 'email';
                break;
            case 'tel':
                $rules[] = 'string';
                break;
            case 'number':
                $rules[] = 'numeric';
                break;
            case 'date':
                $rules[] = 'date';
                break;
            case 'select':
                if (!empty($this->options)) {
                    $rules[] = 'in:' . implode(',', $this->options);
                }
                break;
            case 'file':
            case 'image':
                $rules[] = 'file';

                if ($this->type === 'image') {
                    $rules[] = 'image';
                }

                $config = $this->getFileUploadConfig();

                if (!empty($config['mimes'])) {
                    $rules[] = 'mimes:' . implode(',', $config['mimes']);
                }

                if (!empty($config['max_size'])) {
                    $rules[] = 'max:' . $config['max_size'];
                }

                if (!empty($config['min_size'])) {
                    $rules[] = 'min:' . $config['min_size'];
                }

                if (!empty($config['mimetypes'])) {
                    $rules[] = 'mimetypes:' . implode(',', $config['mimetypes']);
                }

                if (!empty($config['dimensions']) && $this->type === 'image') {
                    $dimRules = [];
                    if (!empty($config['dimensions']['min_width'])) {
                        $dimRules[] = 'min_width=' . $config['dimensions']['min_width'];
                    }
                    if (!empty($config['dimensions']['max_width'])) {
                        $dimRules[] = 'max_width=' . $config['dimensions']['max_width'];
                    }
                    if (!empty($config['dimensions']['min_height'])) {
                        $dimRules[] = 'min_height=' . $config['dimensions']['min_height'];
                    }
                    if (!empty($config['dimensions']['max_height'])) {
                        $dimRules[] = 'max_height=' . $config['dimensions']['max_height'];
                    }
                    if (!empty($dimRules)) {
                        $rules[] = 'dimensions:' . implode(',', $dimRules);
                    }
                }
                break;
        }

        // Tambahkan custom validation rules jika ada
        if (!empty($this->validation_rules)) {
            $rules = array_merge($rules, $this->validation_rules);
        }

        return $rules;
    }

    /**
     * Get file upload configuration dari options
     */
    public function getFileUploadConfig(): array
    {
        if (!is_array($this->options)) {
            return [];
        }

        // Jika options berisi array asosiatif (config object)
        if (array_keys($this->options) !== range(0, count($this->options) - 1)) {
            return $this->options;
        }

        // Jika options berisi list MIME types (legacy: list of extensions)
        return [
            'mimes' => $this->options,
        ];
    }

    /**
     * Cek apakah field ini tipe file upload
     */
    public function isFileUpload(): bool
    {
        return in_array($this->type, ['file', 'image']);
    }

    /**
     * Cek apakah upload field mendukung multiple files
     */
    public function isMultiple(): bool
    {
        if (!$this->isFileUpload()) {
            return false;
        }
        $config = $this->getFileUploadConfig();
        return !empty($config['multiple']);
    }
}
