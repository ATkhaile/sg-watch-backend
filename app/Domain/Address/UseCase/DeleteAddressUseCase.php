<?php

namespace App\Domain\Address\UseCase;

use App\Domain\Address\Repository\AddressRepository;

final class DeleteAddressUseCase
{
    private AddressRepository $addressRepository;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    public function __invoke(int $addressId): void
    {
        $user = auth()->user();

        if (!$user) {
            throw new \Exception(__('auth.notfound'));
        }

        $result = $this->addressRepository->delete($addressId, (int) $user->id);

        if (!$result) {
            throw new \Exception(__('address.delete.failed'));
        }
    }
}
