<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CatalogController extends Controller
{
    public function publicIndex(Request $request): JsonResponse
    {
        $data = $request->validate([
            'kind' => ['nullable', Rule::in(['node', 'flowmap', 'workspace'])],
            'query' => ['nullable', 'string', 'max:120'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);
        $query = $this->query()->where('r.visibility', 'public');
        if (! empty($data['kind'])) $query->where('a.kind', $data['kind']);
        if (! empty($data['query'])) {
            $term = $data['query'];
            $query->where(function ($builder) use ($term): void {
                $builder->where('r.title', 'like', '%'.$term.'%')
                    ->orWhere('r.description', 'like', '%'.$term.'%')
                    ->orWhere('a.id', $term);
            });
        }
        $page = $query->select('r.artifact_id', 'a.kind', 'r.version', 'r.title', 'r.description', 'r.license', 'r.visibility', 'r.sha256', 'r.updated_at', 'u.name as publisher')
            ->orderByDesc('r.id')->paginate(20);
        return response()->json(['items' => array_map(fn ($row) => $this->item($row), $page->items()),
            'page' => $page->currentPage(), 'hasMore' => $page->hasMorePages(), 'total' => $page->total()]);
    }

    private function query()
    {
        return DB::table('catalog_releases as r')
            ->join('catalog_artifacts as a', 'a.id', '=', 'r.artifact_id')
            ->join('users as u', 'u.id', '=', 'a.user_id');
    }

    private function item(object $row): array
    {
        return ['artifactId' => $row->artifact_id, 'kind' => $row->kind,
            'version' => $row->version, 'title' => $row->title, 'description' => $row->description,
            'license' => $row->license, 'visibility' => $row->visibility,
            'publisher' => $row->publisher, 'sha256' => $row->sha256, 'updatedAt' => $row->updated_at];
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->validate(['kind' => ['required', Rule::in(['node', 'flowmap', 'workspace'])],
            'query' => ['nullable', 'string'], 'mine' => ['nullable', 'boolean'], 'page' => ['nullable', 'integer', 'min:1']]);
        $query = $this->query()->where('a.kind', $data['kind']);
        if ($request->boolean('mine')) {
            $query->where('a.user_id', $request->user()->id);
        } else {
            $query->where(function ($q) use ($request): void {
                $q->where('r.visibility', 'public')->orWhere('a.user_id', $request->user()->id);
            });
        }
        if (! empty($data['query'])) {
            $term = $data['query'];
            $query->where(function ($q) use ($term): void {
                $q->where('r.title', 'like', '%'.$term.'%')->orWhere('r.description', 'like', '%'.$term.'%')->orWhere('a.id', $term);
            });
        }
        $page = $query->select('r.artifact_id', 'a.kind', 'r.version', 'r.title', 'r.description', 'r.license', 'r.visibility', 'r.sha256', 'r.updated_at', 'u.name as publisher')
            ->orderByDesc('r.id')->paginate(20);

        return response()->json(['items' => array_map(fn ($row) => $this->item($row), $page->items()),
            'page' => $page->currentPage(), 'hasMore' => $page->hasMorePages(), 'total' => $page->total()]);
    }

    public function download(Request $request, string $artifact, string $version): JsonResponse
    {
        $row = $this->query()->where('a.id', $artifact)->where('r.version', $version)
            ->where(function ($q) use ($request): void {
                $q->whereIn('r.visibility', ['public', 'unlisted'])->orWhere('a.user_id', $request->user()->id);
            })->select('r.*', 'a.kind', 'u.name as publisher')->first();
        abort_unless($row, 404);

        return response()->json(['item' => $this->item($row), 'bundleJson' => $row->bundle_json]);
    }

    public function publish(Request $request): JsonResponse
    {
        $data = $request->validate([
            'artifactId' => ['nullable', 'uuid'], 'kind' => ['required', Rule::in(['node', 'flowmap', 'workspace'])],
            'version' => ['required', 'string', 'max:100', 'regex:/^[0-9]+\.[0-9]+\.[0-9]+(?:-[A-Za-z0-9.-]+)?$/'],
            'title' => ['required', 'string', 'max:255'], 'description' => ['present', 'nullable', 'string'],
            'license' => ['required', 'string', 'max:255'], 'visibility' => ['required', Rule::in(['private', 'unlisted', 'public'])],
            'bundleJson' => ['required', 'string', 'json'],
        ]);
        $bundle = json_decode($data['bundleJson'], true);
        if ($data['kind'] === 'node') {
            abort_unless(is_array($bundle) && ($bundle['schema'] ?? '') === 'tl-custom-node-marketplace/v1'
                && is_array($bundle['manifest'] ?? null) && is_string($bundle['archiveBase64'] ?? null)
                && preg_match('/^[a-f0-9]{64}$/i', $bundle['archiveSha256'] ?? ''), 422, 'Invalid Custom Node release.');
            $archive = base64_decode($bundle['archiveBase64'], true);
            abort_unless(is_string($archive) && str_starts_with($archive, "PK\x03\x04")
                && hash_equals(strtolower($bundle['archiveSha256']), hash('sha256', $archive)), 422, 'Custom Node archive checksum or ZIP signature is invalid.');
            $manifest = $bundle['manifest'];
            abort_unless(is_string($manifest['id'] ?? null) && is_string($manifest['version'] ?? null)
                && $manifest['version'] === $data['version'] && is_string($manifest['name'] ?? null), 422, 'Custom Node manifest identity is invalid.');
        } else {
            abort_unless(is_array($bundle) && ($bundle['schema'] ?? '') === 'tl-catalog-bundle/v1'
                && ($bundle['kind'] ?? '') === $data['kind'] && is_string($bundle['rootId'] ?? null)
                && is_array($bundle['records'] ?? null), 422, 'Invalid portable catalog bundle.');
            $stores = ['tl_pages', 'tl_widgets', 'tl_runtime_nodes', 'tl_runtime_dependencies', 'tl_flows', 'tl_channels', 'tl_connections', 'tl_ai_agents', 'tl_agents'];
            abort_if(array_diff(array_keys($bundle['records']), $stores), 422, 'Unsupported bundle store.');
            foreach ($stores as $store) {
                $rows = $bundle['records'][$store] ?? null;
                abort_unless(is_array($rows) && array_is_list($rows), 422, 'Missing bundle store.');
                $ids = [];
                foreach ($rows as $row) {
                    abort_unless(is_array($row) && is_string($row['id'] ?? null) && $row['id'] !== '' && ! isset($ids[$row['id']]), 422, 'Invalid bundle identity.');
                    $ids[$row['id']] = true;
                }
            }
            $root = collect($bundle['records']['tl_pages'])->firstWhere('id', $bundle['rootId']);
            abort_unless($root, 422, 'Missing root page.');
            $page = $root['content'] ?? $root;
            $isFlow = ($page['type'] ?? '') === 'flowmap' || ($page['kind'] ?? '') === 'flowmap' || ($page['format'] ?? '') === 'tlflow';
            abort_unless($isFlow === ($data['kind'] === 'flowmap'), 422, 'Bundle kind mismatch.');
        }
        $id = $data['artifactId'] ?? (string) Str::uuid();
        DB::transaction(function () use ($request, $data, $id): void {
            $artifact = DB::table('catalog_artifacts')->where('id', $id)->lockForUpdate()->first();
            if ($artifact) {
                abort_unless($artifact->user_id === $request->user()->id, 403);
                abort_unless($artifact->kind === $data['kind'], 422);
            } else {
                abort_if(! empty($data['artifactId']), 404);
                DB::table('catalog_artifacts')->insert(['id' => $id, 'kind' => $data['kind'], 'user_id' => $request->user()->id, 'created_at' => now(), 'updated_at' => now()]);
            }
            abort_if(DB::table('catalog_releases')->where('artifact_id', $id)->where('version', $data['version'])->exists(), 409, 'This version already exists. Publish a new version.');
            DB::table('catalog_releases')->insert(['artifact_id' => $id, 'version' => $data['version'],
                'title' => $data['title'], 'description' => $data['description'] ?? '', 'license' => $data['license'],
                'visibility' => $data['visibility'], 'sha256' => hash('sha256', $data['bundleJson']),
                'bundle_json' => $data['bundleJson'], 'created_at' => now(), 'updated_at' => now()]);
        });

        return response()->json(['artifactId' => $id, 'version' => $data['version'], 'sha256' => hash('sha256', $data['bundleJson'])], 201);
    }
}
