<?php



namespace App\Models\Entities\Goods;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class GoodsByDocument extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<Goods>
     */
    public function goods(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }

    public static function store(array $data): void
    {
        $dataArray = json_decode('[' . $data['data'] . ']', true);
        $documentId = $data['document_id'];

        $existingGoodsIds = self::where('document_id', $documentId)
            ->pluck('goods_id')
            ->toArray();

        $insertArray = [];
        foreach ($dataArray as &$item) {
            $skuID = $item['sku_id'];
            $count = $item['count'];
            $item = array_diff_key($item, ['sku_id' => '', 'count' => '']);

            $insertArray[] = [
                'goods_id' => $skuID,
                'document_id' => $documentId,
                'count' => $count,
                'data' => json_encode($item),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        $seen = [];
        $insertArray = array_values(array_filter($insertArray, function ($entry) use (&$seen, $existingGoodsIds) {
            $key = $entry['document_id'] . '|' . $entry['goods_id'];
            if (isset($seen[$key]) || in_array($entry['goods_id'], $existingGoodsIds)) {
                return false;
            }
            $seen[$key] = true;
            return true;
        }));

        if (empty($insertArray)) {
            return;
        }

        GoodsByDocument::insert($insertArray);
    }

    public static function storeFromTable($data, $documentID)
    {
        $skuID = $data['sku_id'];
        $count = $data['count'];

        if (self::where('document_id', $documentID)->where('goods_id', $skuID)->exists()) {
            abort(422, 'Цей товар вже додано до документу');
        }

        //delete keys
        $data = array_diff_key($data, ['sku_id' => '', 'count' => '', 'sku_category' => '']);

        $skuByDoc = GoodsByDocument::create([
            'goods_id' => $skuID,
            'document_id' => $documentID,
            'count' => $count,
            'data' => json_encode($data)
        ]);
        return $skuByDoc->id;
    }
}
