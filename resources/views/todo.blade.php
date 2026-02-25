<!DOCTYPE html>
<html>
<head>
    <title>to do list</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .wrapper {
            width: 100%;
            max-width: 1100px;
        }

        h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 25px;
        }

        /* FILTER + SEARCH */
        .toolbar {
            background: white;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }

        .toolbar input,
        .toolbar select {
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        .toolbar button {
            padding: 10px 18px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            background: #0f172a;
            color: white;
            font-weight: 500;
        }

        /* ADD TASK CARD */
        .add-card {
            background: white;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .add-card h3 {
            margin-top: 0;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .add-card form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .add-card input,
        .add-card select {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        .add-card button {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            background: #2563eb;
            color: white;
            font-weight: 600;
        }

        /* TASK CARD */
        .task-card {
            background: white;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.04);
            margin-bottom: 15px;
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .task-title {
            font-size: 16px;
            font-weight: 600;
        }

        .completed {
            text-decoration: line-through;
            color: gray;
        }

        .task-meta {
            font-size: 13px;
            margin-top: 6px;
            color: #64748b;
        }

        .priority {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .low { background: #dcfce7; color: #166534; }
        .medium { background: #fef9c3; color: #854d0e; }
        .high { background: #fee2e2; color: #991b1b; }

        .actions {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            border: none;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-success { background: #16a34a; color: white; }
        .btn-warning { background: #f59e0b; color: white; }
        .btn-danger  { background: #dc2626; color: white; }

        .edit-form {
            margin-top: 15px;
            display: none;
        }

        .edit-form input,
        .edit-form select {
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ddd;
            margin-bottom: 8px;
            width: 100%;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <h1>To do list</h1>

    <!-- TOOLBAR -->
    <form method="GET" class="toolbar">
        <input type="text" name="search" placeholder="Search task..." value="{{ request('search') }}">

        <select name="status">
            <option value="">All Status</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
            <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option>
        </select>

        <select name="priority">
            <option value="">All Priority</option>
            <option value="low" {{ request('priority')=='low'?'selected':'' }}>Low</option>
            <option value="medium" {{ request('priority')=='medium'?'selected':'' }}>Medium</option>
            <option value="high" {{ request('priority')=='high'?'selected':'' }}>High</option>
        </select>

        <select name="sort">
            <option value="">Sort By</option>
            <option value="due_date">Due Date</option>
            <option value="priority">Priority</option>
        </select>

        <button>Apply</button>
    </form>

    <!-- ADD TASK -->
    <div class="add-card">
        <h3>Add New Task</h3>
        <form action="{{ route('todo.store') }}" method="POST">
            @csrf
            <input type="text" name="task" placeholder="Task name..." required>
            <input type="date" name="due_date">
            <select name="priority">
                <option value="low">Low</option>
                <option value="medium" selected>Medium</option>
                <option value="high">High</option>
            </select>
            <button type="submit">Add Task</button>
        </form>
    </div>

    <!-- TASK LIST -->
    @forelse($todos as $todo)
        <div class="task-card">

            <div class="task-header">
                <div>
                    <div class="task-title {{ $todo->completed ? 'completed' : '' }}">
                        {{ $todo->task }}
                    </div>
                    <div class="task-meta">
                        Due: {{ $todo->due_date ?? 'No deadline' }}
                        |
                        <span class="priority {{ $todo->priority }}">
                            {{ ucfirst($todo->priority) }}
                        </span>
                    </div>
                </div>

                <div class="actions">
                    <form action="{{ route('todo.update', $todo->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-success">
                            {{ $todo->completed ? 'Undo' : 'Done' }}
                        </button>
                    </form>

                    <button type="button"
                            class="btn btn-warning"
                            onclick="toggleEdit({{ $todo->id }})">
                        Edit
                    </button>

                    <form action="{{ route('todo.destroy', $todo->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger"
                                onclick="return confirm('Delete this task?')">
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <!-- INLINE EDIT -->
            <div id="edit-{{ $todo->id }}" class="edit-form">
                <form action="{{ route('todo.updateText', $todo->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="text" name="task" value="{{ $todo->task }}" required>
                    <input type="date" name="due_date" value="{{ $todo->due_date }}">
                    <select name="priority">
                        <option value="low" {{ $todo->priority=='low'?'selected':'' }}>Low</option>
                        <option value="medium" {{ $todo->priority=='medium'?'selected':'' }}>Medium</option>
                        <option value="high" {{ $todo->priority=='high'?'selected':'' }}>High</option>
                    </select>
                    <button class="btn btn-warning">Save Changes</button>
                </form>
            </div>

        </div>
    @empty
        <div class="empty">
            No tasks found.
        </div>
    @endforelse

</div>

<script>
function toggleEdit(id){
    let el = document.getElementById('edit-' + id);
    el.style.display = el.style.display === 'block' ? 'none' : 'block';
}
</script>

</body>
</html>
