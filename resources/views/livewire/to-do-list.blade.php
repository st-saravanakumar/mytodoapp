<div class="container d-flex justify-content-center align-items-center mt-3" style="min-height: 100vh;">
    <div class="row w-100">
        <div class="col-md-6 offset-md-3">
            <div class="card text-center">
                <div class="card-header">
                    <input type="text" wire:model="task" class="form-control mb-2" placeholder="What do you need to do?">
                    <button wire:click="addTask" class="btn btn-primary">ADD</button>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach($todos as $todo)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <input type="checkbox" wire:click="toggleCompletion({{ $todo->id }})" {{ $todo->completed ? 'checked' : '' }}>
                                <span class="{{ $todo->completed ? 'text-muted text-decoration-line-through' : '' }}">
                                    {{ $todo->task }}
                                </span>
                            </div>
                            <button wire:click="removeTask({{ $todo->id }})" class="btn btn-danger btn-sm">X</button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
