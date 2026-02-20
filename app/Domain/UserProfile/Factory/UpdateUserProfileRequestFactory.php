<?php

namespace App\Domain\UserProfile\Factory;

use App\Domain\UserProfile\Entity\UpdateUserProfileRequestEntity;
use App\Http\Requests\Api\UserProfile\UpdateUserProfileRequest;

class UpdateUserProfileRequestFactory
{
    public function createFromRequest(UpdateUserProfileRequest $request): UpdateUserProfileRequestEntity
    {
        return new UpdateUserProfileRequestEntity(
            emailSend: $request->email_send,
            postalCode: $request->postal_code,
            prefectureId: $request->prefecture_id,
            city: $request->city,
            password: $request->password,
            streetAddress: $request->street_address,
            building: $request->building,
            lineName: $request->line_name,
            lastNameKanji: $request->last_name_kanji,
            firstNameKanji: $request->first_name_kanji,
            lastNameKana: $request->last_name_kana,
            firstNameKana: $request->first_name_kana,
            groupName: $request->group_name,
            groupNameKana: $request->group_name_kana,
            phone: $request->phone,
            isBlack: $request->is_black,
            billingSameAddressFlag: $request->billing_same_address_flag,
            memo: $request->memo,
            birthday: $request->birthday,
        );
    }
}
