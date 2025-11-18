<div class="text-center">
    <div class="btn-group btn-group-sm">
        

        <a href="{{ route('users.index', $project->id) }}" class="btn btn-sm btn-warning">
            <i class="fas fa-edit">eidt</i>
        </a>

        <a href="#" class="btn btn-sm btn-danger deletebtn" data-id="{{ $project->id }}">
            <i class="fas fa-trash-alt">delete</i>
        </a>
    </div>
</div>
