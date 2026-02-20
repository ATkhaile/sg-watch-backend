<?php

namespace App\Domain\Address\Entity;

class CreateAddressRequestEntity
{
    public function __construct(
        private string $label,
        private string $countryCode,
        private string $inputMode,
        private ?string $postalCode = null,
        private ?string $phone = null,
        private ?string $imageUrl = null,
        private bool $isDefault = false,
        private array $detail = [],
    ) {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getInputMode(): string
    {
        return $this->inputMode;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function getDetail(): array
    {
        return $this->detail;
    }

    public function getMasterData(): array
    {
        return [
            'label' => $this->label,
            'country_code' => $this->countryCode,
            'input_mode' => $this->inputMode,
            'postal_code' => $this->postalCode,
            'phone' => $this->phone,
            'image_url' => $this->imageUrl,
            'is_default' => $this->isDefault,
        ];
    }
}
