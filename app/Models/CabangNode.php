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

    public static function getTree($rootId = null)
    {
        if ($rootId === null) {
            $roots = CabangNode::with('cabang', 'children')->whereNull('root_id')->get();
            if ($roots->count() == 0) {
                return null;
            }

            return $roots->map(function ($root) {
                return [
                    'id' => (string)$root->id,
                    'cabang_id' => $root->cabang_id,
                    'root_id' => $root->root_id,
                    'cabang' => $root->cabang,
                    'data' => [
                        'name' => $root->cabang->nama,
                    ],
                    'children' => $root->children->count() > 0 ? $root->children->map(function () use ($root) {
                        return CabangNode::getTree($root->id);
                    }) : null,
                ];
            });
        }
        $root = CabangNode::with('cabang', 'children')->where('root_id', $rootId)->first();
        if (!$root) {
            return null;
        }

        $result = [
            'id' => (string)$root->id,
            'cabang_id' => $root->cabang_id,
            'root_id' => $root->root_id,
            'cabang' => $root->cabang,
            'data' => [
                'name' => $root->cabang->nama,
            ],
            'children' => $root->children->count() > 0 ? $root->children->map(function () use ($root) {
                return CabangNode::getTree($root->id);
            }) : null,
        ];
        if ($result["children"] === null) {
            unset($result["children"]);
        }
        return $result;
    }
}
