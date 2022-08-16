<?php

namespace Imams\SilsilahKeluargaApi\Repository;

use Imams\SilsilahKeluargaApi\Repository\AnggotaKeluargaRepositoryInterface;
use Imams\SilsilahKeluargaApi\Entity\AnggotaKeluarga;

class AnggotaKeluargaRepository implements AnggotaKeluargaRepositoryInterface
{
    public array $anggotaKeluarga = [];

    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(AnggotaKeluarga $anggotaKeluarga): void
    {
        $sql = "INSERT INTO anggota_keluarga(nama, jenis_kelamin, parent_id) VALUES (?,?,?)";
        $statement = $this->connection->prepare($sql);
        $statement->execute([
            $anggotaKeluarga->getNama(),
            $anggotaKeluarga->getJenisKelamin(),
            $anggotaKeluarga->getParentId(),
        ]);
    }

    public function getById(int $anggotaKeluargaId)
    {
        $sql = <<<SQL
        SELECT id, nama, jenis_kelamin, parent_id FROM anggota_keluarga
        WHERE id = ?
        SQL;
        $statement = $this->connection->prepare($sql);
        $statement->execute([$anggotaKeluargaId]);
        
        if ($row = $statement->fetch()) {
            $anggotaKeluarga = new AnggotaKeluarga();
            $anggotaKeluarga->setId($row['id']);
            $anggotaKeluarga->setNama($row['nama']);
            $anggotaKeluarga->setJenisKelamin($row['jenis_kelamin']);
            $anggotaKeluarga->setParentId($row['parent_id']);

            return $anggotaKeluarga;
        } else {
            return false;
        }
    }

    public function update(AnggotaKeluarga $anggotaKeluarga): bool
    {
        $sql = <<<SQL
        SELECT id FROM anggota_keluarga
        WHERE id = ? 
        SQL;
        $statement = $this->connection->prepare($sql);
        $statement->execute([$anggotaKeluarga->getId()]);
        
        if ($statement->fetch()) {
            $sql = <<<SQL
            UPDATE anggota_keluarga SET nama = ?, jenis_kelamin = ?, parent_id = ?
            WHERE id = ? 
            SQL;
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                $anggotaKeluarga->getNama(),
                $anggotaKeluarga->getJenisKelamin(),
                $anggotaKeluarga->getParentId(),
                $anggotaKeluarga->getId(),
            ]);

