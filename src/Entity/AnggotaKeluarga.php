<?php

namespace Imams\SilsilahKeluargaApi\Entity;

class AnggotaKeluarga implements \IteratorAggregate
{
    private int $id;
    private string $nama;
    private string $jenisKelamin;
    private ?int $parentId = null;

    public function getIterator()
    {
        return new \ArrayIterator([
            'id' => $this->id,
            'nama' => $this->nama,
            'jenis_kelamin' => $this->jenisKelamin,
            'parent_id' => $this->parentId
        ]);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function setNama(string $nama): void
    {
        $this->nama = $nama;
    }

    public function getJenisKelamin(): string
    {
        return $this->jenisKelamin;
    }

    public function setJenisKelamin(string $jenisKelamin): void
    {
        $this->jenisKelamin = $jenisKelamin;
    }

    public function getParentId(): string
    {
        return $this->parentId;
    }

    public function setParentId(?string $parentId): void
    {
        if ($parentId) {
            $this->parentId = $parentId;
        }
    }
}
