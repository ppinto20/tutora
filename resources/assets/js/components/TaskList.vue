<template>
    <div class='row'>
        <h1>Todo List</h1>

        <form action="#" @submit.prevent="edit ? updateTask(task.id) : createTask()">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group">
                        <input ref:taskinput v-model="task.description" type="text" name="description" class="form-control" autofocus>
                        <span class="input-group-btn">
                            <button v-show="!edit" type="submit" class="btn btn-primary">New Task</button>
                            <button v-show="edit" type="submit" class="btn btn-primary">Update Task</button>
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input ref:tasknotification v-model="task.notification" type="email" name="notification" placeholder="example@example.com" class="form-control">
                        <span class="input-group-addon">
                            Notify
                        </span>
                    </div>
                </div>
            </div>
        </form>

        <h4>All Tasks</h4>
        <ul class="list-group">
            <li v-if='list.length === 0'>Empty</li>
            <li class="list-group-item" v-for="(task, index) in list">
                 {{ task.description }}
                 <button @click="deleteTask(task.id)" class="btn btn-danger btn-xs pull-right">Delete</button>
                 <button @click="showTask(task.id)" class="btn btn-primary btn-xs pull-right">Edit</button>
            </li>
        </ul>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                edit: false,
                list: [],
                task: {
                    id: '',
                    description: ''
                }
            };
        },
        
        created() {
            this.fetchTaskList();
        },
        
        methods: {
            fetchTaskList() {
                axios.get('api/tasks').then((res) => {
                    this.list = res.data;
                });
            },
 
            createTask() {
                axios.post('api/tasks', this.task)
                    .then((res) => {
                        this.task.description = '';
                        this.edit = false;
                        this.task.notification = '';
                        this.fetchTaskList();
                        swal('Task created!','','success');
                    })
                    .catch((err) => swal('Oops...','Something went wrong','error'));
            },

            updateTask(id) {
                axios.put('api/task/' + id, this.task)
                    .then((res) => {
                        this.task.description = ''
                        this.task.notification = '';
                        this.edit = false
                        this.fetchTaskList()
                        swal('Task updated!','','success');
                    })
                .catch((err) => swal('Oops...','Something went wrong','error'));
            },

            showTask(id) {
                axios.get('api/task/' + id).then((res) => {
                    this.task.id = res.data.id
                    this.task.description = res.data.description
                    this.edit = true
                })
                .catch((err) => swal('Oops...','Something went wrong','error'));
            },
 
            deleteTask(id) {
                axios.delete('api/tasks/' + id)
                    .then((res) => {
                        this.fetchTaskList()
                        swal('Task deleted!','','success');
                    })
                    .catch((err) => swal('Oops...','Something went wrong','error'));
            },
        }
    }
</script>
</script>