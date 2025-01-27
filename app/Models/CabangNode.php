<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabangNode extends Model
{
    use HasFactory;

    protected $fillable = [
        'cabang_id',
        'root_id',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function root()
    {
        return $this->belongsTo(CabangNode::class);
    }

    public function children()
    {
        return $this->hasMany(CabangNode::class, 'root_id');
    }

    public static function getTree($level = 0, $rootId = null)
    {
        $roots = CabangNode::with('cabang', 'children')->where('root_id', $rootId)->get();
        if ($roots->count() == 0) {
            return null;
        }

        $result = $roots->map(function ($root) use ($level) {
            $result = [
                'id' => (string)$root->id,
                'cabang_id' => $root->cabang_id,
                'root_id' => $root->root_id,
                'cabang' => $root->cabang,
                'data' => [
                    'node_id' => $root->id,
                    'name' => $root->cabang->nama,
                    'collapsed' => true,
                    'level' => $level + 1,
                ],
                'children' => CabangNode::getTree($level + 1, $root->id),
            ];
            if ($result["children"] == null) {
                unset($result["children"]);
            }
            return $result;
        });
        return $result;
    }
}
