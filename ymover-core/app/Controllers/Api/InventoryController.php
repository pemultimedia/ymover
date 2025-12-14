<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Models\InventoryVersion;
use App\Models\InventoryBlock;
use App\Models\InventoryItem;

class InventoryController
{
    private InventoryVersion $versionModel;
    private InventoryBlock $blockModel;
    private InventoryItem $itemModel;

    public function __construct()
    {
        $this->versionModel = new InventoryVersion();
        $this->blockModel = new InventoryBlock();
        $this->itemModel = new InventoryItem();
    }

    private function jsonResponse(array $data, int $code = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode($data);
        exit;
    }

    public function getInventory(): void
    {
        $requestId = (int)($_GET['request_id'] ?? 0);
        if (!$requestId) {
            $this->jsonResponse(['error' => 'Missing request_id'], 400);
        }

        $versions = $this->versionModel->getByRequestId($requestId);
        
        $tree = [];
        foreach ($versions as $ver) {
            $blocks = $this->blockModel->getByVersionId((int)$ver['id']);
            $blocksTree = [];
            
            $totalVolume = 0;
            
            foreach ($blocks as $block) {
                $items = $this->itemModel->getByBlockId((int)$block['id']);
                $blocksTree[] = [
                    'id' => $block['id'],
                    'name' => $block['name'],
                    'items' => $items
                ];
                
                foreach ($items as $item) {
                    $totalVolume += (float)$item['volume_m3'];
                }
            }
            
            $tree[] = [
                'id' => $ver['id'],
                'name' => $ver['name'],
                'is_selected' => (bool)$ver['is_selected'],
                'total_volume' => $totalVolume,
                'blocks' => $blocksTree
            ];
        }

        $this->jsonResponse(['versions' => $tree]);
    }

    public function createVersion(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $requestId = $data['request_id'] ?? 0;
        $name = $data['name'] ?? 'Nuova Versione';
        
        if (!$requestId) {
            $this->jsonResponse(['error' => 'Missing request_id'], 400);
        }
        
        $id = $this->versionModel->create([
            'request_id' => $requestId,
            'name' => $name,
            'is_selected' => false
        ]);
        
        // Create default block
        $this->blockModel->create([
            'version_id' => $id,
            'name' => 'Generico'
        ]);
        
        $this->jsonResponse(['success' => true, 'id' => $id]);
    }

    public function createBlock(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $versionId = $data['version_id'] ?? 0;
        $name = $data['name'] ?? 'Nuovo Blocco';
        
        if (!$versionId) {
            $this->jsonResponse(['error' => 'Missing version_id'], 400);
        }
        
        $id = $this->blockModel->create([
            'version_id' => $versionId,
            'name' => $name
        ]);
        
        $this->jsonResponse(['success' => true, 'id' => $id]);
    }

    public function addItem(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $blockId = $data['block_id'] ?? 0;
        
        if (!$blockId) {
            $this->jsonResponse(['error' => 'Missing block_id'], 400);
        }
        
        // Calculate volume if dimensions provided
        $width = (int)($data['width'] ?? 0);
        $height = (int)($data['height'] ?? 0);
        $depth = (int)($data['depth'] ?? 0);
        
        $volume = 0;
        if ($width && $height && $depth) {
            $volume = ($width * $height * $depth) / 1000000; // cm3 to m3
        } else {
            $volume = (float)($data['volume_m3'] ?? 0);
        }
        
        $data['volume_m3'] = $volume;
        
        $id = $this->itemModel->create($data);
        
        $this->jsonResponse(['success' => true, 'id' => $id]);
    }

    public function removeItem(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? 0;
        
        if (!$id) {
            $this->jsonResponse(['error' => 'Missing id'], 400);
        }
        
        $this->itemModel->delete((int)$id);
        
        $this->jsonResponse(['success' => true]);
    }
}
