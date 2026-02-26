<?php

namespace App\Models\Entities\Container;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class ContainerByDocument extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function store(array $data): void
    {

        $dataArray = json_decode('[' . $data['data'] . ']', true);

        $insertArray = [];

        foreach ($dataArray as &$item) {
            $containerID = $item['container_id'];
            $count = $item['count'];
            $item = array_diff_key($item, ['container_id' => '', 'count' => '']);

            $insertArray[] = [
                'container_id' => $containerID,
                'document_id' => $data['document_id'],
                'count' => $count,
                'data' => json_encode($item),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        parent::insert($insertArray);
    }

    public static function storeFromTable($data, $documentID)
    {
        $containerID = $data['container_id'];
        $count = $data['count'];

        //delete keys
        $data = array_diff_key($data, ['container_id' => '', 'count' => '', 'container_category' => '']);

        $containerByDoc = parent::create([
            'container_id' => $containerID,
            'document_id' => $documentID,
            'count' => $count,
            'data' => json_encode($data)
        ]);
        return $containerByDoc->id;
    }
}
