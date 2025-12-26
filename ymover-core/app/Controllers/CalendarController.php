<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\CalendarEvent;
use App\Models\Resource;
use App\Models\Request;

class CalendarController
{
    private CalendarEvent $eventModel;
    private Resource $resourceModel;
    private Request $requestModel;

    public function __construct()
    {
        $this->eventModel = new CalendarEvent();
        $this->resourceModel = new Resource();
        $this->requestModel = new Request();
    }

    public function index(): void
    {
        $resources = $this->resourceModel->getAllByTenant($_SESSION['tenant_id']);
        View::render('calendar/index', ['resources' => $resources]);
    }

    public function getEvents(): void
    {
        $events = $this->eventModel->getByTenant($_SESSION['tenant_id']);
        
        $formatted = array_map(function($e) {
            return [
                'id' => $e['id'],
                'title' => $e['title'],
                'start' => $e['start_datetime'],
                'end' => $e['end_datetime'],
                'color' => $e['type'] === 'job' ? '#0d6efd' : ($e['type'] === 'inspection' ? '#ffc107' : '#6c757d'),
                'extendedProps' => [
                    'type' => $e['type'],
                    'request_id' => $e['request_id']
                ]
            ];
        }, $events);

        header('Content-Type: application/json');
        echo json_encode($formatted);
        exit;
    }

    public function store(): void
    {
        $data = $_POST;
        $data['tenant_id'] = $_SESSION['tenant_id'];
        
        // If it's a job from a request, fetch request info for title if not provided
        if (isset($data['request_id']) && empty($data['title'])) {
            $request = $this->requestModel->findById((int)$data['request_id']);
            $data['title'] = "Lavoro #" . $data['request_id'];
        }

        $this->eventModel->create($data);
        
        if (isset($data['request_id'])) {
            header("Location: /requests/show?id=" . $data['request_id']);
        } else {
            header("Location: /calendar");
        }
        exit;
    }

    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $this->eventModel->delete($id);
        
        header("Location: /calendar");
        exit;
    }
}
