<?php

namespace App\Domain\Address\UseCase;

use App\Domain\Address\Entity\UpdateAddressRequestEntity;
use App\Domain\Address\Repository\AddressRepository;

final class UpdateAddressUseCase
{
    private AddressRepository $addressRepository;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    public function __invoke(UpdateAddressRequestEntity $requestEntity): array
    {
        $user = auth()->user();

        if (!$user) {
            throw new \Exception(__('auth.notfound'));
        }

        $result = $this->addressRepository->update(
            $requestEntity->getAddressId(),
            (int) $user->id,
            $requestEntity->getMasterData(),
            $requestEntity->getDetail()
        );

        if (!$result) {
            throw new \Exception(__('address.update.failed'));
        }

        $address = $this->addressRepository->getById($requestEntity->getAddressId(), (int) $user->id);
        if (!$address) {
            throw new \Exception(__('address.not_found'));
        }
        return $address;
    }
}
