<?php

namespace App\Domain\Address\UseCase;

use App\Domain\Address\Repository\AddressRepository;

final class GetAddressesUseCase
{
    private AddressRepository $addressRepository;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    public function __invoke(): array
    {
        $user = auth()->user();

        if (!$user) {
            throw new \Exception(__('auth.notfound'));
        }

        return $this->addressRepository->getAllByUserId((int) $user->id);
    }
}
