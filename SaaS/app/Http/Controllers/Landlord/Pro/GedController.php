<?php

namespace App\Http\Controllers\Landlord\Pro;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentFolder;
use App\Models\DocumentShare;
use App\Models\DocumentTemplate;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class GedController extends Controller
{
    // ── Explorateur ──

    public function index(Request $request)
    {
        $userId = auth()->id();
        $folderId = $request->input('folder_id');

        $folders = DocumentFolder::where('user_id', $userId)
            ->whereNull('parent_id')
            ->withCount(['children', 'documents'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $currentFolder = null;
        $breadcrumbs = [];

        if ($folderId) {
            $currentFolder = DocumentFolder::where('user_id', $userId)->findOrFail($folderId);
            $breadcrumbs = $this->getBreadcrumbs($currentFolder);
        }

        $currentChildren = DocumentFolder::where('user_id', $userId)
            ->where('parent_id', $folderId)
            ->withCount('documents')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $query = Document::where('user_id', $userId);
        if ($folderId) {
            $query->where('folder_id', $folderId);
        } else {
            $query->whereNull('folder_id');
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('tags', 'like', "%{$s}%")
                  ->orWhere('notes', 'like', "%{$s}%");
            });
        }
        if ($request->boolean('starred')) {
            $query->where('is_starred', true);
        }
        if ($request->filled('type')) {
            $query->where('file_type', $request->type);
        }

        $documents = $query->with(['versions' => fn($q) => $q->take(1)])
            ->orderBy('is_starred', 'desc')
            ->latest()
            ->paginate(30);

        $starredCount = Document::where('user_id', $userId)->where('is_starred', true)->count();

        return Inertia::render('Landlord/Pro/Ged/Index', [
            'folders' => $folders,
            'current_folder' => $currentFolder,
            'breadcrumbs' => $breadcrumbs,
            'current_children' => $currentChildren,
            'documents' => $documents,
            'filters' => $request->only(['search', 'starred', 'type', 'folder_id']),
            'starred_count' => $starredCount,
            'tenants' => Tenant::whereHas('contracts.property', fn($q) => $q->where('user_id', $userId))
                ->orderBy('name')->get(['id', 'name', 'email']),
        ]);
    }

    // ── Dossiers ──

    public function storeFolder(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:document_folders,id',
            'color' => 'nullable|string|max:7',
        ]);

        $data['user_id'] = auth()->id();
        $data['color'] = $data['color'] ?? '#6366f1';

        $folder = DocumentFolder::create($data);

        return redirect()->back()->with('success', "Dossier '{$folder->name}' créé.");
    }

    public function updateFolder(Request $request, DocumentFolder $documentFolder)
    {
        if ($documentFolder->user_id !== auth()->id()) abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ]);

        $documentFolder->update($data);

        return redirect()->back()->with('success', 'Dossier renommé.');
    }

    public function destroyFolder(DocumentFolder $documentFolder)
    {
        if ($documentFolder->user_id !== auth()->id()) abort(403);

        Document::where('folder_id', $documentFolder->id)->update(['folder_id' => $documentFolder->parent_id]);
        $documentFolder->delete();

        return redirect()->route('landlord.pro.ged.index', [
            'folder_id' => $documentFolder->parent_id,
        ])->with('success', 'Dossier supprimé.');
    }

    // ── Upload ──

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:51200',
            'folder_id' => 'nullable|exists:document_folders,id',
            'name' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $name = $request->input('name', pathinfo($originalName, PATHINFO_FILENAME));
        $extension = $file->getClientOriginalExtension();

        $path = $file->store('documents/' . auth()->id(), 'public');

        Document::create([
            'user_id' => auth()->id(),
            'name' => $name,
            'file_path' => $path,
            'file_type' => strtolower($extension),
            'file_size' => $file->getSize(),
            'folder_id' => $request->folder_id,
        ]);

        return redirect()->back()->with('success', "Document '{$name}' uploadé.");
    }

    // ── Actions documents ──

    public function toggleStar(Document $document)
    {
        if ($document->user_id !== auth()->id()) abort(403);
        $document->update(['is_starred' => !$document->is_starred]);

        return redirect()->back();
    }

    public function updateDocument(Request $request, Document $document)
    {
        if ($document->user_id !== auth()->id()) abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'tags' => 'nullable|string|max:500',
            'expires_at' => 'nullable|date',
            'notes' => 'nullable|string|max:2000',
        ]);

        $document->update($data);

        return redirect()->back()->with('success', 'Document mis à jour.');
    }

    public function moveDocument(Request $request, Document $document)
    {
        if ($document->user_id !== auth()->id()) abort(403);

        $request->validate(['folder_id' => 'nullable|exists:document_folders,id']);
        $document->update(['folder_id' => $request->folder_id]);

        return redirect()->back()->with('success', 'Document déplacé.');
    }

    public function destroyDocument(Document $document)
    {
        if ($document->user_id !== auth()->id()) abort(403);

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->back()->with('success', 'Document supprimé.');
    }

    // ── Partage ──

    public function shareDocument(Request $request, Document $document)
    {
        if ($document->user_id !== auth()->id()) abort(403);

        $data = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'permission' => 'required|in:view,download',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $share = DocumentShare::create([
            'document_id' => $document->id,
            'tenant_id' => $data['tenant_id'],
            'permission' => $data['permission'],
            'token' => Str::random(64),
            'expires_at' => $data['expires_at'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Document partagé avec le locataire.');
    }

    public function revokeShare(DocumentShare $documentShare)
    {
        $document = $documentShare->document;
        if ($document->user_id !== auth()->id()) abort(403);

        $documentShare->delete();

        return redirect()->back()->with('success', 'Partage révoqué.');
    }

    // ── Versions ──

    public function uploadVersion(Request $request, Document $document)
    {
        if ($document->user_id !== auth()->id()) abort(403);

        $request->validate(['file' => 'required|file|max:51200']);

        $file = $request->file('file');
        $path = $file->store('documents/' . auth()->id(), 'public');

        $lastVersion = $document->versions()->max('version_number') ?? 0;

        DocumentVersion::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'version_number' => $lastVersion + 1,
        ]);

        return redirect()->back()->with('success', 'Nouvelle version ajoutée.');
    }

    // ── Templates ──

    public function templates()
    {
        $templates = DocumentTemplate::where('user_id', auth()->id())
            ->orWhere('is_built_in', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('Landlord/Pro/Ged/Templates', [
            'templates' => $templates,
            'types' => DocumentTemplate::TYPES,
        ]);
    }

    public function storeTemplate(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:' . implode(',', array_keys(DocumentTemplate::TYPES)),
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7',
        ]);

        $data['user_id'] = auth()->id();
        $data['color'] = $data['color'] ?? '#6366f1';
        $data['content'] = [];

        DocumentTemplate::create($data);

        return redirect()->route('landlord.pro.ged.templates')
            ->with('success', 'Template créé.');
    }

    public function destroyTemplate(DocumentTemplate $documentTemplate)
    {
        if ($documentTemplate->user_id !== auth()->id()) abort(403);
        $documentTemplate->delete();

        return redirect()->route('landlord.pro.ged.templates')
            ->with('success', 'Template supprimé.');
    }

    // ── Helpers ──

    protected function getBreadcrumbs(DocumentFolder $folder): array
    {
        $crumbs = [];
        $current = $folder;

        while ($current) {
            array_unshift($crumbs, [
                'id' => $current->id,
                'name' => $current->name,
            ]);
            $current = $current->parent;
        }

        return $crumbs;
    }
}
