<template>
    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="text-right">
                        <button type="button" class="btn btn-link" @click="showProjectsList = !showProjectsList">
                            {{ showProjectsList ? 'Hide' : 'Show' }} projects list
                        </button>
                    </div>
                    <crud-list v-if="showProjectsList" title="Projects" :data="projects"
                               @add="showModal('Create', 'Project')"
                               @edit="(project) => { showModal('Update', 'Project', project) }"
                               @delete="(project) => { showModal('Delete', 'Project', project) }"
                    />
                    <crud-list title="Tasks" :data="tasks" :draggable="true"
                               @list-dragged="tasksListDragged"
                               @item-dragged="taskDragged"
                               @add="showModal('Create', 'Task')"
                               @edit="(task) => { showModal('Update', 'Task', task) }"
                               @delete="(task) => { showModal('Delete', 'Task', task) }">
                        <template v-slot:filter>
                            Project:
                            <select title="Filter by project" @change="selectProject">
                                <option value="">All projects</option>
                                <option v-for="project in projects" :value="project.id">{{ project.name }}</option>
                            </select>
                        </template>
                    </crud-list>
                </div>
            </div>
        </div>
        <modal title="Create project" :show-modal="modals.showCreateProjectModal" ok-button-text="Create"
               @modal-close="closeModals" @ok="createProject">
            <template>
                <div class="form-group">
                    <label for="project-name-create">Project name *</label>
                    <input type="text" class="form-control" id="project-name-create" v-model="forms.name">
                    <small v-for="error in forms.formErrors.name" class="form-text text-danger">{{ error }}</small>
                </div>
            </template>
        </modal>
        <modal title="Edit project" :show-modal="modals.showUpdateProjectModal" ok-button-text="Update"
               @modal-close="closeModals" @ok="updateProject">
            <template>
                <div class="form-group">
                    <label for="project-name-update">Project name *</label>
                    <input type="text" class="form-control" id="project-name-update" v-model="forms.name">
                    <small v-for="error in forms.formErrors.name" class="form-text text-danger">{{ error }}</small>
                </div>
            </template>
        </modal>
        <modal title="Delete project" :show-modal="modals.showDeleteProjectModal" ok-button-text="Delete"
               ok-button-type="danger"
               @modal-close="closeModals" @ok="deleteProject">
            <template>
                <p v-if="objectForModal">Are you sure you want to delete project "{{ objectForModal.name }}"?</p>
            </template>
        </modal>
        <modal title="Create task" :show-modal="modals.showCreateTaskModal" ok-button-text="Create"
               @modal-close="closeModals" @ok="createTask">
            <template>
                <div class="form-group">
                    <label for="task-name-create">Task name *</label>
                    <input type="text" class="form-control" id="task-name-create" v-model="forms.name">
                    <small v-for="error in forms.formErrors.name" class="form-text text-danger">{{ error }}</small>
                </div>
                <div class="form-group">
                    <label for="task-project-create">Project (optional)</label>
                    <select v-model="forms.projectID" id="task-project-create" title="Select project"
                            class="form-control">
                        <option value="">No project</option>
                        <option v-for="project in projects" :value="project.id">{{ project.name }}</option>
                    </select>
                </div>
            </template>
        </modal>
        <modal title="Edit task" :show-modal="modals.showUpdateTaskModal" ok-button-text="Update"
               @modal-close="closeModals" @ok="updateTask">
            <template>
                <div class="form-group">
                    <label for="task-name-update">Task name</label>
                    <input type="text" class="form-control" id="task-name-update" v-model="forms.name">
                    <small v-for="error in forms.formErrors.name" class="form-text text-danger">{{ error }}</small>
                </div>
                <div class="form-group">
                    <label for="task-project-update">Project (optional)</label>
                    <select v-model="forms.projectID" id="task-project-update" title="Select project"
                            class="form-control">
                        <option value="">No project</option>
                        <option v-for="project in projects" :value="project.id">{{ project.name }}</option>
                    </select>
                </div>
            </template>
        </modal>
        <modal title="Delete task" :show-modal="modals.showDeleteTaskModal" ok-button-text="Delete"
               ok-button-type="danger"
               @modal-close="closeModals" @ok="deleteTask">
            <template>
                <p v-if="objectForModal">Are you sure you want to delete task "{{ objectForModal.name }}"?</p>
            </template>
        </modal>
    </main>
</template>

