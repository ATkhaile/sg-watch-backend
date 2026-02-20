<?php

namespace App\Domain\Address\UseCase;

use App\Domain\Address\Repository\AddressRepository;

final class GetAddressDetailUseCase
{
    private AddressRepository $addressRepository;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    public function __invoke(int $addressId): array
    {
        $user = auth()->user();

        if (!$user) {
            throw new \Exception(__('auth.notfound'));
        }

        $address = $this->addressRepository->getById($addressId, (int) $user->id);

        if (!$address) {
            throw new \Exception(__('address.not_found'));
        }

        return $address;
    }
}
