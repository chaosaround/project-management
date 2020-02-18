import request from './request'

function list(projectID) {
    let params = {};
    if (projectID !== null) params.project_id = projectID
    return request({
        url: `/tasks`,
        method: 'GET',
        params: params
    })
}

function store(task) {
    return request({
        url: `/tasks`,
        method: 'POST',
        data: task
    })
}

function get(task) {
    return request({
        url: `/tasks/${task.id}`,
        method: 'GET'
    })
}

function update(task) {
    return request({
        url: `/tasks/${task.id}`,
        method: 'PUT',
        data: task
    })
}

function destroy(task) {
    return request({
        url: `/tasks/${task.id}`,
        method: 'DELETE'
    })
}

function move(task, type, reference) {
    return request({
        url: `/tasks/${task.id}/move`,
        method: 'PUT',
        params: {
            type: type,
            reference_id: reference.id
        }
    })
}

const TasksAPI = {
    list, store, get, update, destroy, move
}

export default TasksAPI