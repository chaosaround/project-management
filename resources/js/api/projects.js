import request from './request'

function list() {
    return request({
        url: `/projects`,
        method: 'GET'
    })
}

function store(project) {
    return request({
        url: `/projects`,
        method: 'POST',
        data: project
    })
}

function get(project) {
    return request({
        url: `/projects/${project.id}`,
        method: 'GET'
    })
}

function update(project) {
    return request({
        url: `/projects/${project.id}`,
        method: 'PUT',
        data: project
    })
}

function destroy(project) {
    return request({
        url: `/projects/${project.id}`,
        method: 'DELETE'
    })
}

const ProjectsAPI = {
    list, store, get, update, destroy
}

export default ProjectsAPI