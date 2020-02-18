import ProjectsAPI from './api/projects'
import TasksAPI from './api/tasks'

import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

// initial state
const state = {
    projects: [],
    selectedProjectID: null,
    tasks: []
}

// getters
const getters = {
    projects: state => state.projects,
    tasks: state => state.tasks,
}

// actions
const actions = {
    load() {
        this.dispatch("getProjects").then(() => {
            this.dispatch("getTasks")
        })
    },
    getProjects({commit}) {
        return ProjectsAPI.list().then(projects => {
            commit('setProjects', projects.data)
        })
    },
    createProject({}, name) {
        return ProjectsAPI.store({name: name})
    },
    updateProject({}, project) {
        return ProjectsAPI.update(project)
    },
    deleteProject({}, project) {
        return ProjectsAPI.destroy(project)
    },
    selectProject({commit}, projectID) {
        commit('selectProject', projectID)
    },
    getTasks({commit, state}) {
        return TasksAPI.list(state.selectedProjectID).then(tasks => {
            commit('setTasks', tasks.data)
        })
    },
    createTask({}, {name, projectID}) {
        return TasksAPI.store({
            name: name,
            project_id: projectID
        })
    },
    updateTask({}, {id, name, projectID}) {
        return TasksAPI.update({
            id: id,
            name: name,
            project_id: projectID
        })
    },
    deleteTask({}, task) {
        return TasksAPI.destroy(task)
    },
    updateTasks({commit}, tasks) {
        commit('updateTasks', tasks)
    },
    moveTask({commit}, {task, type, reference}) {
        return TasksAPI.move(task, type, reference)
    }
}

// mutations
const mutations = {
    setProjects(state, projects) {
        state.projects = projects
    },
    selectProject(state, projectID) {
        state.selectedProjectID = projectID
    },
    setTasks(state, tasks) {
        state.tasks = []
        tasks.forEach(task => {
            task.tag = task.project ? task.project.name : null
            state.tasks.push(task)
        })
    },
    updateTasks(state, tasks) {
        Vue.set(state, 'tasks', tasks)
    }
}

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations
})