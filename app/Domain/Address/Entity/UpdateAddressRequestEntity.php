<?php

namespace App\Domain\Address\Entity;

class UpdateAddressRequestEntity
{
    public function __construct(
        private int $addressId,
        private ?string $label = null,
        private ?string $inputMode = null,
        private ?string $postalCode = null,
        private ?string $phone = null,
        private ?string $imageUrl = null,
        private ?bool $isDefault = null,
        private array $detail = [],
    ) {
    }

    public function getAddressId(): int
    {
        return $this->addressId;
    }

    public function getDetail(): array
    {
        return $this->detail;
    }

    public function getMasterData(): array
    {
        $data = [];

        if ($this->label !== null) {
            $data['label'] = $this->label;
        }
        if ($this->inputMode !== null) {
            $data['input_mode'] = $this->inputMode;
        }
        if ($this->postalCode !== null) {
            $data['postal_code'] = $this->postalCode;
        }
        if ($this->phone !== null) {
            $data['phone'] = $this->phone;
        }
        if ($this->imageUrl !== null) {
            $data['image_url'] = $this->imageUrl;
        }
        if ($this->isDefault !== null) {
            $data['is_default'] = $this->isDefault;
        }

        return $data;
    }
}
