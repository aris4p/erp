<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeDocumentRequest;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeDocumentController extends Controller
{
    public function create(Employee $employee): View
    {
        return view('employees.documents.create', compact('employee'));
    }

    public function store(StoreEmployeeDocumentRequest $request, Employee $employee): RedirectResponse
    {
        $data = $request->safe()->except('file');
        $data['file_path'] = $request->file('file')->store('employee-documents');

        $employee->documents()->create($data);

        return redirect()->route('employees.show', $employee)->with('success', 'Dokumen karyawan berhasil diunggah.');
    }

    public function download(EmployeeDocument $document): StreamedResponse
    {
        abort_unless(Storage::exists($document->file_path), 404);

        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);

        return Storage::download($document->file_path, $document->type.'-'.$document->id.'.'.$extension);
    }

    public function destroy(EmployeeDocument $document): RedirectResponse
    {
        Storage::delete($document->file_path);
        $employee = $document->employee;
        $document->delete();

        return redirect()->route('employees.show', $employee)->with('success', 'Dokumen karyawan berhasil dihapus.');
    }
}
