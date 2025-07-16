<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\Subject;
    use Illuminate\Http\Request;

    class SubjectController extends Controller
    {
        public function index()
        {
            $subjects = Subject::latest()->paginate(10);
            return view('admin.subjects.index', compact('subjects'));
        }

        public function create()
        {
            return view('admin.subjects.create');
        }

        public function store(Request $request)
        {
            $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:subjects'],
                'code' => ['required', 'string', 'max:50', 'unique:subjects'],
            ]);

            Subject::create($request->all());

            return redirect()->route('admin.subjects.index')->with('success', 'Mata pelajaran baru berhasil ditambahkan.');
        }

        public function edit(Subject $subject)
        {
            return view('admin.subjects.edit', compact('subject'));
        }

        public function update(Request $request, Subject $subject)
        {
            $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:subjects,name,' . $subject->id],
                'code' => ['required', 'string', 'max:50', 'unique:subjects,code,' . $subject->id],
            ]);

            $subject->update($request->all());

            return redirect()->route('admin.subjects.index')->with('success', 'Data mata pelajaran berhasil diperbarui.');
        }

        public function destroy(Subject $subject)
        {
            if ($subject->schedules()->count() > 0) {
                return back()->with('error', 'Gagal menghapus. Mata pelajaran ini masih digunakan di jadwal pelajaran.');
            }

            $subject->delete();
            
            return redirect()->route('admin.subjects.index')->with('success', 'Data mata pelajaran berhasil dihapus.');
        }
    }