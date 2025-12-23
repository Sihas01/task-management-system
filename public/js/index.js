// Function to load tasks from the API and display them in the table
async function loadTasks() {
    const tableBody = document.getElementById('taskTableBody');

    try {
        const response = await fetch('http://localhost/task-management-system/api/read_tasks.php');
        const data = await response.json();

        tableBody.innerHTML = '';

        if (data.status === 'success' && Array.isArray(data.data)) {
            data.data.forEach(task => {
                const row = document.createElement('tr');


                // badge colors based on status
                let statusClass = '';
                switch (task.status) {
                    case 'Pending':
                        statusClass = 'bg-warning text-dark';
                        break;
                    case 'In Progress':
                        statusClass = 'bg-info text-dark';
                        break;
                    case 'Completed':
                        statusClass = 'bg-success text-white';
                        break;
                    default:
                        statusClass = 'bg-secondary text-white';
                }

                row.innerHTML = `
                    <td>${task.id}</td>
                    <td>${task.title}</td>
                    <td>${task.description}</td>
                   <td><span class="badge ${statusClass} p-1">${task.status}</span></td>
                    <td>
                <button class="btn btn-primary btn-sm" onclick="updateTask(${task.id})">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button class="btn btn-danger btn-sm" onclick="deleteTask(${task.id})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
                `;
                tableBody.appendChild(row);
            });
        }
    } catch (err) {
        console.error('Error fetching tasks:', err);
    }
}


// load tasks when the page loads
window.addEventListener('load', loadTasks);



// Function to create a new task
async function createTask() {
    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');

    const formData = new FormData();
    formData.append('title', titleInput.value);
    formData.append('description', descriptionInput.value);

    try {
        const response = await fetch('http://localhost/task-management-system/api/create_task.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.status === 'success') {

            // Close the modal
            const modalEl = document.getElementById('exampleModal');
            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.hide();

            titleInput.value = '';
            descriptionInput.value = '';

            loadTasks();

            alert('Task created successfully!');
        } else {
            alert(result.message || 'Error creating task');
        }
    } catch (err) {
        console.error('Error:', err);
    }
}


// Function to delete a task
async function deleteTask(taskId) {
    if (!confirm('Are you sure you want to delete this task?')) return;

    const formData = new FormData();
    formData.append('id', taskId);

    try {
        const response = await fetch('http://localhost/task-management-system/api/delete_task.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.status === 'success') {
            alert('Task deleted successfully!');
            await loadTasks(); // refresh table
        } else {
            alert(result.message || 'Error deleting task');
        }
    } catch (err) {
        console.error('Error deleting task:', err);
    }
}



function updateTask(taskId) {

    // Find the task row in the table
    const taskRow = Array.from(document.querySelectorAll('#taskTableBody tr'))
        .find(tr => tr.children[0].textContent == taskId);

    if (!taskRow) return;

    // Fill modal fields 
    document.getElementById('edit-task-id').value = taskId;
    document.getElementById('edit-task-title').value = taskRow.children[1].textContent;
    document.getElementById('edit-task-desc').value = taskRow.children[2].textContent;
    document.getElementById('edit-task-status').value = taskRow.children[3].textContent;

    // Show modal
    const modalEl = document.getElementById('editTaskModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}


// Function to submit edited task
async function submitEditTask() {
    const id = document.getElementById('edit-task-id').value;
    const title = document.getElementById('edit-task-title').value;
    const description = document.getElementById('edit-task-desc').value;
    const status = document.getElementById('edit-task-status').value;

    const formData = new FormData();
    formData.append('id', id);
    formData.append('title', title);
    formData.append('description', description);
    formData.append('status', status);

    try {
        const response = await fetch('http://localhost/task-management-system/api/update_task.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message || 'Task updated successfully');

            // Close modal
            const modalEl = document.getElementById('editTaskModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            loadTasks();
        } else {
            alert(result.message || 'Error updating task');
        }
    } catch (err) {
        console.error('Error:', err);
    }
}
