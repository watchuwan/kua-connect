<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Validation\ValidationException;

class CustomLogin extends BaseLogin
{
    public function form(Schema $schema): Schema
    {
        return $schema->components([
            $this->getEmailFormComponent(),
            $this->getPasswordFormComponent(),
            // $this->getTahunFormComponent(),
            $this->getRememberFormComponent(),
        ]);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make("email")
            ->label("Nama / Alamat Email")
            ->required()
            ->autofocus();
    }

    // protected function getTahunFormComponent(): Component
    // {
    //     return Select::make("year_id")
    //         ->label("Tahun")
    //         ->options(Year::orderByDesc("year")->pluck("year", "id"))
    //         ->default(Year::where("is_active", true)->value("id"));
    // }

    public function authenticate(): ?LoginResponse
    {
        $response = parent::authenticate();

        // $data = $this->form->getState();
        // session()->put("active_year_id", $data["year_id"]);

        return $response;
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        $loginType = filter_var($data["email"], FILTER_VALIDATE_EMAIL)
            ? "email"
            : "name";

        return [
            $loginType => $data["email"],
            "password" => $data["password"],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            "data.email" => __(
                "filament-panels::auth/pages/login.messages.failed",
            ),
        ]);
    }
}
