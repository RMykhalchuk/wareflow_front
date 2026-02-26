<?php

namespace App\Models\Entities\Document;

use App\Models\Dictionaries\DoctypeStatus;
use App\Traits\CompanySeparation;
use App\Traits\DocumentTypeDataTrait;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class DocumentType extends Model
{
    use DocumentTypeDataTrait;
    use SoftDeletes;
    use HasUuid;
    use HasLocalId;
    // Поки не юзаємо
    //use CompanySeparationDisable;
    use CompanySeparation;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = [];

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<DoctypeStatus>
     */
    public function status(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DoctypeStatus::class, 'id', 'status_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasMany<Document>
     */
    public function documents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Document::class, 'type_id', 'id');
    }

    public function settings()
    {
        return json_decode($this->settings, true);
    }

    public function getDictionaries(): array
    {
        $selectValuesArray = $this->getDictionaryArray($this->settings()['fields']);
        if (array_key_exists('document_type', $this->settings())) {
            $documents = DocumentType::whereIn('id', $this->settings()['document_type'])->get();
            foreach ($documents as $document) {
                $selectValuesArray = array_merge(
                    $selectValuesArray,
                    $this->getDictionaryArray($document->settings()['fields'])
                );
            }
        }
        if (array_key_exists('custom_blocks', $this->settings())) {
            $selectValuesArray = array_merge(
                $selectValuesArray,
                $this->getDictionaryArray($this->settings()['custom_blocks'])
            );
        }

        return $selectValuesArray;
    }

    public function getRelatedDocumentsArray(): array
    {
        if (array_key_exists('document_type', $this->settings())) {
            return DocumentType::find($this->settings()['document_type'])
                ->toArray();
        }
        return [];
    }

    private function getDictionaryArray($fields): array
    {
        $selectValuesArray = [];
        foreach ($fields as $array) {
            foreach ($array as $value) {
                if ($value['type'] === 'select' || $value['type'] === 'label') {
                    if (!array_key_exists($value['directory'], $selectValuesArray)) {
                    }
                    $dictionary = call_user_func('App\Factories\DictionaryFactory' . '::' . $value['directory'], false);
                    if (!is_array($dictionary)) {
                        $selectValuesArray[$value['directory']] = $dictionary ? $dictionary->take(10)->get()->toArray() : null;
                    } else {
                        $selectValuesArray[$value['directory']] = $dictionary;
                    }
                }
            }
        }
        return $selectValuesArray;
    }
}