<script>
    import {mapGetters} from "vuex";
    import Modal from './Modal'
    import CrudList from './crud/CrudList'

    export default {
        components: {
            Modal,
            CrudList
        },
        computed: {
            ...mapGetters(['projects', 'tasks'])
        },
        data() {
            return {
                showProjectsList: false,
                objectForModal: null,
                modals: {
                    showCreateProjectModal: false,
                    showUpdateProjectModal: false,
                    showDeleteProjectModal: false,
                    showCreateTaskModal: false,
                    showUpdateTaskModal: false,
                    showDeleteTaskModal: false
                },
                forms: {
                    formErrors: {},
                    name: '',
                    projectID: ''
                }
            }
        },
        methods: {
            selectProject(e) {
                let value = e.target.value;
                if (value === '') value = null;
                this.$store.dispatch('selectProject', value).then(() => {
                    this.$store.dispatch("load")
                })
            },
            tasksListDragged(tasks) {
                this.$store.dispatch('updateTasks', tasks)
            },
            taskDragged(index) {
                let element = this.tasks[index]
                if (index > 0) {
                    let previous = this.tasks[index - 1]
                    // move after sibling task
                    this.$store.dispatch('moveTask', {
                        task: element,
                        type: 'after',
                        reference: previous
                    })

                }
                else {
                    let next = this.tasks[index + 1]
                    // move before sibling task
                    this.$store.dispatch('moveTask', {
                        task: element,
                        type: 'before',
                        reference: next
                    })
                }
            },
            showModal(action, entity, object = null) {
                this.modals['show' + action + entity + 'Modal'] = true
                this.objectForModal = object
                if (object) {
                    this.forms.name = object.name
                    this.forms.projectID = object.project ? object.project.id : ''
                }
            },
            closeModals() {
                this.clearForms()
                Object.keys(this.modals).forEach(p => this.modals[p] = false)
            },
            clearForms() {
                this.forms.formErrors = {}
                this.forms.name = ''
            },
            createProject() {
                let self = this
                this.$store.dispatch('createProject', this.forms.name)
                    .then(() => {
                        self.closeModals()
                        self.$store.dispatch("load")
                    })
                    .catch(function (error) {
                        self.$set(self.forms, 'formErrors', error.data.errors)
                    })
            },
            updateProject() {
                let self = this
                this.$store.dispatch('updateProject',
                    {
                        id: this.objectForModal.id,
                        name: this.forms.name
                    })
                    .then(() => {
                        self.closeModals()
                        self.$store.dispatch("load")
                    })
                    .catch(function (error) {
                        self.$set(self.forms, 'formErrors', error.data.errors)
                    })
            },
            deleteProject() {
                let self = this
                this.$store.dispatch('deleteProject', this.objectForModal)
                    .then(() => {
                        self.closeModals()
                        self.$store.dispatch("load")
                    })
                    .catch(function (error) {
                        alert(error.message)
                    })
            },
            createTask() {
                let self = this
                let projectID = this.forms.projectID !== '' ? this.forms.projectID : null
                console.log(projectID)
                this.$store.dispatch('createTask',
                    {
                        name: this.forms.name,
                        projectID: projectID
                    })
                    .then(() => {
                        self.closeModals()
                        self.$store.dispatch("load")
                    })
                    .catch(function (error) {
                        self.$set(self.forms, 'formErrors', error.data.errors)
                    })
            },
            updateTask() {
                let self = this
                let projectID = this.forms.projectID ? this.forms.projectID : null
                this.$store.dispatch('updateTask',
                    {
                        id: this.objectForModal.id,
                        name: this.forms.name,
                        projectID: projectID
                    })
                    .then(() => {
                        self.closeModals()
                        self.$store.dispatch("load")
                    })
                    .catch(function (error) {
                        self.$set(self.forms, 'formErrors', error.data.errors)
                    })
            },
            deleteTask() {
                let self = this
                this.$store.dispatch('deleteTask', this.objectForModal)
                    .then(() => {
                        self.closeModals()
                        self.$store.dispatch("load")
                    })
                    .catch(function (error) {
                        alert(error.message)
                    })
            }
        },
        created() {
            this.$store.dispatch("load")
        }
    }

</script>

<style scoped>
    .btn-link {
        border-bottom: dashed 1px #3490dc;
        padding: 0;
        line-height: 1.1;
        text-decoration: none !important;
    }
</style>