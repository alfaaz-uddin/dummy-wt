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
            showMessage('Task status updated!', 'success');
        } else {
            showMessage('Error: ' + data.message, 'error');
        }
    });
}

function toggleBlocked(taskId, isChecked) {
    const reasonField = document.getElementById('blocked_reason');
    reasonField.style.display = isChecked ? 'block' : 'none';

    if (isChecked) {
        const reason = reasonField.value;
        if (!reason) {
            alert('Please enter a reason for blocking');
            return;
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        if (checkbox.checked) {
            document.getElementById('blocked_reason').style.display = 'block';
        }
    });
});

function addComment(taskId) {
    const body = document.getElementById('commentBody').value;
    const isInternal = document.getElementById('isInternal').checked;

    if (!body.trim()) {
        alert('Comment cannot be empty');
        return;
    }

    fetch('../../controllers/CreateCommentController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'task_id=' + taskId + '&body=' + encodeURIComponent(body) + '&is_internal=' + (isInternal ? 1 : 0)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('commentBody').value = '';
            document.getElementById('isInternal').checked = false;
            showMessage('Comment added!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showMessage('Error: ' + data.message, 'error');
        }
    });
}

function deleteComment(commentId) {
    if (!confirm('Delete this comment?')) return;

    fetch('../../controllers/DeleteCommentController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'comment_id=' + commentId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Comment deleted!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showMessage('Error: ' + data.message, 'error');
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('uploadForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            const attachment = document.getElementById('attachment').files[0];
            const taskId = new URLSearchParams(window.location.search).get('id');
            
            formData.append('attachment', attachment);
            formData.append('task_id', taskId);

            fetch('../../controllers/UploadAttachmentController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('File uploaded!', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showMessage('Error: ' + data.message, 'error');
                }
            });
        });
    }

    const logTimeForm = document.getElementById('logTimeForm');
    if (logTimeForm) {
        logTimeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const taskId = this.querySelector('input[name="task_id"]').value;
            const hours = this.querySelector('input[name="hours"]').value;
            const note = this.querySelector('textarea[name="note"]').value;

            if (!hours || hours <= 0) {
                alert('Please enter valid hours');
                return;
            }

            fetch('../../controllers/LogTimeController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'task_id=' + taskId + '&hours=' + hours + '&note=' + encodeURIComponent(note)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('Time logged!', 'success');
                    this.reset();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showMessage('Error: ' + data.message, 'error');
                }
            });
        });
    }
});

function showMessage(message, type) {
    const alert = document.createElement('div');
    alert.className = 'alert alert-' + type;
    alert.textContent = message;
    alert.style.position = 'fixed';
    alert.style.top = '20px';
    alert.style.right = '20px';
    alert.style.zIndex = '9999';
    alert.style.minWidth = '300px';
    
    document.body.appendChild(alert);
    
    setTimeout(() => alert.remove(), 3000);
}