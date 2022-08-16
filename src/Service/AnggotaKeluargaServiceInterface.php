<?php

namespace Imams\SilsilahKeluargaApi\Service;

use Imams\SilsilahKeluargaApi\Entity\AnggotaKeluarga;

interface AnggotaKeluargaServiceInterface
{
    function showAnggotaKeluargaList(): array;

    function addAnggotaKeluarga(array $data): array;

    function showAnggotaKeluarga(int $anggotaKeluargaId): array;

    function updateAnggotaKeluarga(array $data): array;

    function removeAnggotaKeluarga(int $anggotaKeluargaId);

    function showAnggotaKeluargaChildren(int $anggotaKeluargaId): array;

    function showAnggotaKeluargaGrandChildren(int $anggotaKeluargaId, ?string $jenisKelaminGrandChild): array;

    function showAnggotaKeluargaAunt(int $anggotaKeluargaId): array;

    function showAnggotaKeluargaCousin(int $anggotaKeluargaId, ?string $jenisKelaminCousin): array;
}
