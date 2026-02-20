<?php

namespace App\Domain\UserProfile\Entity;

class UpdateUserProfileRequestEntity
{
    public function __construct(
        public readonly string $emailSend,
        public readonly string $postalCode,
        public readonly string $prefectureId,
        public readonly string $city,
        public readonly ?string $password,
        public readonly string $streetAddress,
        public readonly ?string $building,
        public readonly string $lineName,
        public readonly string $lastNameKanji,
        public readonly string $firstNameKanji,
        public readonly string $lastNameKana,
        public readonly string $firstNameKana,
        public readonly ?string $groupName,
        public readonly ?string $groupNameKana,
        public readonly string $phone,
        public readonly bool $isBlack,
        public readonly bool $billingSameAddressFlag,
        public readonly ?string $memo,
        public readonly string $birthday,
    ) {
    }

    public function toArray(): array
    {
        return [
            'email_send' => $this->emailSend,
            'postal_code' => $this->postalCode,
            'prefecture_id' => $this->prefectureId,
            'city' => $this->city,
            'password' => $this->password,
            'street_address' => $this->streetAddress,
            'building' => $this->building,
            'line_name' => $this->lineName,
            'last_name_kanji' => $this->lastNameKanji,
            'first_name_kanji' => $this->firstNameKanji,
            'last_name_kana' => $this->lastNameKana,
            'first_name_kana' => $this->firstNameKana,
            'group_name' => $this->groupName,
            'group_name_kana' => $this->groupNameKana,
            'phone' => $this->phone,
            'is_black' => $this->isBlack,
            'billing_same_address_flag' => $this->billingSameAddressFlag,
            'memo' => $this->memo,
            'birthday' => $this->birthday,
        ];
    }
}
