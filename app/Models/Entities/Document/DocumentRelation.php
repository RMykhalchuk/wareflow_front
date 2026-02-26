<?php

namespace App\Models\Entities\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class DocumentRelation extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;
    public static function storeByArray(array $dataArray): void
    {
        $relatedDocuments = json_decode($dataArray['related_documents']);
        for ($i = 0;$i < count($relatedDocuments);$i++) {
            parent::firstOrCreate([
                'document_id' => $dataArray['document_id'],
                'related_id' => $relatedDocuments[$i]
            ]);
        }
    }

}
