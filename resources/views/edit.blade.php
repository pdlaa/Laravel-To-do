<h2>Edit Task</h2>

<form action="{{ route('todo.update',$todo->id) }}" method="POST">
    @csrf
    @method('PUT')

    <input type="text" name="task" value="{{ $todo->task }}">
    <input type="date" name="due_date" value="{{ $todo->due_date }}">

    <select name="priority">
        <option value="low" {{ $todo->priority == 'low' ? 'selected' : '' }}>Low</option>
        <option value="medium" {{ $todo->priority == 'medium' ? 'selected' : '' }}>Medium</option>
        <option value="high" {{ $todo->priority == 'high' ? 'selected' : '' }}>High</option>
    </select>

    <button type="submit">Update</button>
</form>
