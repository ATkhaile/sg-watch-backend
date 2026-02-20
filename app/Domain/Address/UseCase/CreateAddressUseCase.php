<?php

namespace App\Domain\Address\UseCase;

use App\Domain\Address\Entity\CreateAddressRequestEntity;
use App\Domain\Address\Repository\AddressRepository;

final class CreateAddressUseCase
{
    private AddressRepository $addressRepository;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    public function __invoke(CreateAddressRequestEntity $requestEntity): array
    {
        $user = auth()->user();

        if (!$user) {
            throw new \Exception(__('auth.notfound'));
        }

        $addressId = $this->addressRepository->create(
            (int) $user->id,
            $requestEntity->getMasterData(),
            $requestEntity->getDetail()
        );

        if (!$addressId) {
            throw new \Exception(__('address.create.failed'));
        }

        $address = $this->addressRepository->getById($addressId, (int) $user->id);
        if (!$address) {
            throw new \Exception(__('address.not_found'));
        }
        return $address;
    }
}
