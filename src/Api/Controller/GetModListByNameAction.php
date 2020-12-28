<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Repository\ModListRepository;

class GetModListByNameAction
{
    /** @var ModListRepository */
    protected $modListRepository;

    public function __construct(ModListRepository $modListRepository)
    {
        $this->modListRepository = $modListRepository;
    }

    public function __invoke(string $name)
    {
        return $this->modListRepository->findOneBy([
            'name' => $name,
        ]);
    }
}
