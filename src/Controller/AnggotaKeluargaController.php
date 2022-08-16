<?php

namespace Imams\SilsilahKeluargaApi\Controller;

use Imams\SilsilahKeluargaApi\Config\Database;
use Imams\SilsilahKeluargaApi\Repository\AnggotaKeluargaRepository;
use Imams\SilsilahKeluargaApi\Service\AnggotaKeluargaService;

class AnggotaKeluargaController
{
    private $anggotaKeluargaService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $anggotaKeluargaRepository = new AnggotaKeluargaRepository($connection);
        $this->anggotaKeluargaService = new AnggotaKeluargaService($anggotaKeluargaRepository);
    }

    public function index()
    {
        $data = $this->anggotaKeluargaService->showAnggotaKeluargaList();

        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function store()
    {
        $request = [
            'nama' => $_POST['nama'],
            'jenis_kelamin' => $_POST['jenis_kelamin'],
            'parent_id' => $_POST['parent_id'] ?? null
        ];

        $data = $this->anggotaKeluargaService->addAnggotaKeluarga($request);

        echo json_encode([
            'message' => 'Sukses input data anggota keluarga',
            'data' => $data
        ], JSON_PRETTY_PRINT);
    }

    public function show(int $anggotaKeluargaId)
    {
        try {
            $data = $this->anggotaKeluargaService->showAnggotaKeluarga($anggotaKeluargaId);

            echo json_encode($data, JSON_PRETTY_PRINT);
        } catch(\Exception $e) {
            http_response_code(404);

            echo json_encode([
                'code' => '404',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(int $anggotaKeluargaId)
    {
        try {
            $request = [
                'id' => $anggotaKeluargaId,
                'nama' => $_POST['nama'],
                'jenis_kelamin' => $_POST['jenis_kelamin'],
                'parent_id' => $_POST['parent_id'] ?? null
            ];
            $data = $this->anggotaKeluargaService->updateAnggotaKeluarga($request);

            echo json_encode($data, JSON_PRETTY_PRINT);
        } catch(\Exception $e) {
            http_response_code(404);

            echo json_encode([
                'code' => '404',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy(int $anggotaKeluargaId)
    {
        try {
            $data = $this->anggotaKeluargaService->removeAnggotaKeluarga($anggotaKeluargaId);

            echo json_encode([
                'message' => 'Sukses hapus data anggota keluarga'
            ], JSON_PRETTY_PRINT);
        } catch(\Exception $e) {
            http_response_code(404);

            echo json_encode([
                'code' => '404',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function showChildrenList(int $anggotaKeluargaId)
    {
        $data = $this->anggotaKeluargaService->showAnggotaKeluargaChildren($anggotaKeluargaId);

        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function showGrandChildrenList(int $anggotaKeluargaId)
    {
        $data = $this->anggotaKeluargaService->showAnggotaKeluargaGrandChildren($anggotaKeluargaId, null);

        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function showGrandChildrenListByGender(int $anggotaKeluargaId, $jenisKelaminGrandChild)
    {
        $data = $this->anggotaKeluargaService->showAnggotaKeluargaGrandChildren($anggotaKeluargaId, ucfirst($jenisKelaminGrandChild));

        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function showAuntList(int $anggotaKeluargaId)
    {
        $data = $this->anggotaKeluargaService->showAnggotaKeluargaAunt($anggotaKeluargaId);

        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function showCousinList(int $anggotaKeluargaId, string $jenisKelaminCousin)
    {
        $data = $this->anggotaKeluargaService->showAnggotaKeluargaCousin($anggotaKeluargaId, ucfirst($jenisKelaminCousin));

        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}