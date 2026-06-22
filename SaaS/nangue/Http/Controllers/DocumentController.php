<?php

namespace Nangue\Http\Controllers;

use App\Http\Controllers\Controller;
use Nangue\Models\Document;
use Nangue\Models\DocumentCategory;
use Inertia\Inertia;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with(['category', 'documentable'])
            ->where('user_id', auth()->id())
            ->latest()->paginate(15);

        $categories = DocumentCategory::all();

        return Inertia::render('Nangue/Landlord/Documents/Index', [
            'documents' => $documents,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file_path' => 'required|string',
            'file_type' => 'nullable|string',
            'file_size' => 'nullable|integer',
            'document_category_id' => 'nullable|exists:document_categories,id',
            'documentable_type' => 'required|string',
            'documentable_id' => 'required|integer',
            'expires_at' => 'nullable|date',
        ]);

        $validated['user_id'] = auth()->id();

        Document::create($validated);

        return redirect()->back()->with('success', 'Document ajouté.');
    }

    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'expires_at' => 'nullable|date',
            'verified' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        if (isset($validated['verified'])) {
            $validated['verified_by'] = auth()->id();
            $validated['verified_at'] = now();
        }

        $document->update($validated);

        return redirect()->back()->with('success', 'Document mis à jour.');
    }

    public function destroy(Document $document)
    {
        $document->delete();

        return redirect()->back()->with('success', 'Document supprimé.');
    }
}
