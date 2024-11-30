@extends('layouts.app')

@section('styles')
<style>
    .max-w-lg {
        width: 100% !important;
        max-width: 100% !important;
    }
    .required-field {
        border-color: red;
    }
    .task-inprogress {
        opacity: 1;
        text-decoration: normal;
    }
    .task-completed {
        opacity: 0.7;
        text-decoration: line-through;
    }
</style>
@endsection

@section('content')
<section class="section">
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-2 mb-2">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Todo Lists') }}
                </h2>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-lg mx-auto">
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="mb-4">
                                <div class="flex shadow-sm">
                                    <input 
                                        type="text" 
                                        name="todo" 
                                        id="todoInput" 
                                        class="w-full p-3 border border-gray-300 rounded-l focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                        placeholder="What do you need to do?"
                                    />
                                    <button
                                        type="button"
                                        id="addTodos"
                                        class="btn btn-md btn-primary text-info px-5 rounded-r hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150"
                                    >
                                        ADD
                                    </button>
                                </div>
                            </div>

                            <!-- Task List -->
                            <ul id="tasksList" class="space-y-2">

                                @foreach ($todos as $todo)
                                <li id="li-{{ $todo->id }}" class="items-center @if($todo->is_completed) task-completed @else task-inprogress @endif">
                                    <input type="checkbox" data-id="todo-{{ $todo->id }}" class="todo-checkbox mr-2" @if($todo->is_completed) checked @endif value="{{ $todo->id }}" />
                                    <span class="@if($todo->is_completed) task-completed @else task-inprogress @endif g-2 task-name">{{ $todo->task }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
$(function() {

    const ajaxHeaders = {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}",
    };

    // Add Task
    $('#addTodos').click(async function() {
        const input = $('#todoInput');
        const task = input.val();
        input.removeClass('required-field');
        if (!task) {
            input.addClass('required-field');
            return;
        }

        await fetch("{{ route('todos.store') }}", {
            method: "POST",
            headers: ajaxHeaders,
            body: JSON.stringify({ task }),
        });

        $('#todoInput').val('');
    });

    // Update Task
    $(document).on('click', 'input.todo-checkbox', async function() {

        const checkbox = $(this);
        const is_completed = checkbox.prop('checked');

        const todoId = checkbox.attr('data-id').split('-')[1];

        if (!todoId) return;

        const url = `{{ url('todos') }}/${todoId}`;

        await fetch(url, {
            method: "PUT",
            headers: ajaxHeaders,
            body: JSON.stringify({ id: todoId, is_completed }),
        });

    });

    window.Echo.channel('todos_create')
    .listen('.todoCreated', (data) => {
        let output = `<li id="li-${data.id}" class="items-center task-inprogress">
            <input type="checkbox" data-id="todo-${data.id}" class="todo-checkbox mr-2" value="${data.id}" />
            <span class="task-inprogress g-2 task-name">${data.task}</span>
        </li>`;
        $('#tasksList').append(output);
    });

    window.Echo.channel('todos_update')
    .listen('.todoUpdated', (data) => {
        if(data.is_completed) {
            $(`#li-${data.id} input[type="checkbox"]`).prop('checked', true);
            $(`#li-${data.id}`).addClass('task-completed').removeClass('task-inprogress');
            $(`#li-${data.id} span`).addClass('task-completed').removeClass('task-inprogress');
        } else {
            $(`#li-${data.id} input[type="checkbox"]`).prop('checked', false);
            $(`#li-${data.id}`).removeClass('task-completed').addClass('task-inprogress');
            $(`#li-${data.id} span.task-name`).removeClass('task-completed').addClass('task-inprogress');
        }
    });
    
});
</script>
@endsection