            return true;
        } else {
            return false;
        }
    }

    public function remove(int $anggotaKeluargaId): bool
    {
        $sql = <<<SQL
        SELECT id FROM anggota_keluarga
        WHERE id = ?
        SQL;
        $statement = $this->connection->prepare($sql);
        $statement->execute([$anggotaKeluargaId]);
        
        if ($statement->fetch()) {
            $sql = <<<SQL
            DELETE FROM anggota_keluarga
            WHERE id = ?
            SQL;
            $statement = $this->connection->prepare($sql);
            $statement->execute([$anggotaKeluargaId]);

            return true;
        } else {
            return false;
        }
    }

    public function findAll(): array
    {
        $sql = "SELECT id, nama, jenis_kelamin, parent_id FROM anggota_keluarga";
        $statement = $this->connection->prepare($sql);
        $statement->execute();

        $result = array();

        foreach ($statement as $row) {
            $anggotaKeluarga = new AnggotaKeluarga();
            $anggotaKeluarga->setId($row['id']);
            $anggotaKeluarga->setNama($row['nama']);
            $anggotaKeluarga->setJenisKelamin($row['jenis_kelamin']);
            $anggotaKeluarga->setParentId($row['parent_id']);
            
            $result[] = $anggotaKeluarga;
        }

        return $result;
    }

    public function findChildren(int $parentId): array
    {
        $sql = <<<SQL
        SELECT id, nama, jenis_kelamin, parent_id 
        FROM anggota_keluarga
        WHERE parent_id = ?
        SQL;
        $statement = $this->connection->prepare($sql);
        $statement->execute([$parentId]);

        $result = array();

        foreach ($statement as $row) {
            $anggotaKeluarga = new AnggotaKeluarga();
            $anggotaKeluarga->setId($row['id']);
            $anggotaKeluarga->setNama($row['nama']);
            $anggotaKeluarga->setJenisKelamin($row['jenis_kelamin']);
            $anggotaKeluarga->setParentId($row['parent_id']);
            
            $result[] = $anggotaKeluarga;
        }

        return $result;
    }

    public function findGrandChildren(int $grandParentId, ?string $jenisKelaminGrandChild): array
    {
        $sql = <<<SQL
        SELECT ak1.id, ak1.nama, ak1.jenis_kelamin, ak1.parent_id 
        FROM anggota_keluarga ak1
        INNER JOIN anggota_keluarga ak2
        ON ak1.parent_id = ak2.id
        WHERE ak2.parent_id = ?
        SQL;
        $params = [$grandParentId];

        if ($jenisKelaminGrandChild) {
            $sql = $sql.' AND ak1.jenis_kelamin = ?';
            $params[] = $jenisKelaminGrandChild;
        }

        $statement = $this->connection->prepare($sql);
        $statement->execute($params);

        $result = array();

        foreach ($statement as $row) {
            $anggotaKeluarga = new AnggotaKeluarga();
            $anggotaKeluarga->setId($row['id']);
            $anggotaKeluarga->setNama($row['nama']);
            $anggotaKeluarga->setJenisKelamin($row['jenis_kelamin']);
            $anggotaKeluarga->setParentId($row['parent_id']);
            
            $result[] = $anggotaKeluarga;
        }

        return $result;
    }

    public function findAunt(int $anggotaKeluargaId): array
    {
        $sql = <<<SQL
        SELECT bibi.id, bibi.nama, bibi.jenis_kelamin, bibi.parent_id 
        FROM anggota_keluarga bibi
        WHERE bibi.parent_id = (
            SELECT ortu.parent_id 
            FROM anggota_keluarga anak
            INNER JOIN anggota_keluarga ortu
            ON anak.parent_id = ortu.id
            INNER JOIN anggota_keluarga kakek
            ON ortu.parent_id = kakek.id
            WHERE anak.id = ?
        )
        AND bibi.jenis_kelamin = ?
        AND bibi.id <> (
            SELECT parent_id
            FROM anggota_keluarga
            WHERE id = ?
        )
        SQL;
        
        $statement = $this->connection->prepare($sql);
        $statement->execute([$anggotaKeluargaId, "Perempuan", $anggotaKeluargaId]);

        $result = array();

        foreach ($statement as $row) {
            $anggotaKeluarga = new AnggotaKeluarga();
            $anggotaKeluarga->setId($row['id']);
            $anggotaKeluarga->setNama($row['nama']);
            $anggotaKeluarga->setJenisKelamin($row['jenis_kelamin']);
            $anggotaKeluarga->setParentId($row['parent_id']);
            
            $result[] = $anggotaKeluarga;
        }

        return $result;
    }

    public function findCousin(int $anggotaKeluargaId, ?string $jenisKelaminCousin): array
    {
        $sql = <<<SQL
        SELECT anak.id, anak.nama, anak.jenis_kelamin, anak.parent_id 
        FROM anggota_keluarga anak
        WHERE anak.parent_id IN (
            SELECT id
            FROM anggota_keluarga
            WHERE parent_id = (
                SELECT kakek.id
                FROM anggota_keluarga anak
                INNER JOIN anggota_keluarga ortu
                ON anak.parent_id = ortu.id
                INNER JOIN anggota_keluarga kakek
                ON ortu.parent_id = kakek.id
                WHERE anak.id = ?
            )
            AND id <> (
                SELECT parent_id
                FROM anggota_keluarga
                WHERE id = ?
            )
        )
        SQL;
        $params = [$anggotaKeluargaId, $anggotaKeluargaId];

        if ($jenisKelaminCousin) {
            $sql = $sql.' AND anak.jenis_kelamin = ?';
            $params[] = $jenisKelaminCousin;
        }

        $statement = $this->connection->prepare($sql);
        $statement->execute($params);

        $result = array();

        foreach ($statement as $row) {
            $anggotaKeluarga = new AnggotaKeluarga();
            $anggotaKeluarga->setId($row['id']);
            $anggotaKeluarga->setNama($row['nama']);
            $anggotaKeluarga->setJenisKelamin($row['jenis_kelamin']);
            $anggotaKeluarga->setParentId($row['parent_id']);
            
            $result[] = $anggotaKeluarga;
        }

        return $result;
    }
}
