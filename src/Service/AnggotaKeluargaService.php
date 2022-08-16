<?php

namespace Imams\SilsilahKeluargaApi\Service;

use Imams\SilsilahKeluargaApi\Entity\AnggotaKeluarga;
use Imams\SilsilahKeluargaApi\Repository\AnggotaKeluargaRepositoryInterface;
use Imams\SilsilahKeluargaApi\Service\AnggotaKeluargaServiceInterface;

class AnggotaKeluargaService implements AnggotaKeluargaServiceInterface
{
    private $anggotaKeluargaRepository;

    public function __construct(AnggotaKeluargaRepositoryInterface $anggotaKeluargaRepository)
    {
        $this->anggotaKeluargaRepository = $anggotaKeluargaRepository;
    }

    public function showAnggotaKeluargaList(): array
    {
        $results = $this->anggotaKeluargaRepository->findAll();
        $anggotaKeluargaList = $this->getFormattedData($results);

        return $anggotaKeluargaList;
    }

    public function addAnggotaKeluarga(array $data): array
    {
        $anggotaKeluarga = new AnggotaKeluarga();
        $anggotaKeluarga->setNama($data['nama']);
        $anggotaKeluarga->setJenisKelamin($data['jenis_kelamin']);
        $anggotaKeluarga->setParentId($data['parent_id'] ?? null);
        $this->anggotaKeluargaRepository->save($anggotaKeluarga);

        return [
            'nama' => $data['nama'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'parent_id' => $data['parent_id']
        ];
    }

    public function showAnggotaKeluarga(int $anggotaKeluargaId): array
    {
        $result = $this->anggotaKeluargaRepository->getById($anggotaKeluargaId);

        if ($result) {
            return [
                'id' => $result->getId(),
                'nama' => $result->getNama(),
                'jenis_kelamin' => $result->getJenisKelamin(),
                'parent_id' =>  $result->getParentId()
            ];
        } else {
            throw new \Exception('Data dengan ID yang telah ditentukan tidak ditemukan');
        }
    }

    public function updateAnggotaKeluarga(array $data): array
    {
        $anggotaKeluarga = new AnggotaKeluarga();
        $anggotaKeluarga->setId($data['id']);
        $anggotaKeluarga->setNama($data['nama']);
        $anggotaKeluarga->setJenisKelamin($data['jenis_kelamin']);
        $anggotaKeluarga->setParentId($data['parent_id'] ?? null);
        $result = $this->anggotaKeluargaRepository->update($anggotaKeluarga);

        if ($result) {
            return [
                'id' => $anggotaKeluarga->getId(),
                'nama' => $anggotaKeluarga->getNama(),
                'jenis_kelamin' => $anggotaKeluarga->getJenisKelamin(),
                'parent_id' =>  $anggotaKeluarga->getParentId()
            ];
        } else {
            throw new \Exception('Data dengan ID yang telah ditentukan tidak ditemukan');
        }
    }

    public function removeAnggotaKeluarga(int $anggotaKeluargaId)
    {
        $result = $this->anggotaKeluargaRepository->remove($anggotaKeluargaId);

        if (!$result) {
            throw new \Exception('Data dengan ID yang telah ditentukan tidak ditemukan');
        }
    }

    public function showAnggotaKeluargaChildren(int $anggotaKeluargaId): array
    {
        $results = $this->anggotaKeluargaRepository->findChildren($anggotaKeluargaId);
        $anggotaKeluargaList = $this->getFormattedData($results);

        return $anggotaKeluargaList;
    }

    public function showAnggotaKeluargaGrandChildren(
                                int $anggotaKeluargaId, 
                                ?string $jenisKelaminGrandChild): array
    {
        $results = $this->anggotaKeluargaRepository->findGrandChildren($anggotaKeluargaId, $jenisKelaminGrandChild);
        $anggotaKeluargaList = $this->getFormattedData($results);

        return $anggotaKeluargaList;
    }

    public function showAnggotaKeluargaAunt(int $anggotaKeluargaId): array
    {
        $results = $this->anggotaKeluargaRepository->findAunt($anggotaKeluargaId);
        $anggotaKeluargaList = $this->getFormattedData($results);

        return $anggotaKeluargaList;
    }

    public function showAnggotaKeluargaCousin(int $anggotaKeluargaId, ?string $jenisKelaminCousin): array
    {
        $results = $this->anggotaKeluargaRepository->findCousin($anggotaKeluargaId, $jenisKelaminCousin);
        $anggotaKeluargaList = $this->getFormattedData($results);

        return $anggotaKeluargaList;
    }

    private function getFormattedData($items)
    {
        // format array of object to assoc array
        $formattedData = [];

        for ($i = 0; $i < count($items); $i++) {
            foreach ($items[$i] as $key => $value) {
                $formattedData[$i][$key] = $value;
            }
        }

        return $formattedData;
    }
}
