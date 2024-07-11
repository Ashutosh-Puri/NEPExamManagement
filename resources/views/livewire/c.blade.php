<div>
    @php
    $subjects=App\Models\Subject::paginate(10)
    @endphp
    @foreach ($subjects as $item)
        <p>{{ $item->subject_name }}</p>
    @endforeach
    {{ $subjects->links() }}

</div>
