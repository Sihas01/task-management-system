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
                row.innerHTML = `
                    <td>${task.id}</td>
                    <td>${task.title}</td>
                    <td>${task.description}</td>
                    <td>${task.status}</td>
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



