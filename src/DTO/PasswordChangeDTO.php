<?php


namespace App\DTO;


class PasswordChangeDTO
{
    const PREVIOUS_PASSWORD = 'Previous_password';
    const NEW_PASSWORD = 'New_password';
    const REPEAT_NEW_PASSWORD = 'Repeat_new_password';

    private ?string $previousPassword;

    private ?string $newPassword;

    private ?string $passwordRepeat;

    public function __construct(array $data)
    {
        $this->previousPassword = array_key_exists(self::PREVIOUS_PASSWORD, $data) ? $data[self::PREVIOUS_PASSWORD] : null;
        $this->newPassword = array_key_exists(self::NEW_PASSWORD, $data) ? $data[self::NEW_PASSWORD] : null;
        $this->passwordRepeat = array_key_exists(self::REPEAT_NEW_PASSWORD, $data) ? $data[self::REPEAT_NEW_PASSWORD] : null;
    }

    public function validate(string $currentHashedPassword): bool
    {
        if (password_verify($this->previousPassword, $currentHashedPassword) && $this->newPassword === $this->passwordRepeat && strlen($this->newPassword) > 0) {
            return true;
        }

        return false;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }
}
