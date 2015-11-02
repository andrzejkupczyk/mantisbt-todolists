(function() {

    window.ToDoList = new Vue({
        el: '#ToDoLists',
        data: {
            tasks: [],
            lang: {},
            newTask: {
                bug_id: 0,
                description: ''
            }
        },
        methods: {
            addTask: function(e) {
                e.preventDefault();
                if (!this.validate()) {
                    return;
                }
                this.$http.post(this.$el.action, {
                    task: this.newTask
                }, function(response) {
                    this.tasks.push(response);
                    this.newTask.description = '';
                });
            },
            saveTask: function(task) {
                task.finished = !!task.finished;
                this.$http.put(this.$el.action, {
                    task: task
                }).error(function() {
                    task.finished = !task.finished;
                });
            },
            editTask: function(task, e){
                e.preventDefault();
                description = prompt(this.lang.titleEditTask, task.description);
                
                if(description != null){
                    task.description = description;
                }
                
                this.$http.put(this.$el.action, {
                    task: task
                });
            },
            deleteTask: function(task, e) {
                e.preventDefault();
                if (!task.finished && !confirm(this.lang.deleteConfirmation)) {
                    return;
                }
                this.$http.delete(this.$el.action + '&id=' + task.id, {
                    id: task.id
                }, function() {
                    this.tasks.$remove(task);
                });
            },
            validate: function() {
                var descLength = this.newTask.description.length;
                return descLength > 0 && descLength <= 120;
            }
        }
    });

})();