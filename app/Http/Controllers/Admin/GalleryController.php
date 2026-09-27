<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GalleryRequest;
use App\Models\Gallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $galleryType = $request->query('gallery_type');
        $status = $request->query('status');

        $galleries = Gallery::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('alt_text', 'like', "%{$search}%")
                        ->orWhere('caption', 'like', "%{$search}%");
                });
            })
            ->when(filled($galleryType), fn ($query) => $query->where('gallery_type', $galleryType))
            ->when(in_array($status, ['published', 'unpublished'], true), function ($query) use ($status): void {
                $query->where('is_published', $status === 'published');
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.galleries.index', compact('galleries', 'search', 'galleryType', 'status'));
    }

    public function create(): View
    {
        return view('admin.galleries.create', ['gallery' => new Gallery]);
    }

    public function store(GalleryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->storeGalleryImage($request);
        }

        $gallery = Gallery::query()->create($data);

        return redirect()->route('admin.galleries.index')->with('success', 'Galeri berhasil ditambahkan.');
    }

    public function edit(Gallery $gallery): View
    {
        return view('admin.galleries.edit', compact('gallery'));
    }

    public function update(GalleryRequest $request, Gallery $gallery): RedirectResponse
    {
        $gallery->update($request->validated());

        $this->handleImageUpload($request, $gallery);

        return redirect()->route('admin.galleries.index')->with('success', 'Galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return redirect()->route('admin.galleries.index')->with('success', 'Galeri berhasil dihapus.');
    }

    protected function handleImageUpload(Request $request, Gallery $gallery): void
    {
        if (! $request->hasFile('image')) {
            return;
        }

        $gallery->update([
            'image_path' => $this->storeGalleryImage($request, $gallery),
        ]);
    }

    protected function storeGalleryImage(Request $request, ?Gallery $gallery = null): string
    {
        $file = $request->file('image');
        $safeName = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('galleries', $safeName, 'public');

        if ($gallery && $gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        return $path;
    }
}
