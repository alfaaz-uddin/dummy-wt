function viewTask(taskId) {
    window.location.href = '../../index.php?page=task-detail&id=' + taskId;
}

function updateTaskStatus(taskId, newStatus) {
    fetch('../../controllers/UpdateTaskStatusController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'task_id=' + taskId + '&status=' + newStatus
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

// Drag and drop functionality
let draggedElement = null;

document.addEventListener('dragstart', function(e) {
    if (e.target.classList.contains('task-card')) {
        draggedElement = e.target;
        e.target.style.opacity = '0.5';
    }
});

document.addEventListener('dragend', function(e) {
    if (e.target.classList.contains('task-card')) {
        e.target.style.opacity = '1';
    }
});

document.querySelectorAll('.tasks-list').forEach(list => {
    list.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.backgroundColor = '#e9ecef';
    });

    list.addEventListener('dragleave', function(e) {
        this.style.backgroundColor = '';
    });

    list.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.backgroundColor = '';
        
        if (draggedElement) {
            const taskId = draggedElement.getAttribute('data-task-id');
            const newStatus = this.getAttribute('data-status');
            
            updateTaskStatus(taskId, newStatus);
        }
    });
});