<div class="text-center">
    <div class="btn-group btn-group-sm">
        {{-- <a href="{{ route('admin.projects.show', $user->id) }}" class="btn btn-sm btn-info">
            <i class="fas fa-eye"></i>
        </a>

        <a href="{{ route('admin.projects.edit', $user->id) }}" class="btn btn-sm btn-warning">
            <i class="fas fa-edit"></i>
        </a> --}}

        <a href="#" class="btn btn-sm btn-danger deletebtn" data-id="{{ $user->id }}">
            delete
        </a>
    </div>
</div>
