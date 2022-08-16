<?php

namespace Imams\SilsilahKeluargaApi\Repository;

use Imams\SilsilahKeluargaApi\Entity\AnggotaKeluarga;

interface AnggotaKeluargaRepositoryInterface
{
    function save(AnggotaKeluarga $anggotaKeluarga): void;

    function update(AnggotaKeluarga $anggotaKeluarga): bool;

    function getById(int $anggotaKeluargaId);
    
    function remove(int $anggotaKeluargaId): bool;

    function findAll(): array;

    function findChildren(int $parentId): array;

    function findGrandChildren(int $grandParentId, ?string $jenisKelaminGrandChild): array;

    function findAunt(int $anggotaKeluargaId): array;

    function findCousin(int $anggotaKeluargaId, ?string $jenisKelaminCousin): array;
}
